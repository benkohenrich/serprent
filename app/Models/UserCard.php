<?php


namespace Models;

use ActiveRecord\DateTime;
use Appendix\Core\Model;
use Helpers\ModelHelper;

/**
 * Class UserCard
 * @package Models
 *
 * Properties:
 * @property int id
 * @property int user_id
 * @property int card_id
 * @property int client_id
 * @property DateTime created_at
 *
 * Relations:
 * @property Client client
 * @property User user
 * @property Card card
 */
class UserCard extends Model
{
	public static $table_name 			= 'user_cards';

	public static $validate_is_present 	= [
		'client_id', 'card_id', 'user_id'
	];

	static $belongs_to = [
		[
			'client',
			'foreign_key' 	=> 'client_id',
			'class_name' 	=> Client::class
		],
		[
			'card',
			'foreign_key' 	=> 'card_id',
			'class_name' 	=> Card::class
		],
		[
			'user',
			'foreign_key' 	=> 'user_id',
			'class_name' 	=> User::class
		],
	];

	/**
	 * @return array
	 */
	public function summary()
	{
		$user_card = [
			'id' 				=> $this->id,
			'client_id' 		=> $this->client_id,
			'card_id' 			=> $this->card_id,
			'user' 				=> ModelHelper::prepare($this->user),
		];

		return $user_card;
	}
}