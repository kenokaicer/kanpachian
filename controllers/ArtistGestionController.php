<?php
namespace controllers;

use dao\Json\artistList as artistList;
use models\Artist as Artist;

class ArtistGestionController
{
    protected $message;
    private $artistList;

    public function __construct()
    {
        $this->artistList = artistList::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT . 'views/ArtistGestion.php';
    }

    public function addArtist($nombre, $apellido)
    {
        try {
            $Artist = new Artist();
            $Artist->setNombre($nombre)->setApellido($apellido);
            $this->artistList->Add($Artist);
            $this->index();
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function listarArtists()
    {
        var_dump($this->artistList->RetrieveAll());
    }

}
