<?php
	namespace Controllers;

	use Dao\BD\SeatsTypesDao as SeatsTypesDao;
	
	class MainController{

		public function __construct()
		{
			//if(!isset($_SESSION["seatTypes"]))
			//	$_SESSION["seatTypes"] = SeatsTypesDao::getInstance()->retrieveAll();
		}
		
		function index()
		{
			require "Views/home.php";
		}
	}
?>
