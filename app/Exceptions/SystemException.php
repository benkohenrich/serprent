<?php

namespace Exceptions;

use Appendix\Core\Router;
use Appendix\Core\System;
use Appendix\Libraries\Db;
use Appendix\Libraries\Event;
use Appendix\Libraries\Input;
use Appendix\Libraries\Logger;
use Helpers\Responder;

/**
 * Class SystemException
 * @package Exceptions
 *
 * V pripade ze sa jedna o AJAX request, vraciam JSON
 * V pripade ze sa jedna o bezny non-AJAX request, robim redirect a pridavam toastr message
 */
class SystemException extends \Exception
{
	public function __construct($code = 500, $message = "Internal server error", \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

//		TODO add to DB
		Event::instance()->attach([
			"system.exception" 		=> [ Logger::instance(), 'warn' ]
		]);

		if (Db::instance()->connection()->connection->inTransaction())
		{
			Db::instance()->transaction("stop");
		}

		if ($previous)
		{
			Event::instance()->notify("system.exception", $this->summary());
		}

		if (Input::is_ajax_request())
		{
			Responder::initialize()->respond($code, $this->summary());
		}
		else
		{
			Input::instance()->set_session("toastr", [
				'type' 			=> 'error',
				'message' 		=> $this->getMessage()
			]);

			$this->redirect();
		}
	}

	public function summary()
	{
		return [
			'code' 					=> (int) $this->getCode(),
			'message' 				=> (string) $this->getMessage()
		];
	}

	protected function redirect()
	{
		Router::redirect(empty($_SERVER['HTTP_REFERER']) ? System::config('admin.default_action_logged') : $_SERVER['HTTP_REFERER']);
	}
}