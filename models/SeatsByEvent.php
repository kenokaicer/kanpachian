<?php

namespace Models;

use Models\SeatType as SeatType;
use Models\EventByDate as EventByDate;

/**
 * Type of seat of the event, prices and availability.
 */
class SeatsByEvent extends Attributes//Plaza_Evento

{
    protected $idSeatsByEvent;
    protected $quantity;
    protected $price;
    protected $remnants; // This is a self calulated value.
    private $seatType; // Class SeatType.
    private $eventByDate; // Class EventByDate

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

    public function setSeatType(SeatType $seatType)
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

    public function getEventByDate()
	{
		return $this->eventByDate;
	}

    public function setEventByDate(EventByDate $eventByDate)
    {
        $this->eventByDate = $eventByDate;

        return $this;
    }
}
