<?php
namespace dao\Interfaces;

interface ITheaterDao
{
    public function Add(Theater $theater);
    public function Retrieve($var);
    public function RetrieveAll();
    public function Update(Theater $theater);
    public function Delete($var);
}
