<?php
	namespace Controllers;

	use Dao\BD\SeatsTypesDao as SeatsTypesDao;
	
	class MainController{

		public function __construct()
		{
		}
		
		function index()
		{
			require "Views/home.php";
		}
	}
?>
