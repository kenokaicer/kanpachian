<?php
namespace Dao\Interfaces;

use Models\SeatType as SeatType;

interface ISeatTypeDao
{
    public function Add(SeatType $seatType);
    public function getById($id);
    public function getAll();
    public function getAllByTheaterId($id);
    public function Update(SeatType $oldSeatType, SeatType $newSeatType);
    public function Delete(SeatType $seatType);
}
