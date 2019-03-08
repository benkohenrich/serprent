<?php
/**
 * Konfiguacny subor pre jednotlive ovladace autorizacneho modulu. Spustanie
 * zvolenych modulov je mozne priamo z klientskeho kodu.
 *
 * Kazdy ovladac obcashuje svoju skupinu nastaveni, ktorej nazov je zhodny
 * s nazvom ovladaca.
 *
 * Skupinove nastavenia:
 *
 * lifetime 			Casova platnost cookie pri zapamatani prihlasenia.
 * default_alogorithm 	Predvoleny algoritmus pouzity na hashovanie hesiel.
 * group_by 			Skupinove kriterium, podla ktoreho su kategorizovane
 * 						opravnenia v databaze opravneni v implementacii.
 */

$config = [
	'permissionbased' 	=> [
		'lifetime' 					=> 5 * 12 * 31 * 24 * 60 * 60,
		'default_algorithm' 		=> 'SHA256',
		'group_by' 					=> [],
		'minimal_password_length' 	=> 8,
	]
];
