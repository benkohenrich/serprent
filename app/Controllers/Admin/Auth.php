<?php

namespace Controllers\Admin;

use Appendix\Core\I18n;
use Appendix\Core\Router;
use Appendix\Core\System;
use Controllers\Admin;
use Models\Role;
use Models\User;
use Helpers\Logger as MyLogger;

class Auth extends Admin
{
	public function login()
	{
		if ($this->input->session('token'))
		{
			Router::redirect(
				(array) System::config('admin.default_action_logged')
			);
		}

		if ($this->input->body("email") && $this->input->body("password"))
		{
			$status 			= $this->auth->login($this->input->body("email"), $this->input->body("password"), [], 1);

			if (($status === TRUE) && $this->auth->logged_in('cms.login'))
			{
				$this->user 	= User::get_first($this->auth->user_id());

				$this->logger->user_id($this->user->id);

				$this->event->notify('auth.login.success');

				Router::redirect(
					(array) System::config('admin.default_action_logged')
				);
			}
			else
			{
				$this->event->notify('auth.login.fail');

				$this->view->register([
					'status' 					=> I18n::load("auth.login.wrong-combination")
				]);
			}
		}
	}


	public function logout()
	{
		$this->event->notify('auth.logout');

		$this->input->destroy_session('active_client');

		$this->auth->logout();

		Router::redirect(['auth', 'login']);
	}
}