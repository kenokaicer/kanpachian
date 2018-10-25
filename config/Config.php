<?php namespace Config;

// Composer Autoload
require_once "Vendor/Autoload.php";

// Constat for Root
define('ROOT', str_replace('\\','/',dirname(__DIR__) . "/"));

// Constats for Json
define("JSONFOLDER", "JsonFiles/");

// Constats for Data Base
define("DB_NAME", "neonlab1_gotoevent");
define("DB_USER", "neonlab1_termo");
define("DB_PASS", "lluviadehamburguesas");
define("DB_HOST", "www.neonlab.com.ar");

// Constats for front
$base=explode($_SERVER['DOCUMENT_ROOT'],ROOT);
define("FRONT_ROOT",$base[1]);
define("VIEWS_PATH", "Views/");
define("CSS_PATH", FRONT_ROOT.VIEWS_PATH . "css/");
define("JS_PATH", FRONT_ROOT.VIEWS_PATH . "js/");
define("IMG_PATH",FRONT_ROOT.VIEWS_PATH . "img/");