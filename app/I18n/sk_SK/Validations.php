<?php


return [
	'clients' 			=> [
		'name' 				=> [
			'unique' 			=> 'Názov klienta už existuje',
			'present' 			=> 'Názov klienta je povinný',
		],
		'type' 				=> [
			'present' 			=> 'Typ je povinný',
		],
		'contact_name' 		=> [
			'present' 			=> 'Kontaktná osoba je povinná',
		],
	],

	'system_users' 		=> [
		'email' 			=> [
			'present' 			=> 'E-mail je povinný',
			'email' 			=> 'Nesprávny e-mail formát',
		],
		'username' 			=> [
			'present' 			=> 'Prihlaosvacie meno je povinné',
		],
		'password' 			=> [
			'present' 			=> 'Heslo je povinné',
		],
		'name' 				=> [
			'present' 			=> 'Meno je povinné',
		],
		'surname' 			=> [
			'present' 			=> 'Priezvisko je povinné',
		],
		'role_id' 			=> [
			'present' 			=> 'Rola je povinná',
		],
		'language_id' 		=> [
			'present' 			=> 'Jazyk je povinný',
		],
		'client_id' 		=> [
			'present' 			=> 'Klient je povinný',
		],
	],

	'cards' 			=> [
		'client_id' 				=> [
			'present' 			=> 'Klient je povinný',
		],
		'code' 			=> [
			'present' 			=> 'Kód je povinná',
			'unique' 			=> 'Kód už existuje',
		],
	],
];
