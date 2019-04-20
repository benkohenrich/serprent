<?php


return [
	'overview' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Používatelia',
			'title' 			=> 'Zoznam používatelov',
		],

		'table' 			=> [
			'full_name' 		=> 'Meno a priezvisko',
			'email' 			=> 'E-mail',
			'role_id' 			=> 'Rola',
			'is_active' 		=> 'Status',
			'controls' 			=> 'Možnosti',
		],

		'buttons' 			=> [
			'create' 			=> 'Pridať používatela'
		]
	],

	'create' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Nový používateľ',
			'title' 			=> 'Zoznam používatelov',
			'active' 			=> 'Pridať nového používatela',
		],
	],

	'form' 				=> [
		'headers' 			=> [
			'user_info' 		=> 'Informácie o používatela',
			'role_info' 		=> 'Rola nastavenia',
		],

		'inputs' 			=> [
			'name' 				=> 'Meno',
			'surname' 			=> 'Priezvisko',
			'password' 			=> 'Heslo',
			'password_again' 	=> 'Potvrdiť heslo',
			'email' 			=> 'E-mail',
			'language_id' 		=> 'Jazyk',
			'role_id' 			=> 'Rola',
			'is_active' 		=> 'Je aktívny?',
			'permissions' 		=> 'Práva',
		],

		'flash' 			=> [
			'success' 			=> 'Úspešné uloženie',
			'failure' 			=> 'Nepodarilo sa uložiť'
		]
	],

	'enums' 			=> [
		'roles' 			=> [
			'1' 				=> 'Superadmin',
			'2' 				=> 'Administrátor',
			'3' 				=> 'Používateľ',
		]
	],

	'booleans' 			=> [
		'is_active' 		=> [
			'1' 				=> 'Aktívny',
			'0' 				=> 'Neaktívny',
		]
	]
];
