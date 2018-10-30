<?php
namespace Models;

class Artist
//implements JsonSerializable

{
    private $idArtist;
    private $name;
    private $lastname;

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

    /*public function jsonSerialize(){ //si se puediera implementar interfaz JsonSerializable
    return
    //[
    array(
    'name'   => $this->getName(),
    'lastname' => $this->getLastname()
    );
    //];
    }*/

    //The Bad //alternative to JsonSerializable interface
    public function toJson() 
    {
        return json_encode(
            [
                'name' => $this->getName(),
                'lastname' => $this->getLastname(),
            ]
        );
    }

    /**
     * Returns all attributes as an array, used for edit dao
     */
    public function getAll()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
