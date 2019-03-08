<?php
namespace Helpers;

use Appendix\Core\Router;
use Appendix\Libraries\Event;
use Helpers\Logger;

class Responder
{
	private static $instance 		= NULL;
	private static $pretty_print 	= FALSE;

	/**
	 * Spusti vytvaranie celeho prostredia. Funkcia vytvara internu instanciu
	 * cez navrhovy vzor Singleton.
	 *
	 * @param bool $pretty
	 * @return Responder
	 *
	 */
	public static function initialize($pretty = FALSE)
	{
		if (!is_object(self::$instance))
		{
			self::$instance = new Responder();
			self::$pretty_print = (bool) is_string($pretty);
		}

		return self::$instance;
	}

	public function respond($http_status_code, $data = NULL)
	{
		Router::header($http_status_code);

		Event::instance()->attach([
			"api.response" 					=> [ Logger::instance(), 'warn' ]
		]);

		Event::instance()->notify("api.response", [
			'status' 						=> $http_status_code,
			'data' 							=> $data
		]);

		if (is_string($data))
		{
			$data 							= [
				'message' 					=> $data
			];
		}

		if ($data === NULL)
			return '';

		return json_encode($data, self::$pretty_print ? JSON_PRETTY_PRINT : 0);
	}
}