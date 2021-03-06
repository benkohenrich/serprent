<?php

namespace Controllers\Admin;

use Appendix\Core\I18n;
use Appendix\Core\Router;
use Appendix\Libraries\Errors;
use Appendix\Libraries\Input;
use Controllers\Admin;
use Appendix\Exceptions\PageNotFound;
use Helpers\DataTableHelper;
use Helpers\ModelHelper;
use Helpers\Responder;
use Helpers\Utils;
use Libraries\Enums\Enums;
use Libraries\Tables\ControlsBuilder;
use Libraries\Tables\RowBuilder;
use Libraries\Tables\TableBuilder;
use Models\Card;
use Models\Client;
use Models\User;
use Models\UserCard;

class Clients extends Admin
{
	/**
	 * Users constructor.
	 * @throws PageNotFound
	 * @throws \Exceptions\SystemException
	 */
	public function __construct()
	{
		parent::__construct();

		if (!$this->check_permission('clients.management'))
			throw new PageNotFound;

		$this->view->register([
			'section' 		=> 'clients'
		]);
	}

	public function overview()
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);

			$filter 							= DataTableHelper::create_filter_array();
			$ordering 							= DataTableHelper::create_ordering_array();

			list($total_items, $response) 		= Client::find_all($filter, $ordering);

			$table 								= new TableBuilder();
			$clients 							= ModelHelper::prepare($response);

			/** @var Client $client */
			foreach ($clients as $client)
			{
				$row 		= new RowBuilder();
				$controls 	= new ControlsBuilder();

				$row->add($client['name']);
				$row->add(sprintf("%s %s %s", $client['street'], $client['city'], $client['zip']));
				$row->add($client['contact_name']);
				$row->add(I18n::load('clients.enums.type.' . $client['type']));
				$row->add(I18n::load('clients.booleans.is_active.' . $client['is_active']));

				$controls->add('edit', Router::uri([ 'clients', 'edit', $client['id'] ]));
				$controls->add('remove', Router::uri([ 'clients', 'remove', $client['id'] ]), [
					'confirm_message' => I18n::load('clients.delete.confirm_message')
				]);

				$row->add($controls->data());

				$table->add($row->data());
			}

			echo Responder::initialize()->respond(200, DataTableHelper::create_response(
				$table->data(), $total_items, Client::count([ 'conditions' => [ 'is_deleted' => 0 ] ]), $filter, $ordering
			));
		}
		else
		{
			$this->view->register([
				'page_title' 				=> I18n::load('clients.overview.breadcrumbs.header'),
				'page' 						=> 'overview_clients',
				'breadcrumbs' 				=> [
					[
						'title' 			=> I18n::load('clients.overview.breadcrumbs.title'),
						'url' 				=> Router::uri([ 'clients' ])
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
				$this->save(new Client(), $attributes);
			}
		}

		$this->view->register([
			'page_title' 				=> I18n::load('clients.create.breadcrumbs.header'),
			'page' 						=> 'create_client',
			'types' 					=> Enums::clients()->type(),
			'form_id' 					=> 'create-client',
			'breadcrumbs' 				=> [
				[
					'title' 				=> I18n::load('clients.create.breadcrumbs.title'),
					'url' 					=> Router::uri([ 'clients' ])
				],
				[
					'title' 				=> I18n::load('clients.create.breadcrumbs.active'),
					'url' 					=> Router::uri([ 'clients', 'create' ])
				],
			]
		]);
	}

	/**
	 * @param $client_id
	 * @throws PageNotFound
	 */
	public function edit($client_id)
	{
		if ($client_id !== $this->user->client_id AND !$this->user->is_superadmin())
			Router::redirect([ 'clients' ]);

		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);
			$attributes 			= [];

			if (($form_data = json_decode($this->input->body('form_data'), TRUE)))
				parse_str($form_data, $attributes);

			if (!empty($attributes['save']))
			{
				/** @var Client $client */
				$client 		= Client::get_first($client_id);

				$this->save($client, $attributes);
			}
		}

		$client 			= Client::get($client_id);
		$save_success 		= Input::session('client_save_success');
		Input::destroy_session('client_save_success');

		$this->view->register([
			'page_title' 				=> I18n::load('clients.edit.breadcrumbs.header'),
			'page' 						=> 'edit_client',
			'types' 					=> Enums::clients()->type(),
			'form_id' 					=> 'edit-client',
			'client' 					=> $client,
			'save_success' 				=> $save_success,
			'breadcrumbs' 				=> [
				[
					'title' 				=> I18n::load('clients.edit.breadcrumbs.title'),
					'url' 					=> Router::uri([ 'clients' ])
				],
				[
					'title' 				=> I18n::load('clients.edit.breadcrumbs.active'),
					'url' 					=> Router::uri([ 'clients', 'edit', $client['id'] ])
				],
			]
		]);
	}

	/**
	 * @param Client $client
	 * @param array $attributes
	 * @return false|string
	 */
	private function save(Client $client, $attributes)
	{
		$errors 					= new \Appendix\Libraries\Errors();
		$parsed_attributes 			= Utils::parse_input($client, $attributes);
		$activity_code 				= ($client->is_new_record()) ? 'client.create' : 'client.edit';

		$client->name 				= $parsed_attributes['name'];
		$client->type 				= $parsed_attributes['type'];
		$client->city 				= $parsed_attributes['city'];
		$client->street 			= $parsed_attributes['street'];
		$client->zip 				= $parsed_attributes['zip'];
		$client->longitude 			= $parsed_attributes['longitude'];
		$client->latitude 			= $parsed_attributes['latitude'];

		$client->contact_name 		= $parsed_attributes['contact_name'];
		$client->contact_phone 		= $parsed_attributes['contact_phone'];
		$client->contact_email 		= $parsed_attributes['contact_email'];

		if (!is_null($parsed_attributes['is_active']))
			$client->is_active 		= $parsed_attributes['is_active'];

		if (!$client->save())
		{
			$errors->merge_others($client->errors->raw());

			echo Responder::initialize()->respond(422, Utils::reformat_errors($errors));
			die;
		}

		$this->event->notify($activity_code, ModelHelper::prepare($client));

		Input::set_session('client_save_success', I18n::load('clients.form.flash.success'));

		echo Responder::initialize()->respond(201, [
			'route' 		=> Router::uri([ 'clients', 'edit', $client->id ])
		]);
		die;
	}

	public function remove($client_id)
	{
		$this->view->set_file(FALSE);

		$errors 		= new Errors();

		$this->db->transaction('start');

		/** @var Client $client */
		if (!($client = Client::get_first([ 'id' => $client_id, 'is_deleted' => FALSE ])))
		{
			$errors->add('client', I18n::load('clients.delete.errors.not_existing_client'));
		}
		else
		{
			$client->is_deleted 		= 1;

			if(!$client->save())
			{
				$errors->merge_others($client->errors->raw());
			}
			else
			{
				$client_users 		= User::get_all([ 'client_id' => $client_id ]);

				if (!empty($client_users))
				{
					/** @var User $client_user */
					foreach ($client_users as $client_user)
					{
						$random_string 				= md5((new \DateTime())->format("Y-m-d H:i:s") . rand());

						$client_user->email 		= sprintf("%s-%s", $client_user->email, $random_string);
						$client_user->username 		= sprintf("%s-%s", $client_user->username, $random_string);
						$client_user->is_deleted 	= 1;

						if (!$client_user->save())
							$errors->merge_others($client_user->errors->raw());
					}
				}

				$client_cards 		= Card::get_all([ 'client_id' => $client_id ]);

				if (!empty($client_cards))
				{
					/** @var Card $client_card */
					foreach ($client_cards as $client_card)
					{
						$client_card->code 			= sprintf("%s-%s", $client_card->code, md5((new \DateTime())->format("Y-m-d H:i:s") . rand()));
						$client_card->is_deleted 	= 1;

						if (!$client_card->save())
							$errors->merge_others($client_card->errors->raw());
					}
				}

				if ($errors->is_empty())
				{
					UserCard::delete_all([
						'conditions' 	=> [
							'client_id' 	=> $client_id
						]
					]);
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

		$this->event->notify('client.remove', ModelHelper::prepare($client));

		return Responder::initialize()->respond(204);
	}
}