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
];