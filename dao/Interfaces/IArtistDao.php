<?php
namespace dao\Interfaces;

use models\Artist as Artist;

interface IArtistDao
{
    public function Add(Artist $artist);
    public function Retrieve($var);
    public function RetrieveAll();
    public function Update(Artist $artist);
    public function Delete($var);
}
