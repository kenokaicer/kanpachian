<?php
namespace dao\Json;

use Dao\interfaces\IArtistDao as IArtistDao;
use Dao\Json\Json as Json;
use Dao\Singletondao as Singletondao;
use Models\Artist as Artist;
use Dao\Json\JsonDecoder\JsonDecoder as JsonDecoder;

//require_once("vendor\karriere\json-decoder\src\JsonDecoder.php");

/**
 * TO DO
 * Retrieve, Update, Delete
 */


class ArtistList extends Singletondao implements IArtistDao
{
    protected $file = JSONFOLDER."Artists.json";

    /**
     * Retrieve list from file, and an object and then serilize it and store it again
     */
    public function Add($object)
    {
        $listaArtist = array();
        $artistList = $this->RetrieveAll();
        array_push($artistList, $object);
        json::Serilize($artistList, $this->file);
    }

    public function Retrieve($var)
    {

    }

    /**
     * Returns a complete list of Artists stored in artists.json
     */
    public function RetrieveAll()
    {
        $jsonDecodedData = Json::Deserilize($this->file);
        $jsonDecoder = new JsonDecoder(true);         //true bool to access private atributes of class
        if($jsonDecodedData == null || empty($jsonDecodedData)){ //if file was empty return empty array
            return array();
        }
        //if(json_decode($jsonDecodedData)!=array()){ //deserilize to know if it's an array, but time consuming
        if($jsonDecodedData[0]!="["){                 //check if first character of the json string is a [ to know if it's an array, much less time consuming
            $Artists[] = $jsonDecoder->decode($jsonDecodedData, Artist::class);
        }else{
            $Artists = $jsonDecoder->decodeMultiple($jsonDecodedData, Artist::class); //This will cast the required class to the object from json string
        }
        return $Artists;
    }

    public function Update(Artist $oldArtist, Artist $newArtist)
    {

    }

    public function Delete($var)
    {

    }
}
