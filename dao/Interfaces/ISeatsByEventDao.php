<?php
namespace Dao\Interfaces;

use Models\SeatsByEvent as SeatsByEvent;

interface ISeatsByEventDao
{
    public function Add(SeatsByEvent $seatsByEvent);
    public function getById($idSeatsByEvent, $load);
    public function getByEventByDateId($idEventByDate, $load);
    public function getAll($load);
    public function Update(SeatsByEvent $oldSeatsByEvent, SeatsByEvent $newSeatsByEvent);
    public function Delete(SeatsByEvent $seatsByEvent);
}
