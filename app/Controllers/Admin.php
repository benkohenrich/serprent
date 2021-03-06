<?php

namespace Controllers;

use ActiveRecord\Config;
use Appendix\Core\Controller;

use Appendix\Core\Router;
use Appendix\Core\System;
use Appendix\Libraries\Auth;
use Exceptions\SystemException;
use Models\User;

class Admin extends Controller
{
	/** @var \Appendix\Libraries\Drivers\Auth\Permissionbased */
	protected $auth;

	/** @var \Models\User */
	protected $user;

	public function __construct()
	{
		parent::__construct();

		$this->auth 				= new Auth('Permissionbased');

		$this->attach_logs();

		if (!$this->auth->logged_in('auth.login'))
		{
			$this->check_whitelist();
		}
		else
		{
			$this->user 			= User::get_first($this->auth->user_id());

			$this->logger->user_id($this->user->id);

			$this->set_actual(Router::$uri_segments);

			$this->view->register([
				'self' 					=> [
					'full_name' 			=> $this->user->get_full_name(),
					'id' 					=> $this->user->id,
					'permissions' 			=> $this->auth->permissions(),
					'role_id' 				=> $this->user->get_role()->id,
					'language_code' 		=> $this->user->get_language_code(),
					'client' 				=> $this->user->client,
					'is_superadmin' 		=> $this->user->is_superadmin(),
				],
				'system_roles' 			=> System::config('app.system_roles')
			]);
		}

		$this->view->register([
			'current_language' 				=> Router::active_language()
		]);
	}

	public function sorry()
	{
		$this->view->set_file('Pages\404.html');
	}

	protected function store_uri()
	{
		if (isset($_SERVER['HTTP_HOST']) AND isset($_SERVER['REQUEST_URI']))
		{
			$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			if (filter_var($uri, FILTER_VALIDATE_URL))
			{
				$this->input->set_session('uri', urlencode($uri));
			}
		}
	}

	protected function set_actual($uri)
	{
		if (empty($uri))
			return FALSE;

		$this->view->register([
			'active_controller' 		=> $uri[0]
		]);

		return TRUE;
	}

	protected function check_permission($code, $throw = FALSE)
	{
		$result = !empty($this->auth->permissions()[$code]);

		if (!$result AND $throw === TRUE)
			throw new SystemException(403, 'Insufficient rights');

		return $result;
	}

	private function check_whitelist()
	{
		$whitelist 					= [
			'Admin\Auth::login',
			'Admin\Auth::logout',
		];

		$method 					= sprintf("%s::%s", Router::$callback[0], Router::$callback[1]);

		if (!in_array($method, $whitelist))
		{
			$this->store_uri();

			$this->input->destroy_session("token");

			Router::redirect([ 'login' ]);
		}
	}

	private function attach_logs()
	{
		$this->event->attach([
			'auth.login.success' 				=> [ $this->logger, 'info' ],
			'auth.login.fail' 					=> [ $this->logger, 'warn' ],
			'auth.logout' 						=> [ $this->logger, 'info' ],
			'client.create' 					=> [ $this->logger, 'info' ],
			'client.edit' 						=> [ $this->logger, 'info' ],
			'client.remove' 					=> [ $this->logger, 'info' ],
			'user.crete' 						=> [ $this->logger, 'info' ],
			'user.edit' 						=> [ $this->logger, 'info' ],
			'user.remove' 						=> [ $this->logger, 'info' ],
			'card.crete' 						=> [ $this->logger, 'info' ],
			'card.edit' 						=> [ $this->logger, 'info' ],
			'card.remove' 						=> [ $this->logger, 'info' ],
		]);
	}
}