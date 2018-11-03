<?php
namespace Dao\Json;

use Dao\interfaces\IArtistDao as IArtistDao;
use Dao\Json\Json as Json;
use Models\Artist as Artist;
use Karriere\JsonDecoder\JsonDecoder as JsonDecoder;

/**
 * TO DO
 * Get, Update, Delete
 */


class ArtistList
{
    protected $file = JSONFOLDER."Artists.json";

    /**
     * Get list from file, and an object and then serilize it and store it again
     */
    public function Add(Artist $artist)
    {
        $listaArtist = array();
        $artistList = $this->getAll();

        if(!empty($artistList)){
            $lastId = end($artistList)->getIdArtist();
            $artist->setIdArtist($lastId+1);
        }else{
            $artist->setIdArtist(1);
        }
        
        array_push($artistList, $artist);

        json::Serilize($artistList, $this->file);
    }

    /**
     * Returns a complete list of Artists stored in artists.json
     */
    public function getAll()
    {
        $jsonDecodedData = Json::Deserilize($this->file);
        $jsonDecoder = new JsonDecoder(true, true);         //true bool to access private attributes of class

        if($jsonDecodedData == null || empty($jsonDecodedData)){ //if file was empty return empty array
            return array();
        }
        //if(json_decode($jsonDecodedData)!=array()){ //deserilize to know if it's an array, but time consuming
        if($jsonDecodedData[0]!="["){                 //check if first character of the json string is a [ to know if it's an array, much less time consuming
            $artists[] = $jsonDecoder->decode($jsonDecodedData, Artist::class);
        }else{
            $artists = $jsonDecoder->decodeMultiple($jsonDecodedData, Artist::class); //This will cast the required class to the object from json string
        }

        return $artists;
    }

    public function getById($idArtist)
    {
        $artists = $this->getAll();
        $returnedArtist = null;

        foreach ($artists as $artist) {
            if($idArtist==$artist->getIdArtist())
            {
                $returnedArtist = $artist;
                break;
            }
        }

        return $returnedArtist;
    }

    public function Update(Artist $oldArtist, Artist $newArtist)
    {

    }

    public function Delete($var)
    {

    }
}
