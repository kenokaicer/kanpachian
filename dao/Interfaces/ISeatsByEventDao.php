<?php
namespace Dao\Interfaces;

use Models\SeatsByEvent as SeatsByEvent;

interface ISeatsByEventDao
{
    public function Add(SeatsByEvent $seatsByEvent);
    public function getById($id);
    public function getAll();
    public function Update(SeatsByEvent $oldSeatsByEvent, SeatsByEvent $newSeatsByEvent);
    public function Delete(SeatsByEvent $seatsByEvent);
}
