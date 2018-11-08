<?php namespace Config;

// Constat for Root
define('ROOT', str_replace('\\','/',dirname(__DIR__) . "/"));

// Composer Autoload
require_once ROOT."Vendor/Autoload.php";

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
define("IMG_PATH2",VIEWS_PATH . "img/");