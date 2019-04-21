<?php


namespace Models;

use Appendix\Core\Model;
use Appendix\Models\UserInfo;
use ActiveRecord\RecordNotFound;
use Appendix\Libraries\Errors;

/**
 * Class User
 * @package Models
 *
 * Properties:
 * @property int id
 *
 * Relations:
 */
class Role extends \Appendix\Models\Role
{
	const ROLE_ADMIN = 1;
	const ROLE_USER = 2;

	public static function get_permissions()
	{
		$role_permissions = [];

		try
		{
			$role_permissions = \Appendix\Models\UserInfo::all([
				'select' 		=> 'system_users_info.permission_id, system_permissions.code, value, role_id',
				'joins' 		=> 'LEFT JOIN system_permissions ON system_permissions.id = system_users_info.permission_id',
				'conditions' 	=> [ "system_users_info.user_id IS NULL AND permission_id IS NOT NULL AND system_permissions.is_visible = 1" ],
				'order' 		=> 'permission_id ASC'
			]);
		}
		catch (RecordNotFound $e)
		{
			// Velka chyba, toto by sa nikdy nemalo stat
		}

		return $role_permissions;
	}

	public static function get_roles($role_id)
	{
		$__roles = [];

		try
		{
			$__roles = Model::prepare(Role::all([
				'select' 		=> 'id, name',
				'order' 		=> 'id ASC',
				'conditions' 	=> [ 'id >= ?', $role_id ]
			]));
		}
		catch (RecordNotFound $e)
		{
			// Velka chyba, toto by sa nikdy nemalo stat
		}

		return $__roles;
	}

	public static function get_permissions_for_user($user_id)
	{
		$user_permissions = [];

		try
		{
			$user_permissions = \Appendix\Models\UserInfo::all([
				'conditions' 	=> [ "system_users_info.user_id = ?", $user_id ],
				'order' 		=> 'permission_id ASC'
			]);
		}
		catch (RecordNotFound $e)
		{
			// Velka chyba, toto by sa nikdy nemalo stat
		}

		/** @var UserInfo $user_permission */

		$is_default = TRUE;
		$permissions = [];
		$result = [];
		$result['role'] = NULL;

		if ($user_permissions)
		{
			foreach ($user_permissions as $user_permission)
			{
				if ($user_permission->role_id)
				{
					$result['role'] = $user_permission->role_id;
				}

				if ($user_permission->permission_id !== NULL)
				{
					$is_default = FALSE;
					$permissions[$user_permission->permission_id] = $user_permission->value;
				}
			}
		}

		if ($is_default == TRUE)
		{
			$result['permissions'] = NULL;
		}
		else
		{
			$result['permissions'] = $permissions;
		}

		return $result;
	}

	public static function delete_role_and_permissions_for_user($user_id)
	{
		$user_permissions = [];

		$errors 									= new Errors();

		try
		{
			$user_permissions = \Appendix\Models\UserInfo::all([
				'conditions' 	=> [ "system_users_info.user_id = ?", $user_id ],
				'order' 		=> 'permission_id ASC'
			]);
		}
		catch (RecordNotFound $e)
		{
			// Velka chyba, toto by sa nikdy nemalo stat
		}



		$permissions = [];

		if ($user_permissions)
		{
			/** @var UserInfo $user_permission */
			foreach ($user_permissions as $user_permission)
			{
				if(!$user_permission->delete())
				{
					$errors->merge_others($user_permission->errors->raw());

					return [
						'errors' 			=> $errors,
						'permissions' 		=> $permissions
					];
				}

				$permissions[] = $user_permission;
			}
		}

		return [
			'errors' 			=> $errors,
			'permissions' 		=> $permissions
		];
	}
}