<?php

namespace Models;

class SeatType extends Attributes //Tipo_plaza
{
    protected $idSeatType;
    protected $seatTypeName;
    protected $description;

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

	public function getIdSeatType()
	{
		return $this->idSeatType;
	}

    public function setIdSeatType($idSeatType)
    {
        $this->idSeatType = $idSeatType;

        return $this;
    }
}