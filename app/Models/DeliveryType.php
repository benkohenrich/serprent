<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class DeliveryType
 * @package Models
 *
 * Properties:
 * @property int id
 * @property string name
 * @property string description
 * @property string email_description
 * @property string handle
 * @property float price
 */
class DeliveryType extends Model
{
	public static $table_name 			= 'delivery_types';

	public static $validate_is_present 	= [
		'name', 'description', 'email_description', 'handle', 'price'
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$delivery_type = [
			'id' 					=> $this->id,
			'name' 					=> $this->name,
			'description' 			=> $this->description,
			'email_description' 	=> $this->email_description,
			'handle' 				=> $this->handle,
			'price' 				=> $this->price,
		];

		return $delivery_type;
	}
}