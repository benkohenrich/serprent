<?php
/**
 * Konfiguacny subor pre systemove prostredie.
 *
 * Kazdy ovladac obcashuje svoju skupinu nastaveni, ktorej nazov je zhodny
 * s nazvom ovladaca.
 *
 * Vseobecne nastavenia:
 *
 * project_name 		Nazov projektu.
 * uri_abslute 			Pole s indexom oznacujucim subdomenu a absolutnou
 * 						cestou k castiam stranky.
 * certificate 			V pripade pouzitia SSL cesta k certifikatu. Vhodne najme
 * 						pri samo-podpisanych certifikatoch.
 * cookie_domain 		Pouzitelne najma v pripade viacerych subdomen, kde je
 * 						potrebne zdielat cookies; explicitne definuje predvolenu
 * 						domenu platnosti.
 * site_email 			Emailova adresa na spravcu stranky.
 * developer_email 		Emailova adresa na vyvojara stranky, ktory moze byt
 * 						notifikovany o roznych udalstiach ako neuspesnych
 * 						CRON akciach a pod.
 * time_zone			Casova zona, podla ktorej sa prepocitavaju
 * 						casy na stranke.
 * validator 			Cesta k modelovej validacnej kniznici. Predvolenou
 * 						hodnotou je "\Appendix\Libraries\Validation".
 * autostart_sessions 	Boolovska hodnota oznacujuca, ci maju byt automaticky
 * 						spustane relacie PHP pri kazdej poziadavke.
 * autoload_libraries 	Pole kniznic, ktore sa automaticky nacitavaju
 * 						pri spustani stranky. Klucom pola je identifikator,
 * 						ktory sa pri spustenom radici stane instancnou
 * 						premennou. Hodnotou mozu byt iba plne kvalifikovane
 * 						nazvy kniznic alebo staticke volania inicializacnych
 * 						funkcii cez navrhovy vzor Singleton.
 * prefer_x_real_ip 	Pri pouziti vstupnovystupnej kniznice je automaticky
 * 						naplnane pole s informaciami o poziadavke sucastou coho
 * 						je aj IP adresa klienta. V pripade prechodu cez
 * 						front-end server je praxou vyuzivat hlavicku X-REAL-IP
 * 						namiesto REMOTE_ADDR. Predvolena hodnota je FALSE.
 *
 * Ostatne nastavenia su specificke pre kazdy projekt.
 */

$config = [
	'project_name' 			=> 'serprent-cms',

	'uri_absolute'			=> [
		'cms' 				=> '',
	],

	'certificate' 			=> FALSE,

	'cookie_domain' 		=> '',

	'site_email'			=> 'no-reply@seprent.com',
	'no_reply_email'		=> 'no-reply@seprent.com',
	'developer_email' 		=> [
		'henrich.benko@backbone.sk', 'zoltan.czinege@backbone.sk'
	],

	'time_zone'				=> 'UTC',

	'validator' 			=> '\Appendix\Libraries\Validation',

	'autostart_sessions' 	=> TRUE,

	'autoload_libraries'	=> [
		'db'		=> '\Appendix\Libraries\Db::instance',
		'input'		=> '\Appendix\Libraries\Input::instance',
		'event' 	=> '\Appendix\Libraries\Event::instance',
		'logger' 	=> '\Helpers\Logger::instance',
		'events' 	=> '\Appendix\Models\Activity::setup_events',
		'view'		=> '\Appendix\Libraries\View::instance',
	],

	'prefer_x_real_ip' 		=> TRUE
];

$config[ TEST ] = [
	'cookie_domain' 		=> '',

	'time_zone'				=> 'Europe/Bratislava',

	'site_email'			=> 'henrich.benko@backbone.sk',
	'no_reply_email'		=> 'henrich.benko@backbone.sk',
	'developer_email' 		=> [ 'henrich.benko@backbone.sk', 'zoltan.czinege@backbone.sk' ],

	'uri_absolute'			=> [
		'cms' 				=> ''
	]
];

$config[ DEVELOPMENT ] = [
	'cookie_domain' 		=> '.serprent.test',

	'time_zone'				=> 'Europe/Bratislava',

	'uri_absolute'			=> [
		'cms' 				=> 'http://cms.serprent.test',
	],
];


