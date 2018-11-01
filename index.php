<?php

	/**
	 * Show PHP errors
	 */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	require_once "config/Config.php";
	require_once "config/Autoload.php";
	require_once "views/css/scssphp/scss.inc.php";

	/**
	 * Alias
	 */
	use Config\Autoload as Autoload;
	use Config\Router 	as Router;
	use Config\Request 	as Request;
	use Cross\Session as Session;
	use Daos\SingletonDao as SingletonDao;
	use Leafo\ScssPhp\Compiler;

	function compileScss()
	{
		$scss = new Compiler();
		$scss->setImportPaths("views/css/");
		echo '<style>';
		echo $scss->compile('@import "cart.scss"'); //To add more .scss just copy n paste this line.
		echo '</style>';
	}
	
	Autoload::start();
	Session::start();

	require "Views/Header.php";
	require "Views/navbar.php";

	Router::Route(new Request());

	require "Views/Footer.php";

	function test()
	{
	 require "Views/admin.php";
	}


?>
