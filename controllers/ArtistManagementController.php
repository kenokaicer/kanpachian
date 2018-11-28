<?php
namespace Controllers;

//use Dao\Json\ArtistList as ArtistList;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Models\Artist as Artist;
use Exception as Exception;
use Cross\Session as Session;

class ArtistManagementController
{
    private $artistDao;
    private $eventByDateDao;
    private $folder = "Management/Artist/";

    public function __construct()
    {
        Session::adminLogged();
        //$this->ArtistDao = ArtistList::getInstance(); //Json
        $this->artistDao = new ArtistDao(); //BD
        $this->eventByDateDao = new EventByDateDao();
    }

    public function index($alert = array())
    { 
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        require VIEWS_PATH.$this->folder."ArtistManagement.php";
    }

    public function viewAddArtist()
    {
        require VIEWS_PATH.$this->folder."ArtistManagementAdd.php";
    }

    public function addArtist($name, $lastname)
    {
        try{
            $alert = array();

            if(empty($this->artistDao->getByNameAndLastname($name,$lastname)))
            {
                $artist = new Artist();
            
                $args = func_get_args();
                array_unshift($args, null); //put null at first of array for id
                
                $artistAttributeList = array_combine(array_keys($artist->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
                
                foreach ($artistAttributeList as $attribute => $value) {
                    $artist->__set($attribute,$value);
                }

                $this->artistDao->Add($artist);

                $alert["title"] = "Artista agregado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "Artista ya existente en el sistema";
                $alert["text"] = "Si es otra persona agregue un alias";
                $alert["icon"] = "warning";
            }
        }catch (Exception $ex){
            $alert["title"] = "Error al agregar Artista";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }
        
        $this->index($alert);
    }

    public function artistList($alert = array())
    {
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        try{
            $artistList = $this->artistDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al intentar listar Artistas";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
        }
        
        require VIEWS_PATH.$this->folder."ArtistManagementList.php";
    }

    public function deleteArtist($idArtist)
    {
        try{
            if(empty($this->eventByDateDao->getAllPastNowByArtist($idArtist))){ //check if there are future eventByDates with this artist
                $artist = $this->artistDao->getById($idArtist);
 
                $this->artistDao->Delete($artist);
                
                $alert["title"] = "Artista eliminado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "Artista existe en un calendario futuro";
                $alert["text"] = "No se permite el borrado";
                $alert["icon"] = "warning";
                //you could actually tell here which eventByDates are locking this
            }
            
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar artista";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->artistList($alert);
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
            $alert["title"] = "Error al intentar buscar Artista";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->artistList($alert);
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
            
            $alert["title"] = "Artista modificado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex) {
            $alert["title"] = "Error no se pudo modificar el artista";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->artistList($alert);
    }

}
