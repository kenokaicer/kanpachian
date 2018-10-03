<?php
	namespace controllers;
	
	use Vistas;
	
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
