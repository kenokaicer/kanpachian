<?php
namespace models;

class Artist 
//implements JsonSerializable
{
    private $name;
    private $apellido;

    public function __construct()
    {
        //to add
    }

    public function getname()
    {
        return $this->name;
    }

    public function setname($name) //interfaz tipada o con hint, segun name clase
    {
        $this->name = $name;

        return $this;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }
  
    /*public function jsonSerialize(){ //si se puediera implementar interfaz JsonSerializable
    return
    //[
        array(
    'name'   => $this->getname(),
    'apellido' => $this->getApellido()
        );
    //];
    }*/

    //The Bad
    public function toJson() //alternative to JsonSerializable interface
    {
        return json_encode(
            [
                'name' => $this->getname(),
                'apellido' => $this->getApellido(),
            ]
        );
    }

}
