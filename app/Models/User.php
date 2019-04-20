<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Core\System;
use Appendix\Libraries\Input;
use Appendix\Models\Language;
use Appendix\Models\UserInfo;
use Helpers\ModelHelper;

/**
 * Class User
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int client_id
 * @property int role_id
 * @property int language_id
 * @property string email
 * @property string username
 * @property string password
 * @property string name
 * @property string surname
 * @property boolean is_active
 * @property boolean is_deleted
 * @property DateTime created_at
 * @property DateTime updated_at
 *
 * Relations:
 * @property Client client
 */
class User extends Model
{
	public static $table_name 			= 'system_users';

	public static $validate_is_email 	= [
		[ 'email' ]
	];

	public static $validate_is_present 	= [
		'email', 'username', 'password', 'name', 'surname', 'role_id', 'language_id', 'client_id'
	];

	public static $validate_by_custom 	= [
		'password[minimal_password_length]',
		'password_again[compare_to_confirm]',
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
		$params['group'] 				= 'system_users.id';

		$params 						= array_merge($params, $additional_params);

		return [
			$total_items,
			self::get_all($params)
		];
	}

	/**
	 * @return string
	 */
	public function get_full_name()
	{
		return sprintf("%s %s", $this->name, $this->surname);
	}

	/**
	 * @return \ActiveRecord\Model|NULL
	 */
	public function get_role()
	{
		$user_info = UserInfo::get_first([
			'conditions' 				=> sprintf('user_id = %d AND role_id IS NOT NULL', $this->id)
		]);

		return Role::get_first($user_info->role_id);
	}

	/**
	 * @return array|mixed|null
	 */
	public function get_language_code()
	{
		/** @var Language $language */
		$language = Language::get_first($this->language_id);

		return $language->code;
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

			if ($column == 'full_name')
			{
				return sprintf("name %s, surname %s", $direction, $direction);
			}

			$order 		= sprintf("%s %s", $column, $direction);
		}

		return $order;
	}

	/**
	 * @return bool
	 */
	public function minimal_password_length()
	{
		if (!empty($this->password) AND Input::instance()->body('password'))
		{
			if (strlen(Input::instance()->body('password')) < System::config('app.minimal_password_length'))
				return FALSE;
		}

		return TRUE;
	}

	/**
	 * @return bool
	 */
	public function compare_to_confirm()
	{
		if (Input::instance()->body('password_again'))
		{
			$pass1 							= Input::instance()->body('password');
			$pass2 							= Input::instance()->body('password_again');

			if ($pass1 !== $pass2)
				return FALSE;
		}

		return TRUE;
	}

	/**
	 * @return array
	 */
	public function summary()
	{
		$user = [
			'id' 				=> $this->id,
			'client_id' 		=> $this->client_id,
			'full_name' 		=> $this->get_full_name(),
			'role_id' 			=> $this->role_id,
			'language_id' 		=> $this->language_id,
			'language_code' 	=> $this->get_language_code(),
			'email' 			=> $this->email,
			'is_active' 		=> $this->is_active,
			'is_deleted' 		=> $this->is_deleted,
		];

		return $user;
	}
}