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

    public function getname()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getlastname()
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
    'name'   => $this->getname(),
    'lastname' => $this->getlastname()
    );
    //];
    }*/

    //The Bad
    public function toJson() //alternative to JsonSerializable interface

    {
        return json_encode(
            [
                'name' => $this->getname(),
                'lastname' => $this->getlastname(),
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
