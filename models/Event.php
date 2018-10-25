<?php

namespace Models;

use Models\EventsByDate as EventsByDate;
use Models\Category as Category;

class Event
{
    private $idEvent;
    private $eventName;
    private $image;
    private $description;
    private $eventsByDate = array(); //Class EventsByDate (calendario)
    private $category; //Class Category

    public function getEventName()
    {
        return $this->eventName;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getIdEvent()
    {
        return $this->idEvent;
    }

    public function setIdEvent($idEvent)
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function addCalendar(Calendar $calendar)
    {
        $this->calendar[] = $calendar;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
	public function getEventsByDate()
	{
		return $this->eventsByDate;
	}

    public function setEventsByDate($eventsByDate)
    {
        $this->eventsByDate = $eventsByDate;

        return $this;
    }
}
