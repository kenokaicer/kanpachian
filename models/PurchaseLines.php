<?php

namespace Models;

use Models\SeatsByEvent as SeatsByEvent;

class PurchaseLines//Lineas_Compra

{
    private $idPurchaseLines;
    private $seatsByEvent; //Class SeatsByEvent.
    //private $quantity; //Tentative var.
    private $price; //This is a inherited value from SteasByEvent

    public function getSeatsByEvent()
    {
        return $this->seatsByEvent;
    }

    public function setSeatsByEvent($seatsByEvent)
    {
        $this->seatsByEvent = $seatsByEvent;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getIdPurchaseLines()
    {
        return $this->idPurchaseLines;
    }

    public function setIdPurchaseLines($idPurchaseLines)
    {
        $this->idPurchaseLines = $idPurchaseLines;

        return $this;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }
}
