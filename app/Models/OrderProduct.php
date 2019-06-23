<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class OrderProduct
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int order_id
 * @property int product_id
 * @property int product_variant_id
 * @property int quantity
 * @property float price
 * @property float tax
 * @property DateTime created_at
 *
 * Relations:
 * @property Order order
 * @property Product product
 * @property ProductVariant product_variant
 */
class OrderProduct extends Model
{
	public static $table_name 			= 'order_products';

	public static $validate_is_present 	= [
		'order_id', 'product_id', 'quantity', 'price', 'tax'
	];

	static $belongs_to = [
		[
			'order',
			'foreign_key' 	=> 'order_id',
			'class_name' 	=> Order::class
		],
		[
			'product',
			'foreign_key' 	=> 'product_id',
			'class_name' 	=> Product::class
		],
		[
			'product_variant',
			'foreign_key' 	=> 'product_variant_id',
			'class_name' 	=> ProductVariant::class
		],
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$order_product = [
			'id' 						=> $this->id,
			'order_id' 					=> $this->order_id,
			'product_id' 				=> $this->product_id,
			'product_variant_id' 		=> $this->product_variant_id,
			'quantity' 					=> $this->quantity,
			'price' 					=> $this->price,
			'tax' 						=> $this->tax
		];

		return $order_product;
	}
}