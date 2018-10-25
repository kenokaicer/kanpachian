<?php
namespace Dao\Interfaces;

use Models\Theater as Theater;

interface ITheaterDao
{
    public function Add(Theater $theater);
    public function Get($var);
    public function getAll();
    public function Update(Theater $oldTheater, Theater $newTheater);
    public function Delete($id);
}