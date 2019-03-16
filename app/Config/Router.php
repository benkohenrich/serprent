<?php
/**
 * Konfiguacny subor pre smerovaci komponent jadra aplikacneho ramca.
 *
 * Vseobecne nastavenia:
 *
 * allow_null_slug 			Je mozne mat a vyuzivat v adrese nulove elementy
 * 							(napr. /test/0/). Standardna hodnota je FALSE.
 * force_trailing_slash 	Je povinne, aby kazda adresa (nie hash) koncili
 * 							lomkou. Standardna hodnota je TRUE.
 * patterns 				Pole so zoznamom adries zapisanych cez regularne
 * 							vyrazy delene podla subdomeny aplikacie. Prvou
 * 							cestou v zozname je _default, ktora oznacuje
 * 							startovacie miesto v pripade, ze PATH_INFO je
 * 							prazdne. Druhym a tretim prvkom kazdeho elementu je
 * 							controller (priamo ako nazov triedy v priecinku
 * 							"Controllers" alebo plne kvalifikovane meno) a jeho  *							metoda, ktore sa o danu poziadavku staraju.
 * 							Pri vyuziti viacjazycnosti aplikacie je mozne
 * 							zaznacit v regularnom vyraze cast adresy ako
 * 							<_:subor.element>, kde sa podla aktualne aktivneho
 * 							jazyka nahradi konkretnou jazykovou mutaciou
 * 							ziskanou z jazykoveho suboru (napr.
 * 							"/app/I18n/sk-SK/uri.php" pri <_:uri.nieco>)
 * is_multilingual			Je aplikacia viacjazycna; ak ano (TRUE), tak v
 * 							adrese je automaticky vyzadovana dvojznakova
 * 							skratka dotupneho jazyka. Predvolena hodnota
 * 							je FALSE.
 * 							Alternativnym zapisom je vyuzit pole, kde prvym
 * 							klucom je "force" boolean hodnota v zmysle
 * 							predosleho a druhym kluc "except" obsahujuci
 * 							pole vynimiek z povinneho spravania (berie sa v
 * 							uvahu iba v pripade, ze je hodnotou prvej casti
 * 							TRUE), napr. nasledujuci zapis znamena, ze vsetky
 * 							adresy zacinajuce sa na "data" nebude nutne musiet
 * 							mat pred sebou dvojpismenne oznacenie jazyka (a
 * 							ostatne ano).
 * 								$config['is_multilingual'] = array(
 * 									'force' => TRUE, 'except' => array('data')
 * 								)
 *
 * 							Poznamka: Je dolezite spomenut, ze "data" nesmie
 * 							byt skutocny adresar v suborovom systeme operacneho
 * 							systemu.
 * valid_languages 			Zoznam dostupnych jazykovych verzii ako pole
 * 							s klucom prvku ako dvojpismenna skratka jazyka,
 * 							ktora sa bude zobrazovat ako prva cast URL adresy.
 * 							Jej hodnotu vytvara jazykovy kod s verziou (napr.
 * 							"sk-SK" alebo "en-US").
 * default_language	  		Skratka standardne predvoleneho jazyka.
 * error404 				Definuje spravanie pri neexistujucej alebo chybovej
 * 							stranke v produkcnom mode aplikacie. Prvym prvkom
 * 							zoznamu je alebo moznost "run", ktora spusta
 * 							konkretny controller a akciu zadefinovanu polom,
 * 							alebo "redirect", ktora priamo presmerovava
 * 							na adresu zadefinovanu polom (respektuje nastavenia
 * 							viacjazycnosti).
 */

$config = [
	'allow_null_slug' 		=> FALSE,
	'force_trailing_slash' 	=> FALSE,
	'patterns'				=> [
		'cms' => [

			// Authorization
			[
				'^/login/$',
				'Admin\Auth', 'login'
			],
			[
				'^/logout/$',
				'Admin\Auth', 'logout'
			],

			// Users
			[
				'^/users/$',
				'Admin\Users', 'overview'
			],
		]
	],

	'is_multilingual' 		=> TRUE,

	'valid_languages'		=> [
		'sk' 	=> 'sk_SK',
		'hu' 	=> 'hu_HU',
		'en' 	=> 'en_US',
	],
	'default_language'		=> 'sk',

	'error404' 				=> [
		'run', [ 'admin', 'sorry' ]
	]
];
