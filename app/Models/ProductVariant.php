<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class ProductVariant
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int product_id
 * @property string storage_no
 * @property string title
 * @property string text
 * @property string main_photo_path
 * @property string preview_path
 * @property float tax
 * @property float price
 * @property string code
 * @property int weight_in_grams
 * @property int in_store_quantity
 * @property int position
 * @property string ean_code
 * @property boolean is_sold_out
 * @property DateTime updated_at
 * @property DateTime created_at
 *
 * Relations:
 * @property Product product
 */
class ProductVariant extends Model
{
	public static $table_name 			= 'product_variants';

	public static $validate_is_present 	= [
		'product_id', 'title', 'text', 'tax', 'price', 'code', 'weight_in_grams', 'in_store_quantity', 'position'
	];

	static $belongs_to = [
		[
			'product',
			'foreign_key' 	=> 'product_id',
			'class_name' 	=> Product::class
		],
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$product_variant = [
			'id' 						=> $this->id,
			'product_id' 				=> $this->product_id,
			'storage_no' 				=> $this->storage_no,
			'title' 					=> $this->title,
			'text' 						=> $this->text,
			'main_photo_path' 			=> $this->main_photo_path,
			'preview_path' 				=> $this->preview_path,
			'tax' 						=> $this->tax,
			'price' 					=> $this->price,
			'code' 						=> $this->code,
			'weight_in_grams' 			=> $this->weight_in_grams,
			'in_store_quantity' 		=> $this->in_store_quantity,
			'position' 					=> $this->position,
			'ean_code' 					=> $this->ean_code,
			'is_sold_out' 				=> $this->is_sold_out
		];

		return $product_variant;
	}
}