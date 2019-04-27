<?php


return [
	'overview' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Karty',
			'title' 			=> 'Zoznam kariet',
		],

		'table' 			=> [
			'code' 				=> 'Kód karty',
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

	'form' 				=> [
		'headers' 			=> [
			'card_info' 		=> 'Informácie o karte',
		],

		'inputs' 			=> [
			'code' 				=> 'Kód karty',
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
