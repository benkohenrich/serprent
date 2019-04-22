<?php


return [
	'navigation' 		=> [
		'profile' 			=> [
			'my_profile' 		=> 'Môj profil',
			'quick_access' 		=> 'Rýchly prístup',
		],
		'partials' 		=> [
			'users' 		=> [
				'main' 			=> 'Používatelia',
				'create' 		=> 'Pridať nového',
				'overview' 		=> 'Zoznam'
			],
			'clients' 		=> [
				'main' 			=> 'Klienti',
				'create' 		=> 'Pridať nového',
				'overview' 		=> 'Zoznam'
			],
			'cards' 		=> [
				'main' 			=> 'Karty',
				'create' 		=> 'Pridať novú',
				'overview' 		=> 'Zoznam'
			],
		]
	],

	'plugins' 			=> [
		'select2' 			=> [
			'placeholder' 		=> 'Vyberte možnosť'
		]
	],

	'controls' 			=> [
		'submit' 			=> 'Uložiť formulár',
		'edit' 				=> 'Editovať',
		'view' 				=> 'Detail',
		'delete' 			=> 'Vymazať',
		'all' 				=> 'Všetky',
		'logout' 			=> 'Odhlásiť sa',
		'hide' 				=> 'Skryť platbu'
	],

	'roles' 			=> [
		'superadmin' 		=> 'Superadmin',
		'administrator' 	=> 'Administrátor',
		'user' 				=> 'Používateľ',
	],

	'permissions' 		=> [
		'auth' 				=> [
			'login' 			=> 'Používateľ sa môže prihlásiť do systému'
		],
		'users' 			=> [
			'management' 		=> 'Používateľ môže zmeniť ostatných používateľov'
		],
		'clients' 			=> [
			'management' 		=> 'Používateľ môže zmeniť klientov'
		],
		'cards' 			=> [
			'management' 		=> 'Používateľ môže zmeniť karty'
		]
	],

	'languages' 		=> [
		'slovak' 			=> 'Slovenský',
		'hungarian' 		=> 'Maďarský',
		'english' 			=> 'Anglický',
	]
];
