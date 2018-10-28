<?php

namespace Models;

class SeatType //Tipo_plaza
{
    private $idSeatType;
    private $seatTypeName;
    private $description;

    public function getSeatTypeName()
	{
		return $this->seatTypeName;
	}

    public function setSeatTypeName($seatTypeName)
    {
        $this->seatTypeName = $seatTypeName;

        return $this;
    }

	public function getDescription()
	{
		return $this->description;
	}

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }

	public function getIdSeatType()
	{
		return $this->idSeatType;
	}

    public function setIdSeatType($idSeatType)
    {
        $this->idSeatType = $idSeatType;

        return $this;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}