<?php

namespace Models;

use Models\SeatsByEvent as SeatsByEvent;

class PurchaseLines //Lineas_Compra
{
    private $idPurchaseLines;
    private $seatsByEvent;//Class SeatsByEvent.
    private $quantity; //Tentative var.
    private $price; //This is a calculated value.

	public function getSeatsByEvent()
	{
		return $this->seatsByEvent;
	}

    public function setSeatsByEvent($seatsByEvent)
    {
        $this->seatsByEvent = $seatsByEvent;

        return $this;
    }

	public function getQuantity()
	{
		return $this->quantity;
	}

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice()
	{
		return $this->price;
	}
}