<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class ProductCategory
 * @package Models
 *
 * Properties:
 * @property int id
 * @property string title
 * @property string handle
 * @property boolean is_deleted
 * @property DateTime updated_at
 * @property DateTime created_at
 */
class ProductCategory extends Model
{
	public static $table_name 			= 'product_categories';

	public static $validate_is_present 	= [
		'title', 'handle'
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$product_category = [
			'id' 				=> $this->id,
			'title' 			=> $this->title,
			'handle' 			=> $this->handle,
			'is_deleted' 		=> $this->is_deleted,
		];

		return $product_category;
	}
}