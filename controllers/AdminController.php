<?php
	namespace Controllers;
	/*use Dao\BD\SeatsByEventDao as SeatsByEventDao;
	use Models\SeatsByEvent as SeatsByEvent;
	use Models\SeatType as SeatType;*/
	
	class AdminController{
		
		function index()
		{	
			/*$seatType = new seatType();
			$seatType->setIdSeatType(1)->setName('seat')->setDescription('desc');

			$seatsByEvent = new SeatsByEvent();
			$seatsByEvent->setQuantity(1)->setPrice(10)->setRemnants(100)->setSeatType($seatType);

			SeatsByEventDao::getInstance()->add($seatsByEvent);*/
			require "Views/admin.php";
		}
	}
?>
