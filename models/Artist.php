<?php
namespace models;

class Artist 
//implements JsonSerializable
{
    private $nombre;
    private $apellido;

    public function __construct()
    {
        //to add
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre) //interfaz tipada o con hint, segun nombre clase
    {
        $this->nombre = $nombre;

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
    'nombre'   => $this->getNombre(),
    'apellido' => $this->getApellido()
        );
    //];
    }*/

    //The Bad
    public function toJson() //alternative to JsonSerializable interface
    {
        return json_encode(
            [
                'nombre' => $this->getNombre(),
                'apellido' => $this->getApellido(),
            ]
        );
    }

}
