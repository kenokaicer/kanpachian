<?php
namespace Dao\Interfaces;

use Models\EventsByDate as EventsByDate;

interface IEventsByDateDao
{
    public function Add(EventsByDate $eventsByDate);
    public function getByID($id);
    public function getAll();
    public function Update(EventsByDate $oldEventsByDate, EventsByDate $newEventsByDate);
    public function Delete(EventsByDate $eventsByDate);
}
