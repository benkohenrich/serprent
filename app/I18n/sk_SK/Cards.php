<?php


return [
	'overview' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Karty',
			'title' 			=> 'Zoznam kariet',
		],

		'table' 			=> [
			'code' 				=> 'Kód karty',
			'card_user' 		=> 'Používateľ',
			'is_active' 		=> 'Status',
			'controls' 			=> 'Možnosti',
		],

		'buttons' 			=> [
			'create' 			=> 'Pridať kartu'
		]
	],

	'create' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Nová karta',
			'title' 			=> 'Zoznam kariet',
			'active' 			=> 'Pridať novú kartu',
		],
	],

	'edit' 				=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Editovať kartu',
			'title' 			=> 'Zoznam kariet',
			'active' 			=> 'Editovať existujúcu kartu',
		],
	],

	'delete' 			=> [
		'confirm_message' 	=> ' zvolenú kartu',
		'success_message' 	=> 'Karta bola úspešne odstránená zo systému',
		'errors' 			=> [
			'not_existing_card' => 'Taká karta neexistuje v systéme',
			'client_mismatch' 	=> 'Nemáte právo vymazať kartu iného klienta',
		],
	],

	'form' 				=> [
		'headers' 			=> [
			'card_info' 		=> 'Informácie o karte',
		],

		'inputs' 			=> [
			'code' 				=> 'Kód karty',
			'user_id' 			=> 'Používateľ',
			'is_active' 		=> 'Je aktívy?',
		],

		'flash' 			=> [
			'success' 			=> 'Úspešné uloženie',
			'failure' 			=> 'Nepodarilo sa uložiť'
		]
	],

	'booleans' 			=> [
		'is_active' 		=> [
			'1' 				=> 'Aktívny',
			'0' 				=> 'Neaktívny',
		]
	]
];
