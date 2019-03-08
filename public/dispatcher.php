<?php
require_once(__DIR__.'/../vendor/autoload.php');

define('APP_PATH', __DIR__.'/../app/');
define('PUBLIC_PATH', APP_PATH.'../public/');

// Spustenie systemu a vytvorenie prostredia pre beh aplikacie
\Appendix\Core\System::initialize();
