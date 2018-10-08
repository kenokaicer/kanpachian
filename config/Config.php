<?php namespace Config;
//define('ROOT', dirname(dirname(__FILE__)) . DS); //str_replace("/", "\\", $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI']));
//define('__ROOT__', dirname(dirname(__FILE__)));

// Composer Autoload
require_once "Vendor/Autoload.php";

// Constat for Root
define('ROOT', str_replace('\\','/',dirname(__DIR__) . "/"));

// Constat for Request
$base=explode($_SERVER['DOCUMENT_ROOT'],ROOT);
define("BASE",$base[1]);

// Constats for Json
define("JSONFOLDER", "JsonFiles/");

// Constats for Base de datos
define("DB_NAME", "neonlab1_gotoevent");
define("DB_USER", "neonlab1_termo");
define("DB_PASS", "lluviadehamburguesas");
define("DB_HOST", "www.neonlab.com.ar");

// Constats for front
define('THEME_NAME', 'tema1');
define("URL_THEME", "/TrafficMDQ/Views");
define("URL_CSS", URL_THEME . "css");
define("URL_JS", URL_THEME . "js");

// Constats for Server
define('HOST_ROOT', __DIR__ . '/../');
define('HOST_URL_THEME', HOST_ROOT . 'Views/'. THEME_NAME . '/');
define("MODELS","core/models/");


/*echo '<p>Constante DS:' . DS . '</p>';
echo '<p>Constante ROOT:' . ROOT . '</p>';
echo '<p>Constante DB_NAME:' . DB_NAME . '</p>';
echo '<p>Constante DB_USER:' . DB_USER . '</p>';
echo '<p>Constante DB_PASS:' . DB_PASS . '</p>';
echo '<p>Constante DB_HOST:' . DB_HOST . '</p>';
echo '<p>Constante URL_THEME:' . URL_THEME . '</p>';
echo '<p>Constante URL_CSS:' . URL_CSS . '</p>';
echo '<p>Constante URL_JS:' . URL_JS . '</p>';
echo '<p>Constante THEME_NAME:' . THEME_NAME . '</p>';

echo '<pre>';
print_r($_SERVER);
echo '</pre>';*/