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

    /**
     * Recieve unmodified atributes for Artist for diplaying in the forms,
     * then after the modifications sends them to this->editArtist
     */
    public function viewEditArtist($id, $name, $lastname)
    {   
        require ROOT."Views/ArtistManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object Artist.
     * Create two Artist objects for ArtistDao update, only load id in oldArtist as the data is
     * retrieved in the BD, this could be passed as complete object but it would be needed to recieve  
     * the complete oldArtist by serialization or session in past steps
     */
    public function editArtist($id, $name, $lastname)
    {
        $newArtist = new Artist();
        $oldArtist = new Artist();

        $oldArtist->setIdArtist($id); //only id is needed for old artist
        $newArtist->setName($name);
        $newArtist->setLastname($lastname);
        
        $this->ArtistsDao->Update($oldArtist, $newArtist);
        $this->artistList();
    }

}
