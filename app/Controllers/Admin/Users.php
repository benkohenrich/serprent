<?php

namespace Controllers\Admin;

use Appendix\Core\I18n;
use Appendix\Core\Router;
use Appendix\Libraries\Input;
use Controllers\Admin;
use Appendix\Exceptions\PageNotFound;
use Helpers\DataTableHelper;
use Helpers\ModelHelper;
use Helpers\Responder;
use Helpers\Utils;
use Libraries\Tables\ControlsBuilder;
use Libraries\Tables\RowBuilder;
use Libraries\Tables\TableBuilder;
use Models\Role;
use Models\User;

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
				$controls->add('remove', Router::uri([ 'users', 'remove', $user['id'] ]));

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

	private function save(User $user, $attributes)
	{
		$errors 					= new \Appendix\Libraries\Errors();
		$parsed_attributes 			= Utils::parse_input($user, $attributes);

		$user->name 				= $parsed_attributes['name'];
		$user->surname 				= $parsed_attributes['surname'];

		if (!is_null($parsed_attributes['is_active']))
			$user->is_active 		= $parsed_attributes['is_active'];

		if (!$user->save())
		{
			$errors->merge_others($user->errors->raw());

			echo Responder::initialize()->respond(422, Utils::reformat_errors($errors));
			die;
		}

		Input::set_session('user_save_success', I18n::load('users.form.flash.success'));

		echo Responder::initialize()->respond(201, [
			'route' 		=> Router::uri([ 'users', 'edit', $user->id ])
		]);
		die;
	}

	private function fill_register()
	{
		$roles 		= Role::get_roles();

		foreach($roles as &$role)
		{
			$role['name'] 		= I18n::load('app.roles.' . $role['name']);
		}

		$this->view->register([
			'roles' 			=> $roles,
			'permissions' 		=> Role::get_permissions()
		]);
	}
}