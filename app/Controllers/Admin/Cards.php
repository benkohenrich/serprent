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
use Libraries\Tables\ControlsBuilder;
use Libraries\Tables\RowBuilder;
use Libraries\Tables\TableBuilder;
use Models\Card;
use Models\User;
use Models\UserCard;

class Cards extends Admin
{
	/**
	 * Users constructor.
	 * @throws PageNotFound
	 * @throws \Exceptions\SystemException
	 */
	public function __construct()
	{
		parent::__construct();

		if (!$this->check_permission('cards.management') AND !Input::is_ajax_request())
			throw new PageNotFound;

		$this->view->register([
			'section' 		=> 'cards'
		]);
	}

	public function overview()
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);

			$filter 							= DataTableHelper::create_filter_array();
			$ordering 							= DataTableHelper::create_ordering_array();

			list($total_items, $response) 		= Card::find_all($filter, $ordering);

			$table 								= new TableBuilder();
			$cards 								= ModelHelper::prepare($response);

			/** @var Card $card */
			foreach ($cards as $card)
			{
				$row 		= new RowBuilder();
				$controls 	= new ControlsBuilder();

				$card_user 	= !empty($card['card_user']['user']['full_name'])
					? $card['card_user']['user']['full_name']
					: "";

				$row->add($card['code']);
				$row->add($card_user);
				$row->add(I18n::load('cards.booleans.is_active.' . $card['is_active']));

				$controls->add('edit', Router::uri([ 'cards', 'edit', $card['id'] ]));
				$controls->add('remove', Router::uri([ 'cards', 'remove', $card['id'] ]), [
					'confirm_message' => I18n::load('cards.delete.confirm_message')
				]);

				$row->add($controls->data());

				$table->add($row->data());
			}

			echo Responder::initialize()->respond(200, DataTableHelper::create_response(
				$table->data(), $total_items, Card::count([ 'conditions' => [ 'is_deleted' => 0 ] ]), $filter, $ordering
			));
		}
		else
		{
			$this->view->register([
				'page_title' 				=> I18n::load('cards.overview.breadcrumbs.header'),
				'page' 						=> 'overview_cards',
				'breadcrumbs' 				=> [
					[
						'title' 			=> I18n::load('cards.overview.breadcrumbs.title'),
						'url' 				=> Router::uri([ 'cards' ])
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
				$this->save(new Card(), $attributes);
			}
		}

		$this->fill_register();

		$this->view->register([
			'page_title' 				=> I18n::load('cards.create.breadcrumbs.header'),
			'page' 						=> 'create_card',
			'form_id' 					=> 'create-card',
			'breadcrumbs' 				=> [
				[
					'title' 				=> I18n::load('cards.create.breadcrumbs.title'),
					'url' 					=> Router::uri([ 'cards' ])
				],
				[
					'title' 				=> I18n::load('cards.create.breadcrumbs.active'),
					'url' 					=> Router::uri([ 'cards', 'create' ])
				],
			]
		]);
	}

	/**
	 * @param $card_id
	 * @throws PageNotFound
	 */
	public function edit($card_id)
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);
			$attributes 			= [];

			if (($form_data = json_decode($this->input->body('form_data'), TRUE)))
				parse_str($form_data, $attributes);

			if (!empty($attributes['save']))
			{
				/** @var Card $card */
				$card 		= Card::get_first($card_id);

				$this->save($card, $attributes);
			}
		}

		$this->fill_register();

		$card 				= Card::get($card_id);

		if ($card['client']['id'] !== $this->user->client_id AND !$this->user->is_superadmin())
			Router::redirect([ 'cards' ]);

		$save_success 		= Input::session('card_save_success');
		Input::destroy_session('card_save_success');

		$this->view->register([
			'page_title' 				=> I18n::load('cards.edit.breadcrumbs.header'),
			'page' 						=> 'edit_card',
			'form_id' 					=> 'edit-card',
			'card' 						=> $card,
			'save_success' 				=> $save_success,
			'breadcrumbs' 				=> [
				[
					'title' 				=> I18n::load('cards.edit.breadcrumbs.title'),
					'url' 					=> Router::uri([ 'cards' ])
				],
				[
					'title' 				=> I18n::load('cards.edit.breadcrumbs.active'),
					'url' 					=> Router::uri([ 'cards', 'edit', $card['id'] ])
				],
			]
		]);
	}

	/**
	 * @param Card $card
	 * @param $attributes
	 */
	private function save(Card $card, $attributes)
	{
		$errors 					= new Errors();
		$parsed_attributes 			= Utils::parse_input($card, $attributes);
		$activity_code 				= ($card->is_new_record()) ? 'card.create' : 'card.edit';

		$this->db->transaction('start');

		$card->code 				= $parsed_attributes['code'];
		$card->client_id 			= $parsed_attributes['client_id'];

		if (!is_null($parsed_attributes['is_active']))
			$card->is_active 		= $parsed_attributes['is_active'];

		if (!$card->save())
			$errors->merge_others($card->errors->raw());

		UserCard::delete_all([
			'conditions' 	=> [
				'card_id' 		=> $card->id,
				'client_id' 	=> $card->client_id
			]
		]);

		if (!empty($attributes['user_id']))
		{
			$user_card 		= new UserCard([
				'client_id' 	=> $card->client_id,
				'user_id' 		=> $attributes['user_id'],
				'card_id' 		=> $card->id
			]);

			if (!$user_card->save())
				$errors->merge_others($user_card->errors->raw());
		}

		if (!$errors->is_empty())
		{
			$this->db->transaction('stop');

			echo Responder::initialize()->respond(422, Utils::reformat_errors($errors));
			die;
		}

		$this->db->transaction('finish');

		$this->event->notify($activity_code, ModelHelper::prepare($card));

		Input::set_session('card_save_success', I18n::load('cards.form.flash.success'));

		echo Responder::initialize()->respond(201, [
			'route' 		=> Router::uri([ 'cards', 'edit', $card->id ])
		]);
		die;
	}

	private function fill_register()
	{
		$users 		= User::find_all_for_client($this->user->client_id);

		$this->view->register([
			'users' 	=> $users
		]);
	}

	/**
	 * @param $card_id
	 * @return false|string
	 * @throws \Exception
	 */
	public function remove($card_id)
	{
		$this->view->set_file(FALSE);

		$errors 		= new Errors();

		$this->db->transaction('start');

		/** @var Card $card */
		if (!($card = Card::get_first([ 'id' => $card_id, 'is_deleted' => FALSE ])))
		{
			$errors->add('card', I18n::load('cards.delete.errors.not_existing_card'));
		}
		else
		{
			if ($card->client_id !== $this->user->client_id)
			{
				$errors->add('card', I18n::load('cards.delete.errors.client_mismatch'));
			}

			$card->is_deleted 		= 1;
			$card->code 			= sprintf("%s-%s", $card->code, md5((new \DateTime())->format("Y-m-d H:i:s") . rand()));

			if(!$card->save())
			{
				$errors->merge_others($card->errors->raw());
			}
			else
			{
				UserCard::delete_all([
					'conditions' 	=> [
						'card_id' 		=> $card->id
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

		$this->event->notify('card.remove', ModelHelper::prepare($card));

		return Responder::initialize()->respond(204);
	}
}