<?php
namespace Dao\Interfaces;

use Models\EventByDate as EventByDate;

interface IEventByDateDao
{
    public function Add(EventByDate $eventByDate);
    public function getByID($id);
    public function getAll();
    public function Update(EventByDate $oldEventByDate, EventByDate $newEventByDate);
    public function Delete(EventByDate $eventByDate);
}
