<?php

namespace Helpers;

use Appendix\Libraries\Errors;

class Utils
{
	/**
	 * @param $model
	 * @param $attributes
	 * @return array
	 */
	public static function parse_input($model, $attributes)
	{
		$parsed_attributes 		= [];
		$is_new_record 			= $model->is_new_record();

		foreach ($model->attributes() as $key => $model_attribute)
		{
			$value 		= NULL;

			if (isset($attributes[$key]))
			{
				$value 		= $attributes[$key];
			}
			else
			{
				if (!$is_new_record)
				{
					$value 		= $model->{$key};
				}
			}

			$parsed_attributes[$key] 		= $value;
		}

		return $parsed_attributes;
	}

	/**
	 * @param Errors $errors
	 * @return array
	 */
	public static function reformat_errors(Errors $errors)
	{
		if ($errors->is_empty())
			return[];

		$reformatted_errors 		= [];
		$already_inserted_msg 		= [];

		foreach ($errors->raw() as $field => $errors)
		{
			foreach ($errors as $error)
			{
				if (!in_array($error['message'], $already_inserted_msg))
				{
					$already_inserted_msg[] 	= $error['message'];

					$reformatted_errors[] 		= [
						'field' 	=> $field,
						'reason' 	=> $error['code'],
						'message' 	=> $error['message']
					];
				}
			}
		}

		return $reformatted_errors;
	}
}