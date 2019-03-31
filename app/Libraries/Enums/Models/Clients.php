<?php

namespace Libraries\Enums\Models;

use Appendix\Core\I18n;

class Clients
{
	public function type()
	{
		return [
			[
				'id' 			=> 'gym',
				'name' 			=> I18n::load('clients.enums.type.gym')
			]
		];
	}
}