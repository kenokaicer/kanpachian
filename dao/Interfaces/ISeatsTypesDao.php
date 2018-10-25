<?php
namespace Dao\Interfaces;

use Models\SeatsType as SeatsType;

interface ISeatsTypesDao
{
    public function Add(SeatsType $seatsType);
    public function RetrieveById($id);
    public function RetrieveAll();
    public function Update(SeatsType $oldSeatsType, SeatsType $newSeatsType);
    public function Delete(SeatsType $seatsType);
}
