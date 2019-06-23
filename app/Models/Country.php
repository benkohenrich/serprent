<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class Country
 * @package Models
 *
 * Properties:
 * @property int id
 * @property string code
 * @property string name
 */
class Country extends Model
{
	public static $table_name 			= 'countries';

	public static $validate_is_present 	= [
		'code', 'name'
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$country = [
			'id' 				=> $this->id,
			'code' 				=> $this->code,
			'name' 				=> $this->name
		];

		return $country;
	}
}