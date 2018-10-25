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
    private $folder = "ArtistManagement/";

    public function __construct()
    {
        //$this->ArtistsDao = ArtistList::getInstance(); //Json
        $this->artistsDao = ArtistsDao::getInstance(); //BD
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."ArtistManagement.php";
    }

    public function viewAddArtist()
    {
        require VIEWS_PATH.$this->folder."ArtistManagementAdd.php";
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
        
        require VIEWS_PATH.$this->folder."ArtistManagementList.php";
    }

    public function deleteArtist($id)
    {
        $artist = $this->artistsDao->getByID($idArtist);

        try{
            $this->artistsDao->Delete($artist);
            echo "<script> alert('Artista eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->artistList();
    }

    /**
     * Recieve id of Artist to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editArtist
     */
    public function viewEditArtist($idArtist)
    {   
        $oldArtist = $this->artistsDao->getByID($idArtist);

        require VIEWS_PATH.$this->folder."ArtistManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object Artist
     * and old object by id, call dao update
     */
    public function editArtist($oldIdArtist, $name, $lastname)
    {
        $oldArtist = $this->artistsDao->getByID($oldIdArtist);
        $newArtist = new Artist();

        $args = func_get_args();
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

        $this->artistList();
    }

}
