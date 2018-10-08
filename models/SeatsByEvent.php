<?php

namespace Models;

use Models\SeatType as SeatType;

/**
 * Type of seat of the event, prices and availability.
 */
class SeatsByEvent//Plaza_Evento

{
    private $idSeatsByEvent;
    private $seatType; // This is an enum.
    private $quantity;
    private $price;
    private $remnants; // This is a self calulated value.

    public function getIdSeatsByEvent()
    {
        return $this->idSeatsByEvent;
    }

    public function setIdSeatsByEvent($idSeatsByEvent)
    {
        $this->idSeatsByEvent = $idSeatsByEvent;

        return $this;
    }

    public function getSeatType()
    {
        return $this->seatType;
    }

    public function setSeatType($seatType)
    {
        $this->seatType = $seatType;

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

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getRemnants()
    {
        return $this->remnants;
    }

    public function setRemnants($remnants)
    {
        $this->remnants = $remnants;

        return $this;
    }
}
