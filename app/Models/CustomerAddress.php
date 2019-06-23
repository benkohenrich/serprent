<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Appendix\Exceptions\PageNotFound;
use Helpers\ModelHelper;

/**
 * Class CustomerAddress
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int country_id
 * @property string name
 * @property string surname
 * @property string street
 * @property string city
 * @property string zip_code
 * @property string business_name
 * @property string business_brn
 * @property string business_tax_id
 * @property string business_vat_id
 * @property string entity
 * @property DateTime updated_at
 * @property DateTime created_at
 *
 * Relations:
 * @property Country country
 */
class CustomerAddress extends Model
{
	public static $table_name 			= 'customer_addresses';

	public static $validate_is_present 	= [
		'country_id', 'name', 'street', 'city', 'zip'
	];

	static $belongs_to = [
		[
			'country',
			'foreign_key' 	=> 'country_id',
			'class_name' 	=> Country::class
		],
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$customer_address = [
			'id' 					=> $this->id,
			'country_id' 			=> $this->country_id,
			'name' 					=> $this->name,
			'surname' 				=> $this->surname,
			'street' 				=> $this->street,
			'city' 					=> $this->city,
			'zip_code' 				=> $this->zip_code,
			'business_name' 		=> $this->business_name,
			'business_brn' 			=> $this->business_brn,
			'business_tax_id' 		=> $this->business_tax_id,
			'business_vat_id' 		=> $this->business_vat_id,
			'entity' 				=> $this->entity
		];

		return $customer_address;
	}
}