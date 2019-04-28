<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;

/**
 * Class Client
 * @package Models
 *
 * Properties:
 * @property int id
 * @property string type
 * @property string name
 * @property string city
 * @property string street
 * @property string zip
 * @property string longitude
 * @property string latitude
 * @property string contact_name
 * @property string contact_email
 * @property string contact_phone
 * @property boolean is_active
 * @property boolean is_deleted
 * @property DateTime updated_at
 * @property DateTime created_at
 */
class Client extends Model
{
	public static $table_name 			= 'clients';

	public static $validate_is_present 	= [
		'name', 'type', 'contact_name'
	];

	public static $validate_is_unique 	= [
		[ 'name' ]
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
		$params['group'] 				= 'clients.id';

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

			if ($column == 'address')
				$column 	= 'street';

			$order 		= sprintf("%s %s", $column, $direction);
		}

		return $order;
	}

	/**
	 * @param $client_id
	 * @return array
	 * @throws PageNotFound
	 */
	public static function get($client_id)
	{
		/** @var Client $client */
		if (!($client = self::get_first([ 'id' => $client_id, 'is_deleted' => FALSE])))
			throw new PageNotFound;

		return $client->summary();
	}

	/**
	 * @return array
	 */
	public function summary()
	{
		$client = [
			'id' 				=> $this->id,
			'name' 				=> $this->name,
			'type' 				=> $this->type,
			'city' 				=> $this->city,
			'street' 			=> $this->street,
			'zip' 				=> $this->zip,
			'longitude' 		=> $this->longitude,
			'latitude' 			=> $this->latitude,
			'contact_name' 		=> $this->contact_name,
			'contact_email' 	=> $this->contact_email,
			'contact_phone' 	=> $this->contact_phone,
			'is_active' 		=> $this->is_active,
			'is_deleted' 		=> $this->is_deleted,
		];

		return $client;
	}
}