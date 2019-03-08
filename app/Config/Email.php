<?php
/**
 * Konfiguacny subor pre jednotlive ovladace kniznice urcenej
 * na spravu odosielania emailov. Existuju tri zakladne adaptery
 * v ramci vyuzivaneho pluginu (SwiftMailer):
 * <ul>
 *  <li>native - nativny adapter vyuzivajuci priamo PHP</li>
 *  <li>sendmail - adapter vyuzivajuci aplikaciu sendmail v OS (UNIX)</li>
 *  <li>smtp - adapter vyuziva pripojenie na SMTP</li>
 * </ul>
 *
 * Preferovanym riesenim je pouzitie SMTP adaptera pre jeho moznosti
 * pri kontrolovani dorucenia emailovych sprav a pod.
 *
 * Vseobecne nastavenia:
 *
 * driver 		Standardne predvoleny adapter.
 *
 * Ostatne nastavenia su specificke pre jednotlive adaptery a mali by
 * byt samovysvetlujuce.
 */
$config = [
	'driver' 	=> 'smtp',

	'smtp' 		=> [
		'hostname' 		=> 'localhost',
		'port' 			=> 25,
		'encryption' 	=> FALSE,
		'timeout' 		=> 10
	]
];
