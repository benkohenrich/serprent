<?php
/**
 * Konfiguracny subor pre nastavenia kniznice urcenej na manipulaciu s obrazovymi
 * datami a fotografiami. Zakladnou ulohou je vytvaranie zmensenin unifikovanym
 * sposobom.
 *
 * Skupinove nastavenia:
 *  allowed_types 	- Zoznam povolenych typov obrazkov, z ktorymi sa da manipulovat
 * 	max_size 		- Hodnota maximálnej podporovanej velkosti uploadovaneho suboru
 * 	quality 		- Pole kvality s akou sa maju vytvorit obtazky (odporucam
 * 					  pozriet dokumentaciu k funkciam imagejpeg a pod., pretoze pri
 * 					  JPEG suboroch ide ozaj o kvalitu, kdezto pri PNG
 * 					  o mnozstvo kompresie)
 */
$config = [
	'default_library' 	=> 'gd',

	'allowed_types' 	=> [ 'jpg', 'jpeg', 'pjpeg', 'png', 'gif' ],

	'max_size' 			=> 2 * 1024 * 1024,

	'quality' 			=> [
		'jpeg' 	=> 80,
		'png' 	=> 4
	],

	'paths' 			=> []
];
