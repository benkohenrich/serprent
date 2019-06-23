<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class Product
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int product_category_id
 * @property string title
 * @property string handle
 * @property int position
 * @property boolean is_deleted
 * @property boolean is_hidden
 * @property DateTime updated_at
 * @property DateTime created_at
 *
 * Relations:
 * @property ProductCategory product_category
 */
class Product extends Model
{
	public static $table_name 			= 'products';

	public static $validate_is_present 	= [
		'product_category_id', 'title', 'handle', 'position'
	];

	static $belongs_to = [
		[
			'product_category',
			'foreign_key' 	=> 'product_category_id',
			'class_name' 	=> ProductCategory::class
		],
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$card = [
			'id' 						=> $this->id,
			'product_category_id' 		=> $this->product_category_id,
			'title' 					=> $this->title,
			'handle' 					=> $this->handle,
			'position' 					=> $this->position,
			'is_deleted' 				=> $this->is_deleted,
			'is_hidden' 				=> $this->is_hidden,
		];

		return $card;
	}
}