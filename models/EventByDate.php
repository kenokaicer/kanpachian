<?php

namespace Models;

use Models\Theater as Theater;

class EventByDate//Calendario

{
    private $idEventByDate;
    private $date;
    private $event; //Class Event
    private $theater; //Class Theater
    private $artists = array(); //Array of Class Artist
    //private $seatsByEvents = array(); //Array of Class SeatsByEvent //this only if I want a bidirectional dependancy

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

    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    public function getTheater()
    {
        return $this->theater;
    }

    public function setTheater($theater)
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

    public function addEventsBySeat($eventBySeat)
    {
        $this->eventsBySeats[] = $eventBySeat;
    }

    public function addArtist($artist)
    {
        $this->artists[] = $artist;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

}
