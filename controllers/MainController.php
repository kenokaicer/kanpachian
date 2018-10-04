<?php
	namespace Controllers;
	
	use Vistas; //necesary?
	
	class MainController{
		
		function __construct()
		{
			
		}
		
		function index()
		{
			require(ROOT.'views/home.php');
		}
	}
?>
