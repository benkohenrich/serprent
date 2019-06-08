<?php

namespace Controllers\Admin;

use Appendix\Core\I18n;
use Appendix\Core\Router;
use Appendix\Libraries\Errors;
use Appendix\Libraries\Input;
use Appendix\Models\Language;
use Appendix\Models\Permission;
use Appendix\Models\UserInfo;
use Controllers\Admin;
use Appendix\Exceptions\PageNotFound;
use Helpers\DataTableHelper;
use Helpers\ModelHelper;
use Helpers\Responder;
use Helpers\Utils;
use Libraries\Tables\ControlsBuilder;
use Libraries\Tables\RowBuilder;
use Libraries\Tables\TableBuilder;
use Models\Card;
use Models\Role;
use Models\User;
use Models\UserCard;

class Users extends Admin
{
	/**
	 * Users constructor.
	 * @throws PageNotFound
	 * @throws \Exceptions\SystemException
	 */
	public function __construct()
	{
		parent::__construct();

		if (!$this->check_permission('users.management') AND !Input::is_ajax_request())
			throw new PageNotFound;

		$this->view->register([
			'section' 		=> 'users'
		]);
	}

	public function overview()
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);

			$filter 							= DataTableHelper::create_filter_array();
			$ordering 							= DataTableHelper::create_ordering_array();

			list($total_items, $response) 		= User::find_all($filter, $ordering);

			$table 								= new TableBuilder();
			$users 								= ModelHelper::prepare($response);

			/** @var User $user */
			foreach ($users as $user)
			{
				$row 		= new RowBuilder();
				$controls 	= new ControlsBuilder();

				$row->add($user['full_name']);
				$row->add($user['email']);
				$row->add(I18n::load('users.enums.roles.' . $user['role_id']));
				$row->add(I18n::load('users.booleans.is_active.' . $user['is_active']));

				$controls->add('edit', Router::uri([ 'users', 'edit', $user['id'] ]));
				$controls->add('remove', Router::uri([ 'users', 'remove', $user['id'] ]), [
					'confirm_message' => I18n::load('users.delete.confirm_message')
				]);

				$row->add($controls->data());

				$table->add($row->data());
			}

			echo Responder::initialize()->respond(200, DataTableHelper::create_response(
				$table->data(), $total_items, User::count([ 'conditions' => [ 'is_deleted' => 0 ] ]), $filter, $ordering
			));
		}
		else
		{
			$this->view->register([
				'page_title' 				=> I18n::load('users.overview.breadcrumbs.header'),
				'page' 						=> 'overview_users',
				'breadcrumbs' 				=> [
					[
						'title' 			=> I18n::load('users.overview.breadcrumbs.title'),
						'url' 				=> Router::uri([ 'users' ])
					],
				],
			]);
		}
	}

	/**
	 * @throws \Exceptions\SystemException
	 */
	public function create()
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);
			$attributes 			= [];

			if (($form_data = json_decode($this->input->body('form_data'), TRUE)))
				parse_str($form_data, $attributes);

			if (!empty($attributes['save']))
			{
				$this->save(new User(), $attributes);
			}
		}

		$this->fill_register();

		$this->view->register([
			'page_title' 				=> I18n::load('users.create.breadcrumbs.header'),
			'page' 						=> 'create_user',
			'form_id' 					=> 'create-user',
			'breadcrumbs' 				=> [
				[
					'title' 				=> I18n::load('users.create.breadcrumbs.title'),
					'url' 					=> Router::uri([ 'users' ])
				],
				[
					'title' 				=> I18n::load('users.create.breadcrumbs.active'),
					'url' 					=> Router::uri([ 'users', 'create' ])
				],
			]
		]);
	}

	/**
	 * @param $user_id
	 * @throws PageNotFound
	 * @throws \Exceptions\SystemException
	 */
	public function edit($user_id)
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);
			$attributes 			= [];

			if (($form_data = json_decode($this->input->body('form_data'), TRUE)))
				parse_str($form_data, $attributes);

			if (!empty($attributes['save']))
			{
				/** @var User $user */
				$user 			= User::get_first($user_id);

				$this->save($user, $attributes);
			}
		}

		$this->fill_register($user_id);

		$user 				= User::get($user_id);

		if ($user['client']['id'] !== $this->user->client_id AND !$this->user->is_superadmin())
			Router::redirect([ 'users' ]);

		$save_success 		= Input::session('user_save_success');
		Input::destroy_session('user_save_success');

		$this->view->register([
			'page_title' 				=> I18n::load('users.edit.breadcrumbs.header'),
			'page' 						=> 'edit_user',
			'form_id' 					=> 'edit-user',
			'user' 						=> $user,
			'save_success' 				=> $save_success,
			'assigned_permissions' 		=> Role::get_permissions_for_user($user_id),
			'breadcrumbs' 				=> [
				[
					'title' 				=> I18n::load('users.edit.breadcrumbs.title'),
					'url' 					=> Router::uri([ 'users' ])
				],
				[
					'title' 				=> I18n::load('users.edit.breadcrumbs.active'),
					'url' 					=> Router::uri([ 'users', 'edit', $user['id'] ])
				],
			]
		]);
	}

	/**
	 * @param User $user
	 * @param $attributes
	 * @throws \Exceptions\SystemException
	 */
	private function save(User $user, $attributes)
	{
		$errors 					= new \Appendix\Libraries\Errors();
		$parsed_attributes 			= Utils::parse_input($user, $attributes);
		$activity_code 				= ($user->is_new_record()) ? 'user.create' : 'user.edit';

		$this->db->transaction('start');

		if ($user->is_new_record())
		{
			if (($existing_email = User::get_first([ 'client_id' => $parsed_attributes['client_id'], 'email' => $parsed_attributes['email'] ])))
				$errors->add('email', I18n::load('users.form.errors.email_used'));

			if ($user->compare_to_confirm($attributes['password'], $attributes['password_again']))
			{
				$user->password = $this->auth->hash($attributes['password']);

				if (!$user->minimal_password_length())
					$errors->add('email', I18n::load('users.form.errors.password_length'));

				if (!$user->pattern_confirm())
					$errors->add('email', I18n::load('users.form.errors.pattern_match'));
			}
			else
			{
				$errors->add('password', I18n::load('users.form.errors.password_mismatch'));
			}

			$user->username 		= sprintf("%s-%s", $parsed_attributes['email'], $parsed_attributes['client_id']);
			$user->email 			= $parsed_attributes['email'];
		}

		$user->name 				= $parsed_attributes['name'];
		$user->surname 				= $parsed_attributes['surname'];
		$user->client_id 			= $parsed_attributes['client_id'];
		$user->role_id 				= $parsed_attributes['role_id'];
		$user->language_id 			= $parsed_attributes['language_id'];

		if (!is_null($parsed_attributes['is_active']))
			$user->is_active 		= $parsed_attributes['is_active'];

		if (!$user->save())
			$errors->merge_others($user->errors->raw());
		else
		{
			// PERMISSIONS
			if (!empty($attributes['permissions'][$user->role_id]))
			{
				$user_role_info = UserInfo::get_first([
					'conditions' => [
						'user_id = ? AND role_id IS NOT NULL AND value IS TRUE', $user->id
					]
				]);

				if (!($user_role_info))
					$user_role_info = new UserInfo();

				$user_role_info->user_id  	= $user->id;
				$user_role_info->role_id  	= $user->role_id;
				$user_role_info->value  	= 1;

				if (!$user_role_info->save())
					$errors->merge_others($user_role_info->errors->raw());

				UserInfo::delete_all([
					'conditions' => [
						'user_id = ? AND permission_id IS NOT NULL AND value IS FALSE', $user->id
					]
				]);

				foreach ($attributes['permissions'][$user->role_id] as $permission_id => $value)
				{
					if ($value ===  "0")
					{
						$user_permission_info 	= new UserInfo([
							'user_id' 				=> $user->id,
							'permission_id' 		=> $permission_id,
							'value' 				=> 0
						]);

						if (!$user_permission_info->save())
							$errors->merge_others($user_permission_info->errors->raw());
					}
				}
			}

			// CARDS
			if ($this->check_permission('cards.management'))
			{
				if (!empty($attributes['card_id']))
				{
					UserCard::delete_all([
						"conditions" 	=> [
							'user_id' 		=> $user->id,
							'client_id' 	=> $user->client_id
						]
					]);

					foreach ($attributes['card_id'] as $card_id)
					{
						if (!empty($card_id))
						{
							if (!($existing_user_card = UserCard::get_first([ 'card_id' => $card_id ])))
							{
								$user_card = new UserCard([
									'user_id' 		=> $user->id,
									'card_id' 		=> $card_id,
									'client_id' 	=> $user->client_id
								]);

								if (!$user_card->save())
									$errors->merge_others($user_card->errors->raw());
							}
						}
					}
				}
			}
		}

		if (!$errors->is_empty())
		{
			$this->db->transaction('stop');

			echo Responder::initialize()->respond(422, Utils::reformat_errors($errors));
			die;
		}

		$this->db->transaction('finish');

		$this->event->notify($activity_code, ModelHelper::prepare($user));

		Input::set_session('user_save_success', I18n::load('users.form.flash.success'));

		echo Responder::initialize()->respond(201, [
			'route' 		=> Router::uri([ 'users', 'edit', $user->id ])
		]);
		die;
	}

	private function fill_register($user_id = NULL)
	{
		$roles 		= Role::get_roles($this->user->role_id);

		foreach($roles as &$role)
		{
			$role['name'] 		= I18n::load('app.roles.' . $role['name']);
		}

		$languages = Language::get_all([
			'conditions' 	=> [
				'is_deleted' 	=> 0
			]
		]);

		foreach($languages as &$language)
		{
			$language->name 		= I18n::load('app.languages.' . $language->name);
		}

		$card_filters 	= [
			'client_id' 		=> $this->user->client_id,
			'not_assigned' 		=> TRUE
		];

		if ($user_id)
			$card_filters['user_id'] 	= $user_id;

		list($card_meta, $cards) 	= Card::find_all($card_filters);

		$this->view->register([
			'roles' 			=> $roles,
			'permissions' 		=> Role::get_permissions(),
			'languages' 		=> $languages,
			'cards' 			=> ModelHelper::prepare($cards)
		]);
	}

	public function remove($user_id)
	{
		$this->view->set_file(FALSE);

		$errors 		= new Errors();

		$this->db->transaction('start');

		/** @var User $user */
		if (!($user = User::get_first([ 'id' => $user_id, 'is_deleted' => FALSE ])))
		{
			$errors->add('user', I18n::load('users.delete.errors.not_existing_user'));
		}
		else
		{
			if ($user->client_id !== $this->user->client_id)
			{
				$errors->add('user', I18n::load('users.delete.errors.client_mismatch'));
			}

			$random_string 			= md5((new \DateTime())->format("Y-m-d H:i:s") . rand());

			$user->is_deleted 		= 1;
			$user->email 			= sprintf("%s-%s", $user->email, $random_string);
			$user->username 		= sprintf("%s-%s", $user->username, $random_string);

			if(!$user->save())
			{
				$errors->merge_others($user->errors->raw());
			}
			else
			{
				UserCard::delete_all([
					'conditions' 	=> [
						'user_id' 		=> $user->id
					]
				]);
			}
		}

		if (!$errors->is_empty())
		{
			$this->db->transaction('stop');

			echo Responder::initialize()->respond(422, Utils::reformat_errors($errors));
			die;
		}

		$this->db->transaction('finish');

		$this->event->notify('user.remove', ModelHelper::prepare($user));

		return Responder::initialize()->respond(204);
	}
}