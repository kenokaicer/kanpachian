<?php namespace Config;

define("MODELS","core/models/");
define('ROOT', str_replace('\\','/',dirname(__DIR__) . "/"));

$base=explode($_SERVER['DOCUMENT_ROOT'],ROOT);
define("BASE",$base[1]);