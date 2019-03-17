<?php

namespace Controllers\Admin;

use Appendix\Core\I18n;
use Appendix\Core\Router;
use Appendix\Core\System;
use Appendix\Libraries\Input;
use Controllers\Admin;
use Models\User;

class Auth extends Admin
{
	public function login()
	{
		if (Input::session('token'))
		{
			Router::redirect(System::config('admin.default_action_logged'));
		}

		if ($this->input->body("email") && $this->input->body("password"))
		{
			$status 			= $this->auth->login($this->input->body("email"), $this->input->body("password"), [], 1);

			if (($status === TRUE) && $this->auth->logged_in('auth.login'))
			{
				$this->user 	= User::get_first($this->auth->user_id());

				$this->logger->user_id($this->user->id);

				$this->event->notify('auth.login.success');

				Router::redirect(
					(array) System::config('admin.default_action_logged'),
					[
						'flash' 	=> [
							I18n::load('auth.login.flash.login_success'), 'success'
						]
					]
				);
			}
			else
			{
				$this->event->notify('auth.login.fail');

				$this->view->register([
					'flash' 	=> [
						I18n::load("auth.login.errors.wrong_combination"), 'error'
					]
				]);
			}
		}
	}


	public function logout()
	{
		$this->event->notify('auth.logout');

		$this->auth->logout();

		Router::redirect(['login']);
	}
}