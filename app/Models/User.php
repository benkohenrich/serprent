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
 * @property int role_id
 * @property int language_id
 * @property string email
 * @property string username
 * @property string password
 * @property string name
 * @property string surname
 * @property boolean is_active
 * @property boolean is_deleted
 * @property DateTime last_login_at
 * @property DateTime created_at
 * @property DateTime updated_at
 */
class User extends Model
{
	public static $table_name 			= 'system_users';

	public static $validate_is_email 	= [
		['email']
	];

	public static $validate_is_present 	= [
		'email',
		'username',
		'password',
		'name',
		'surname'
	];

	public static $validate_is_unique 	= [
		['email']
	];

	public static $validate_by_custom 	= [
		'password[minimal_password_length]',
		'password_again[compare_to_confirm]',
	];

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

		if (!empty($ordering))
		{
			$params['order'] 		= sprintf("%s %s", $ordering['column'], $ordering['direction']);
		}
		else
		{
			$params['order'] 			= "created_at ASC";
		}

		$params['joins'] 				= array_unique($params['joins']);
		$params['group'] 				= 'system_users.id';

		$params 						= array_merge($params, $additional_params);

		return [
			$total_items,
			self::get_all($params)
		];
	}

	public function get_full_name()
	{
		return sprintf("%s %s", $this->name, $this->surname);
	}

	public function minimal_password_length()
	{
		if (!empty($this->password) AND Input::instance()->body('password'))
		{
			if (strlen(Input::instance()->body('password')) < System::config('app.minimal_password_length'))
				return FALSE;
		}

		return TRUE;
	}

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

	public function get_role()
	{
		$user_info = UserInfo::get_first([
			'conditions' 				=> sprintf('user_id = %d AND role_id IS NOT NULL', $this->id)
		]);

		return Role::get_first($user_info->role_id);
	}

	public function get_language_code()
	{
		$language = Language::get_first($this->language_id);

		return $language->code;
	}
}