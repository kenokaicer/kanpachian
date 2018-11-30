<?php

namespace Models;

use Models\Theater as Theater;
use Models\Artist as Artist;
use Models\Event as Event;

class EventByDate extends Attributes//Calendario

{
    protected $idEventByDate;
    protected $date;
    protected $endPromoDate;
    protected $isSale;
    private $event; //Class Event
    private $theater; //Class Theater
    private $artists = array(); //Array of Class Artist
    //protected $seatsByEvents = array(); //Array of Class SeatsByEvent //this only if I want a bidirectional dependancy

    public function getIdEventByDate()
    {
        return $this->idEventByDate;
    }

    public function setIdEventByDate($idEventByDate)
    {
        $this->idEventByDate = $idEventByDate;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    public function getTheater()
    {
        return $this->theater;
    }

    public function setTheater(Theater $theater)
    {
        $this->theater = $theater;

        return $this;
    }

    public function getArtists()
	{
		return $this->artists;
	}

    public function setArtists($artists)
    {
        $this->artists = $artists;

        return $this;
    }

    public function addArtist(Artist $artist)
    {
        $this->artists[] = $artist;
    }

	public function getIsSale()
	{
		return $this->isSale;
	}

    public function setIsSale($isSale)
    {
        $this->isSale = $isSale;

        return $this;
    }

    public function getEndPromoDate()
	{
		return $this->endPromoDate;
	}

    public function setEndPromoDate($endPromoDate)
    {
        $this->endPromoDate = $endPromoDate;

        return $this;
    }

    public static function getAllAttributes()
    {
        return get_class_vars(get_called_class()); //use get_called_class() insted of class_name($this) because it's a static method
    }
}
