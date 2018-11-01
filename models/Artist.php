<?php
namespace Models;
use JsonSerializable as JsonSerializable;

class Artist extends Attributes implements JsonSerializable
{
    protected $idArtist;
    protected $name;
    protected $lastname;

    public function getIdArtist()
    {
        return $this->idArtist;
    }

    public function setIdArtist($idArtist)
    {
        $this->idArtist = $idArtist;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function jsonSerialize(){ //si se puediera implementar interfaz JsonSerializable
        /*return array( 
            'idArtist' => $this->getIdArtist(),
            'name'   => $this->getName(),
            'lastname' => $this->getLastname()
            );*/
        return $this->getAll(); //This works instead of the previous array, previous lines left only to know what jsonSerialize expects
    }

    //The Bad //alternative to JsonSerializable interface, deprecated
    public function toJson() 
    {
        return json_encode(
            [
                'idArtist' => $this->getIdArtist(),
                'name' => $this->getName(),
                'lastname' => $this->getLastname(),
            ]
        );
    }
}
