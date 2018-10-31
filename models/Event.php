<?php

namespace Models;

use Models\EventByDate as EventByDate;
use Models\Category as Category;

class Event
{
    private $idEvent;
    private $eventName;
    private $image;
    private $description;
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

    public function getImage()
	{
		return $this->image;
	}

    public function setImage($image)
    {
        $this->image = $image;

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
    
	public function getDescription()
	{
		return $this->description;
	}

    public function setDescription($description)
    {
        $this->description = $description;

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
    
	public function getEventByDate()
	{
		return $this->eventByDate;
	}

    public function setEventByDate($eventByDate)
    {
        $this->eventByDate = $eventByDate;

        return $this;
    }


}
