<?php

namespace Models;

use Models\Theater as Theater;

class EventsByDate//Calendario

{
    private $idEventsByDate;
    private $date;
    private $theater;
    private $eventsBySeats = array();

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

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
}
