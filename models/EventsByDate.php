<?php

namespace Models;

use Models\Theater as Theater;

class EventsByDate//Calendario

{
    private $idEventsByDate;
    private $date;
    private $event; //Class Event
    private $theater; //Class Theater
    private $artists = array(); //Array of Class Artist
    //private $seatsByEvents = array(); //Array of Class SeatsByEvent //this only if I want a bidirectional dependancy

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

    public function getIdEventsByDate()
    {
        return $this->idEventsByDate;
    }

    public function setIdEventsByDate($idEventsByDate)
    {
        $this->idEventsByDate = $idEventsByDate;

        return $this;
    }

    public function addEventsBySeat($eventBySeat)
    {
        $this->eventsBySeats[] = $eventBySeat;
    }

    public function getArtists()
    {
        return $this->artists;
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
