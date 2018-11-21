<?php
namespace Dao\Interfaces;

use Models\EventByDate as EventByDate;

interface IEventByDateDao
{
    public function Add(EventByDate $eventByDate);
    public function getById($idEventByDate);
    public function getAll();
    public function getAllByArtist($idArtist);
    public function Update(EventByDate $oldEventByDate, EventByDate $newEventByDate);
    public function Delete(EventByDate $eventByDate);
    public function getByEventId($idEvent, $load);
    public function getByEventIdAndTheaterIdLazy($idEvent, $idTheater);
}
