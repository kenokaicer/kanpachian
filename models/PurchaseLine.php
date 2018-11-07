<?php

namespace Models;

use Models\SeatsByEvent as SeatsByEvent;

class PurchaseLine extends Attributes//Lineas_Compra

{
    protected $idPurchaseLine;
    protected $price; //This is a inherited value from SteasByEvent
    private $seatsByEvent; //Class SeatsByEvent.

    public function getIdPurchaseLine()
    {
        return $this->idPurchaseLine;
    }

    public function setIdPurchaseLine($idPurchaseLine)
    {
        $this->idPurchaseLine = $idPurchaseLine;

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
