<?php
namespace Controllers;

use Dao\Json\ArtistList as ArtistList;
use Models\Artist as Artist;

class ArtistManagementController
{
    protected $message;
    private $artistList;

    public function __construct()
    {
        $this->artistList = ArtistList::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT . 'views/ArtistManagement.php';
    }

    public function addArtist($name, $lastname)
    {
        try {
            $Artist = new Artist();
            $Artist->setName($name)->setLastname($lastname);
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
