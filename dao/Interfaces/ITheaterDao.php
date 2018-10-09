<?php
namespace Dao\Interfaces;

use Models\Theater as Theater;

interface ITheaterDao
{
    public function Add(Theater $theater);
    public function Retrieve($var);
    public function RetrieveAll();
    public function Update(Theater $oldTheater, Theater $newTheater);
    public function Delete($id);
}