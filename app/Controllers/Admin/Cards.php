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
use Models\Card;

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

				$row->add($card['code']);
				$row->add(I18n::load('cards.booleans.is_active.' . $card['is_active']));

				$controls->add('edit', Router::uri([ 'cards', 'edit', $card['id'] ]));
				$controls->add('remove', Router::uri([ 'cards', 'remove', $card['id'] ]));

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
	 */
	public function edit($card_id)
	{
		/** @var Card $card */
		$card 			= Card::get_first($card_id);

		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);
			$attributes 			= [];

			if (($form_data = json_decode($this->input->body('form_data'), TRUE)))
				parse_str($form_data, $attributes);

			if (!empty($attributes['save']))
			{
				$this->save($card, $attributes);
			}
		}

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
					'url' 					=> Router::uri([ 'cards', 'edit', $card->id ])
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
		$errors 					= new \Appendix\Libraries\Errors();
		$parsed_attributes 			= Utils::parse_input($card, $attributes);

		$card->code 				= $parsed_attributes['code'];
		$card->client_id 			= $parsed_attributes['client_id'];

		if (!is_null($parsed_attributes['is_active']))
			$card->is_active 		= $parsed_attributes['is_active'];

		if (!$card->save())
		{
			$errors->merge_others($card->errors->raw());

			echo Responder::initialize()->respond(422, Utils::reformat_errors($errors));
			die;
		}

		Input::set_session('card_save_success', I18n::load('cards.form.flash.success'));

		echo Responder::initialize()->respond(201, [
			'route' 		=> Router::uri([ 'cards', 'edit', $card->id ])
		]);
		die;
	}
}