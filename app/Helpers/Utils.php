<?php

namespace Helpers;


use ActiveRecord\DateTime;
use Appendix\Core\I18n;
use Appendix\Core\Router;

class Utils
{
	/**
	 * @param DateTime $datetime
	 */
	public static function get_time_difference($datetime)
	{
		$now 				= new DateTime();

		I18n::instance()->set_language(Router::active_language());

		$intervals 			= [
			'P1D' 			=> 'deň',
			'PT1H' 			=> 'hodina',
			'PT1M' 			=> 'minúta',
		];

		foreach ($intervals as $interval => $text)
		{
			$interval 				= new \DateInterval($interval);
			$date_range 			= new \DatePeriod($datetime, $interval, $now);

			$result 				= 0;

			/** @var \DateTime $date */
			foreach ($date_range as $date)
			{
				$result++;
			}

			if ($result > 0)
			{
				return sprintf('%d %s', $result, $text);
			}
		}





	}
}