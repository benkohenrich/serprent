<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class Order
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int delivery_type_id
 * @property int payment_type_id
 * @property int shipping_address_id
 * @property int billing_address_id
 * @property string status
 * @property string name
 * @property string surname
 * @property string email
 * @property string phone
 * @property float discount_price
 * @property float delivery_price
 * @property float product_price
 * @property float total_price
 * @property string notes
 * @property string variable_symbol
 * @property boolean is_thank_you_email_sent
 * @property boolean is_deleted
 * @property DateTime processed_at
 * @property DateTime sent_at
 * @property DateTime updated_at
 * @property DateTime created_at
 *
 * Relations:
 * @property DeliveryType delivery_type
 * @property PaymentType payment_type
 * @property CustomerAddress shipping_address
 * @property CustomerAddress billing_address
 */
class Order extends Model
{
	public static $table_name 			= 'orders';

	public static $validate_is_present 	= [
		'delivery_type_id', 'shipping_address_id', 'billing_address_id', 'status', ' name', 'surname', 'email', 'phone',
		'discount_price', 'delivery_price', 'product_price', 'total_price', 'variable_symbol'
	];

	static $belongs_to = [
		[
			'delivery_type',
			'foreign_key' 	=> 'delivery_type_id',
			'class_name' 	=> DeliveryType::class
		],
		[
			'payment_type',
			'foreign_key' 	=> 'payment_type_id',
			'class_name' 	=> PaymentType::class
		],
		[
			'shipping_address',
			'foreign_key' 	=> 'shipping_address_id',
			'class_name' 	=> CustomerAddress::class
		],
		[
			'billing_address',
			'foreign_key' 	=> 'billing_address_id',
			'class_name' 	=> CustomerAddress::class
		],
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$order = [
			'id' 						=> $this->id,
			'delivery_type_id' 			=> $this->delivery_type_id,
			'payment_type_id' 			=> $this->payment_type_id,
			'shipping_address_id' 		=> $this->shipping_address_id,
			'billing_address_id' 		=> $this->billing_address_id,
			'status' 					=> $this->status,
			'name' 						=> $this->name,
			'surname' 					=> $this->surname,
			'email' 					=> $this->email,
			'phone' 					=> $this->phone,
			'discount_price' 			=> $this->discount_price,
			'delivery_price' 			=> $this->delivery_price,
			'product_price' 			=> $this->product_price,
			'total_price' 				=> $this->total_price,
			'notes' 					=> $this->notes,
			'variable_symbol' 			=> $this->variable_symbol,
			'is_thank_you_email_sent' 	=> $this->is_thank_you_email_sent,
			'is_deleted' 				=> $this->is_deleted,
			'processed_at' 				=> $this->processed_at,
			'sent_at' 					=> $this->sent_at
		];

		return $order;
	}
}