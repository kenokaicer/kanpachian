<?php
namespace Controllers;

//use Dao\Json\ArtistList as ArtistList;
use Dao\BD\ArtistDao as ArtistDao;
use Models\Artist as Artist;
use Exception as Exception;
use Cross\Session as Session;

class ArtistManagementController
{
    private $artistDao;
    private $folder = "Management/Artist/";

    public function __construct()
    {
        Session::adminLogged();
        //$this->ArtistDao = ArtistList::getInstance(); //Json
        $this->artistDao = new ArtistDao(); //BD
    }

    public function index()
    { 
        require VIEWS_PATH.$this->folder."ArtistManagement.php";
    }

    public function viewAddArtist()
    {
        require VIEWS_PATH.$this->folder."ArtistManagementAdd.php";
    }

    public function addArtist($name, $lastname)
    {
        try{
            $artist = new Artist();
            
            $args = func_get_args();
            array_unshift($args, null); //put null at first of array for id
            
            $artistAttributeList = array_combine(array_keys($artist->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
            
            foreach ($artistAttributeList as $attribute => $value) {
                $artist->__set($attribute,$value);
            }

            $this->artistDao->Add($artist);
            echo "<script> alert('Artista agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el artista. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function artistList()
    {
        try{
            $artistList = $this->artistDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Artistas: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."ArtistManagementList.php";
    }

    public function deleteArtist($idArtist)
    {
        try{
            $artist = $this->artistDao->getById($idArtist);
 
            $this->artistDao->Delete($artist);
            echo "<script> alert('Artista eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->artistList();
    }

    /**
     * Recieve id of Artist to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editArtist
     */
    public function viewEditArtist($idArtist)
    {   
        try{
            $oldArtist = $this->artistDao->getById($idArtist);
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar Artista: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."ArtistManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object Artist
     * and old object by id, call dao update
     */
    public function editArtist($oldIdArtist, $name, $lastname)
    {
        try{
            $oldArtist = $this->artistDao->getById($oldIdArtist);
            $newArtist = new Artist();

            $args = func_get_args();
            $artistAttributeList = array_combine(array_keys($newArtist->getAll()),array_values($args)); 

            foreach ($artistAttributeList as $attribute => $value) {
                $newArtist->__set($attribute,$value);
            }

            $this->artistDao->Update($oldArtist, $newArtist);
            echo "<script> alert('Artista modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->artistList();
    }

}
