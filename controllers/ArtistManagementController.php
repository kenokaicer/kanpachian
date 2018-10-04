<?php
namespace Controllers;

//use Dao\Json\ArtistList as ArtistList;
use Dao\BD\ArtistsDao as ArtistsDao;
use Models\Artist as Artist;

class ArtistManagementController
{
    protected $message;
    private $ArtistsDao;

    public function __construct()
    {
        //$this->ArtistsDao = ArtistList::getInstance();
        $this->ArtistsDao = ArtistsDao::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT."Views/ArtistManagement.php";
    }

    public function viewAddArtist()
    {
        require ROOT."Views/ArtistManagementAdd.php";
    }

    public function addArtist($name, $lastname)
    {
        try {
            $Artist = new Artist();
            $Artist->setName($name)->setLastname($lastname);
            $this->ArtistsDao->Add($Artist);
            $this->index();
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    public function artistList()
    {
        $artistList = $this->ArtistsDao->RetrieveAll();
        require ROOT."Views/ArtistManagementList.php";
    }

    public function deleteArtist($id)
    {
        $this->ArtistsDao->Delete($id);
        $this->artistList();
    }

    public function viewEditArtist($id, $name, $lastname)
    {
        require ROOT."Views/ArtistManagementEdit.php";
    }

    public function editArtist($id, $name, $lastname)
    {
        $artist = new Artist();
        $artist->setName($name);
        $artist->setLastname($lastname);
        
        $this->ArtistsDao->Update($id, $artist);
        $this->artistList();
    }

}
