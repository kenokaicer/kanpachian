<?php

namespace Models;

use Models\SeatsByEvent as SeatsByEvent;

class PurchaseLines extends Attributes//Lineas_Compra

{
    protected $idPurchaseLines;
    protected $price; //This is a inherited value from SteasByEvent
    private $seatsByEvent; //Class SeatsByEvent.

    public function getIdPurchaseLines()
    {
        return $this->idPurchaseLines;
    }

    public function setIdPurchaseLines($idPurchaseLines)
    {
        $this->idPurchaseLines = $idPurchaseLines;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getSeatsByEvent()
    {
        return $this->seatsByEvent;
    }

    public function setSeatsByEvent(SeatsByEvent $seatsByEvent)
    {
        $this->seatsByEvent = $seatsByEvent;

        return $this;
    }
}
