<?php
namespace controllers;

use dao\Json\ListaArtistas as ListaArtistas;
use models\Artista as Artista;

class ArtistGestionController
{
    protected $message;
    private $listaArtistas;

    public function __construct()
    {
        $this->listaArtistas = ListaArtistas::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT . 'views/ArtistGestion.php';
    }

    public function cargarArtista($nombre, $apellido)
    {
        try {
            $artista = new Artista();
            $artista->setNombre($nombre)->setApellido($apellido);
            $this->listaArtistas->Add($artista);
            $this->index();
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function listarArtistas()
    {
        var_dump($this->listaArtistas->RetrieveAll());
    }

}
