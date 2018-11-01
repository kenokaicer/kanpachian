<?php
	namespace Controllers;

	class AjaxController
	{
		public function index(){}

		public function perro()
		{
			print('?????????????????????');
			echo("<script>alert('Guau');</script>");
		}
			public function gato()
		{
			print('XXXXXXXXXXX');
			echo("<script>alert('Miau');</script>");
		}
	}
?>