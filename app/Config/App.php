<?php
/**
 * Konfiguacny subor s nastaveniami specifickymi pre projekt.
 *
 * Vseobecne nastavenia:
 *
 * validate_email_strictly 	Ma sa email overovat cez platnost DNS zaznamu
 * 							domeny? Toto nastavenie ma vplyv iba ak je
 * 							aplikacia v inom stave ako development.
 */

$config = [
	'site_name' 								=> 'Serprent',
	'minimal_password_length' 					=> 8,
	'password_pattern' 							=> '((?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{6,20})',

	'system_roles' 								=> [
		'superadmin' 								=> 1,
		'administrator' 							=> 2,
		'user' 										=> 3
	],
];