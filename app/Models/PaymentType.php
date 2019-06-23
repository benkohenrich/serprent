<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class PaymentType
 * @package Models
 *
 * Properties:
 * @property int id
 * @property string name
 * @property string handle
 * @property string service
 * @property boolean is_deleted
 */
class PaymentType extends Model
{
	public static $table_name 			= 'payment_types';

	public static $validate_is_present 	= [
		'name', 'handle'
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$payment_type = [
			'id' 				=> $this->id,
			'name' 				=> $this->name,
			'handle' 			=> $this->handle,
			'service' 			=> $this->service,
			'is_deleted' 		=> $this->is_deleted,
		];

		return $payment_type;
	}
}