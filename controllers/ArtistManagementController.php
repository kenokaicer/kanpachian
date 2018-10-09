<?php
namespace Controllers;

//use Dao\Json\ArtistList as ArtistList;
use Dao\BD\ArtistsDao as ArtistsDao;
use Models\Artist as Artist;

class ArtistManagementController
{
    protected $message;
    private $artistsDao;

    public function __construct()
    {
        //$this->ArtistsDao = ArtistList::getInstance(); //Json
        $this->artistsDao = ArtistsDao::getInstance(); //BD
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
            $this->artistsDao->Add($Artist);
            $this->index();
        } catch (Exception $e) {
            echo "<script>alert('Error al agregar. Error message:".$e->getMessage()."')</script>";
        }
    }

    public function artistList()
    {
        $artistList = $this->artistsDao->RetrieveAll();
        require ROOT."Views/ArtistManagementList.php";
    }

    public function deleteArtist($id) //should this recieve all parameters and make an object?
    {
        $this->artistsDao->Delete($id);
        $this->artistList();
    }

    /**
     * Recieve unmodified atributes for Artist for diplaying in the forms,
     * then after the modifications sends them to this->editArtist
     */
    public function viewEditArtist($id, $name, $lastname)
    {   
        $oldArtist = new Artist();
        $oldArtist->setIdArtist($id)->setName($name)->setLastname($lastname);
        $_SESSION["oldArtist"] = $oldArtist;
        require ROOT."Views/ArtistManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object Artist
     * and old object by session, call dao update
     * then unset the old object in session
     */
    public function editArtist($name, $lastname)
    {
        if(isset($_SESSION["oldArtist"])){
            $oldArtist = $_SESSION["oldArtist"];
        }else{
            echo "<script>alert('Error al editar, [Session for old object not set]');</script>";
            $this->artistList();
        }
        $newArtist = new Artist();

        $newArtist->setName($name)->setLastname($lastname);
        
        $this->ArtistsDao->Update($oldArtist, $newArtist);
        unset($_SESSION["oldArtist"]);
        $this->artistList();
    }

}
