<?php
	namespace Controladoras;
	
	use Vistas;
	
	class ControladoraPaginaPrincipal{
		
		function __construct()
		{
			
		}
		
		function index()
		{
			require(ROOT.'View/home.php');
		}
	}
?>
