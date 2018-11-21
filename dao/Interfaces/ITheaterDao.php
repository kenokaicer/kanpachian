<?php
namespace Dao\Interfaces;

use Models\Theater as Theater;

interface ITheaterDao
{
    public function Add(Theater $theater);
    public function getById($idTheater, $load);
    public function getAll();
    public function Update(Theater $oldTheater, Theater $newTheater);
    public function Delete(Theater $theater);
}