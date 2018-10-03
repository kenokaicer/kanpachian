<?php
namespace controllers;

use dao\Json\ListaArtists as ListaArtists;
use models\Artist as Artist;

class ArtistGestionController
{
    protected $message;
    private $listaArtists;

    public function __construct()
    {
        $this->listaArtists = ListaArtists::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT . 'views/ArtistGestion.php';
    }

    public function cargarArtist($nombre, $apellido)
    {
        try {
            $Artist = new Artist();
            $Artist->setNombre($nombre)->setApellido($apellido);
            $this->listaArtists->Add($Artist);
            $this->index();
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function listarArtists()
    {
        var_dump($this->listaArtists->RetrieveAll());
    }

}
