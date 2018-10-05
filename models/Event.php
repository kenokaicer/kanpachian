<?php

namespace Models;

use Models\Category as Category;
use Models\Calendar as Calendar;

class Event
{
    private $id;
    private $eventName;
    private $calendar = array(); //Class Calendar
    private $category; //Enum Category


	public function getEventName()
	{
		return $this->eventName;
	}

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

	public function getCalendarsList()
	{
		return $this->calendar;
	}

    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;

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

    public function addCalendar(Calendar $calendar)
    {
        $this->calendar[] = $calendar;
    }
}