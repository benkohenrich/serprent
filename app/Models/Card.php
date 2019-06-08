<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class Card
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int client_id
 * @property string code
 * @property boolean is_active
 * @property boolean is_deleted
 * @property DateTime updated_at
 * @property DateTime created_at
 *
 * Relations:
 * @property Client client
 * @property UserCard user_card
 */
class Card extends Model
{
	public static $table_name 			= 'cards';

	public static $validate_is_present 	= [
		'client_id', 'code'
	];

	public static $validate_is_unique 	= [
		[ 'code' ]
	];

	static $belongs_to = [
		[
			'client',
			'foreign_key' 	=> 'client_id',
			'class_name' 	=> Client::class
		],
	];

	static $has_one = [
		[
			'user_card',
			'foreign_key' 	=> 'card_id',
			'class_name' 	=> UserCard::class
		]
	];

	/**
	 * @param array $filter
	 * @param array $ordering
	 * @param array $additional_params
	 * @return array
	 */
	public static function find_all($filter = [], $ordering = [], $additional_params = [])
	{
		$conditions 					= [];
		$conditions[0] 					= "cards.is_deleted IS FALSE";

		if (isset($filter['client_id']))
		{
			$conditions[0] 		= sprintf("%s AND cards.client_id = ?", $conditions[0]);
			$conditions[] 		= $filter['client_id'];
		}

		if (isset($filter['not_assigned']) AND !isset($filter['user_id']))
		{
			$conditions[0] 		= sprintf("%s AND cards.id NOT IN (SELECT card_id FROM user_cards)", $conditions[0]);
		}

		if (!isset($filter['not_assigned']) AND isset($filter['user_id']))
		{
			$conditions[0] 		= sprintf("%s AND user_cards.user_id = ?", $conditions[0]);
			$conditions[] 		= $filter['user_id'];
		}

		if (isset($filter['not_assigned']) AND isset($filter['user_id']))
		{
			$conditions[0] 		= sprintf("%s AND ((cards.id NOT IN (SELECT card_id FROM user_cards)) OR (user_cards.user_id = ?))", $conditions[0]);
			$conditions[] 		= $filter['user_id'];
		}

		$params 						= [];
		$joins 							= [
			'LEFT JOIN user_cards ON cards.id = user_cards.card_id',
			'LEFT JOIN system_users ON user_cards.user_id = system_users.id'
		];

		$params['conditions'] 			= $conditions;
		$params['joins'] 				= $joins;

		$total_items 					= self::count($params);

		if (isset($filter['limit']) && $filter['limit'] > 0)
		{
			$params['offset'] 			= isset($filter['offset']) ? ($filter['offset'] * $filter['limit']) : 0;
			$params['limit'] 			= $filter['limit'];
		}

		$params['order'] 					= self::order($ordering);

		if (empty($params['order']))
		{
			$params['order'] 			= "cards.id ASC";
		}

		$params['joins'] 				= array_unique($params['joins']);
		$params['group'] 				= 'cards.id';

		$params 						= array_merge($params, $additional_params);

		return [
			$total_items,
			self::get_all($params)
		];
	}

	/**
	 * @param $ordering
	 * @return string
	 */
	private static function order($ordering)
	{
		$order 		= '';

		if (!empty($ordering['column']) AND !empty($ordering['direction']))
		{
			$column 		= $ordering['column'];
			$direction 		= $ordering['direction'];

			if ($column == 'card_user')
			{
				return sprintf("system_users.name %s, system_users.surname %s", $direction, $direction);
			}

			$order 		= sprintf("cards.%s %s", $column, $direction);
		}

		return $order;
	}

	/**
	 * @param $card_id
	 * @return array
	 * @throws PageNotFound
	 */
	public static function get($card_id)
	{
		/** @var Card $card */
		if (!($card = self::get_first([ 'id' => $card_id, 'is_deleted' => FALSE])))
			throw new PageNotFound;

		return $card->summary();
	}

	/**
	 * @return array
	 */
	public function summary()
	{
		$card = [
			'id' 				=> $this->id,
			'code' 				=> $this->code,
			'name' 				=> $this->code,
			'is_active' 		=> $this->is_active,
			'is_deleted' 		=> $this->is_deleted,
			'card_user' 		=> ModelHelper::prepare($this->user_card),
			'client' 			=> ModelHelper::prepare($this->client)
		];

		return $card;
	}
}