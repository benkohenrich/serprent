<?php


namespace Helpers;
use Appendix\Core\Router;
use Appendix\Libraries\Drivers\Auth\Api;
use Appendix\Libraries\Input;
use Appendix\Models\History;

/**
 * Vlastný logger ktorý je SubClassom Loggeru z Frameworku
 * Tuto mozeme nastavit hocijaku attributy pre tabulku system_logs
 *
 * @author 		Henrich Benkő <henrich.benko@backbone.sk>
 * @version 	1.0
 */

class Logger extends \Appendix\Libraries\Logger
{
	public static function instance()
	{
		if (!(isset(self::$instance) AND self::$instance instanceof self))
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	protected function log($level, $activity_id, $additional_data)
	{
		$log = new History();

		$log->activity_id 	= $activity_id;
		$log->level 		= $level;
		$log->ip_address 	= Input::$info['IP_ADDRESS'];
		$log->url 			= sprintf(
			'%s:%s',
			Router::$active['key'],
			parse_url(Router::uri_string(), PHP_URL_PATH)
		);

		$log->user_id 		= $this->user_id;
		$log->user_session 	= isset(Input::$info['USER_SESSION'])
			? Input::$info['USER_SESSION']
			: '';

		$log->additional_info = !empty($additional_data)
			? json_encode($additional_data, JSON_UNESCAPED_SLASHES)
			: NULL;

		$log->save();
	}
}