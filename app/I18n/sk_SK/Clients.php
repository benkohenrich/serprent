<?php


return [
	'overview' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Klienti',
			'title' 			=> 'Zoznam klientov',
		],

		'table' 			=> [
			'name' 				=> 'Názov klienta',
			'type' 				=> 'Typ',
			'address' 			=> 'Adresa',
			'contact_name' 		=> 'Kontaktná osoba',
			'is_active' 		=> 'Status',
			'controls' 			=> 'Možnosti',
		],

		'buttons' 			=> [
			'create' 			=> 'Pridať klienta'
		]
	],

	'create' 			=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Nový klient',
			'title' 			=> 'Zoznam klientov',
			'active' 			=> 'Pridať nového klienta',
		],
	],

	'edit' 				=> [
		'breadcrumbs' 		=> [
			'header' 			=> 'Editovať klienta',
			'title' 			=> 'Zoznam klientov',
			'active' 			=> 'Editovať existujúceho klienta',
		],
	],

	'form' 				=> [
		'headers' 			=> [
			'client_info' 		=> 'Informácie o klienta',
			'contact_info' 		=> 'Kontaktná osoba',
			'superadmin_info' 	=> 'Superadmin sekcia',
		],

		'inputs' 			=> [
			'name' 				=> 'Názov klienta',
			'type' 				=> 'Typ',
			'city' 				=> 'Mesto',
			'street' 			=> 'Ulica',
			'zip' 				=> 'PSČ',
			'longitude' 		=> 'Zemepisná dĺžka',
			'latitude' 			=> 'Zemepisná šírka',
			'contact_name' 		=> 'Meno a priezvisko',
			'contact_email' 	=> 'E-mail',
			'contact_phone' 	=> 'Telefónne číslo',
			'is_active' 		=> 'Je aktívy?',
		],

		'flash' 			=> [
			'success' 			=> 'Úspešné uloženie',
			'failure' 			=> 'Nepodarilo sa uložiť'
		]
	],

	'enums' 			=> [
		'type' 				=> [
			'gym' 				=> 'Posilňovňa'
		]
	],

	'booleans' 			=> [
		'is_active' 		=> [
			'1' 				=> 'Aktívny',
			'0' 				=> 'Neaktívny',
		]
	],

	'delete' 			=> [
		'confirm_message' 	=> ' zvoleného klienta',
		'success_message' 	=> 'Klient bol úspešne odstránený zo systému',
		'errors' 			=> [
			'not_existing_client' 	=> 'Takýto klient neexistuje v systéme',
		],
	],
];
