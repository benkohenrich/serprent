<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;

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

	/**
	 * @param array $filter
	 * @param array $ordering
	 * @param array $additional_params
	 * @return array
	 */
	public static function find_all($filter = [], $ordering = [], $additional_params = [])
	{
		$conditions 					= [];
		$conditions[0] 					= "is_deleted IS FALSE";

		$params 						= [];
		$joins 							= [];

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
			$params['order'] 			= "id ASC";
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

			$order 		= sprintf("%s %s", $column, $direction);
		}

		return $order;
	}

	/**
	 * @return array
	 */
	public function summary()
	{
		$client = [
			'id' 				=> $this->id,
			'client_id' 		=> $this->client_id,
			'code' 				=> $this->code,
			'is_active' 		=> $this->is_active,
			'is_deleted' 		=> $this->is_deleted,
		];

		return $client;
	}
}