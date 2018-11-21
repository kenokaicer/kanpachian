<?php
namespace Dao\Interfaces;

use Models\SeatType as SeatType;

interface ISeatTypeDao
{
    public function Add(SeatType $seatType);
    public function getById($idSeatType);
    public function getBySeatTypeName($seatTypeName);
    public function getAll();
    public function getAllByTheaterId($idTheater);
    public function Update(SeatType $oldSeatType, SeatType $newSeatType);
    public function Delete(SeatType $seatType);
}
