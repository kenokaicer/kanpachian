<?php
namespace Dao\Interfaces;

use Models\Artist as Artist;

interface IArtistDao
{
    public function Add(Artist $artist);
    public function Retrieve($var);
    public function RetrieveAll();
    public function Update(Artist $oldArtist, Artist $newArtist);
    public function Delete($id);
}
