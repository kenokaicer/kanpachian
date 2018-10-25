<?php
namespace Controllers;

//use Dao\Json\ArtistList as ArtistList;
use Dao\BD\ArtistsDao as ArtistsDao;
use Models\Artist as Artist;
use Exception as Exception;

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
        $artist = new Artist();
        
        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        
        $artistAtributeList = array_combine(array_keys($artist->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
        
        foreach ($artistAtributeList as $atribute => $value) {
            $artist->__set($atribute,$value);
        }

        try{
            $this->artistsDao->Add($artist);
            echo "<script> alert('Artista agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el artista. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function artistList()
    {
        try{
            $artistList = $this->artistsDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Artistas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require ROOT."Views/ArtistManagementList.php";
    }

    public function deleteArtist($id, $name, $lastname) //should this recieve all parameters and make an object?
    {
        $artist = new Artist();

        $artistAtributeList = array_combine(array_keys($artist->getAll()),array_values(func_get_args())); 

        foreach ($artistAtributeList as $atribute => $value) {
            $artist->__set($atribute,$value);
        }

        try{
            $this->artistsDao->Delete($artist);
            echo "<script> alert('Artista eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->artistList();
    }

    /**
     * Recieve unmodified atributes for Artist for diplaying in the forms,
     * then after the modifications sends them to this->editArtist
     */
    public function viewEditArtist($id, $name, $lastname)
    {   
        $oldArtist = new Artist();

        $artistAtributeList = array_combine(array_keys($oldArtist->getAll()),array_values(func_get_args())); 

        foreach ($artistAtributeList as $atribute => $value) {
            $oldArtist->__set($atribute,$value);
        }
        
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

        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        $artistAtributeList = array_combine(array_keys($newArtist->getAll()),array_values($args)); 

        foreach ($artistAtributeList as $atribute => $value) {
            $newArtist->__set($atribute,$value);
        }

        try{
            $this->artistsDao->Update($oldArtist, $newArtist);
            echo "<script> alert('Artista modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        unset($_SESSION["oldArtist"]);
        $this->artistList();
    }

}
