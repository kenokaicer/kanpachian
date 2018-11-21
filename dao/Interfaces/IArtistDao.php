<?php
namespace Dao\Interfaces;

use Models\Artist as Artist;

interface IArtistDao
{
    public function Add(Artist $artist);
    public function getById($idArtist);
    public function getByNameOrAndLastname($nameOrAndLastname);
    public function getAllArtitsByEventByDate($idEvent);
    public function getAll();
    public function Update(Artist $oldArtist, Artist $newArtist);
    public function Delete(Artist $artist);
}
