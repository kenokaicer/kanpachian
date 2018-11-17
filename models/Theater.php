<?php
namespace Models;

use Models\SeatType as SeatType;

class Theater extends Attributes//Lugar_evento

{
    protected $idTheater;
    protected $theaterName;
    protected $location;
    protected $address;
    protected $maxCapacity;
    protected $image;
    private $seatTypes = array(); //to calculate the amount of each type.

    public function getTheaterName()
    {
        return $this->theaterName;
    }

    public function setTheaterName($theaterName)
    {
        $this->theaterName = $theaterName;

        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getMaxCapacity()
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity($maxCapacity)
    {
        $this->maxCapacity = $maxCapacity;

        return $this;
    }

    public function getSeatTypes()
    {
        return $this->seatTypes;
    }

    public function setSeatTypes($seatTypes)
    {
        $this->seatTypes = $seatTypes;

        return $this;
    }

    public function getIdTheater()
    {
        return $this->idTheater;
    }

    public function setIdTheater($idTheater)
    {
        $this->idTheater = $idTheater;

        return $this;
    }

    public function addSeatType(SeatType $seatType)
    {
        $this->seatTypes[] = $seatType;
    }

	public function getAddress()
	{
		return $this->address;
	}

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
}
