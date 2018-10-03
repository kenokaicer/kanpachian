<?php
namespace controllers;

use dao\Json\ArtistList as ArtistList;
use models\Artist as Artist;

class ArtistGestionController
{
    protected $message;
    private $artistList;

    public function __construct()
    {
        $this->artistList = ArtistList::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT . 'views/ArtistGestion.php';
    }

    public function addArtist($name, $apellido)
    {
        try {
            $Artist = new Artist();
            $Artist->setname($name)->setApellido($apellido);
            $this->artistList->Add($Artist);
            $this->index();
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function artistList()
    {
        var_dump($this->artistList->RetrieveAll());
    }

}
