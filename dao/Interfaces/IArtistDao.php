<?php
namespace Dao\Interfaces;

use Models\Artist as Artist;

interface IArtistDao
{
    public function Add(Artist $artist);
    public function GetByID($id);
    public function GetAll();
    public function Update(Artist $oldArtist, Artist $newArtist);
    public function Delete(Artist $artist);
}
