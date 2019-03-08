<?php
/**
 * Created            31/07/16 15:28
 * @author            Jakub Dubec <jakub.dubec@gmail.com>
 */

namespace Helpers;

use Appendix\Core\Router;
use Appendix\Core\System;
use Appendix\Libraries\Email;
use Appendix\Libraries\View;

abstract class EmailHelper
{

	/**
	 * @param string $name
	 * @param mixed $errors
	 */
	public static function send_cron_error($name, $errors)
	{
		$data 					= [
			'list' 				=> $errors,
			'name' 				=> $name,
			'happened_at' 		=> new \DateTime()
		];

		$template 				= sprintf("cron_error_%s", $name);

		self::send_email(
			System::config("system.developer_email"),
			"Runister Developer",
			sprintf("Error message from CRON job %s", $name),
			$template,
			$data
		);
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @param string $subject
	 * @param string $template
	 * @param array $data
	 * @return bool
	 */
	public static function send_email($email, $name, $subject, $template, $data = [])
	{
		$new_email 				= new Email();
		$new_email->message()
			->setSubject($subject)
			->setFrom(System::config("system.site_email"), System::config("app.site_name"))
			->setTo($email, $name)
			->setCharset("UTF-8");

		$content 				= new View();
		$content->set_file(strtolower(sprintf("_emails/%s", $template)))->register($data);

		$new_email->message()->setBody($content->output(), 'text/html');

		try
		{
			return $new_email->send();
		}
		catch (\Exception $e)
		{
			return false;
		}
	}
}