<?php
namespace Models;

use Models\SeatType as SeatType;

class Theater//Lugar_evento

{
    private $idTheater;
    private $name;
    private $location;
    private $image;
    private $maxCapacity;
    private $seatTypes = array(); //to calculate the amount of each type.

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

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

    public function getAll()
    {
        return get_object_vars($this);
    }
}
