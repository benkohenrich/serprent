<?php
/**
 * Konfiguacny subor pre jednotlive ovladace databazoveho modulu. Spustanie
 * zvolenych modulov je mozne priamo z klientskeho kodu.
 *
 * Kazdy ovladac obcashuje svoju skupinu nastaveni, ktorej nazov je zhodny
 * s nazvom ovladaca.
 *
 * Vseobecne nastavenia:
 *
 * driver 		Standardne predvoleny databazovy ovladac.
 * benchmark 	Je povolene zaznamenavanie behu kazdeho dotazu (ma iba
 * 				optimalizacny zmysel, inak jemne spomaluje).
 *
 * Skupinove nastavenia:
 *
 * orm 		Ma sa vyuzivat ORM modelovanie cez ActiveRecord.
 * connection 	Skupina obsahujuca zakladne nastavenia pre pripojenie
 * 				na zvoleny databazovy sever.
 */
$config = [
	'driver' 		=> 'mysql',
	'benchmark' 	=> FALSE,

	'mysql' 		=> [
		'orm' 			=> TRUE,
		'connection' 	=> [
			'hostname'		=> 'localhost',
			'username'		=> '',
			'password'		=> '',
			'database'		=> '',
			'port'			=> 3306,
			'sock'			=> '',
			'charset' 		=> 'utf8'
		]
	],

	'health_check' 	=> []
];

$config[ TEST ] = [
	'mysql' 		=> [
		'orm' 			=> TRUE,
		'connection' 	=> [
			'hostname'		=> 'localhost',
			'username'		=> '',
			'password'		=> '',
			'database'		=> '',
			'port'			=> 3306,
			'sock' 			=> '',
			'charset' 		=> 'utf8'
		]
	]
];

$config[ DEVELOPMENT ] = [
	'benchmark' 	=> TRUE,

	'mysql' 		=> [
		'orm' 			=> TRUE,
		'connection' 	=> [
			'hostname'		=> '127.0.0.1',
			'username'		=> 'root',
			'password'		=> '',
			'database'		=> 'seprent_cms',
			'port'			=> 3306,
			'sock'			=> '',
			'charset' 		=> 'utf8'
		]
	]
];
