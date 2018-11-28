<?php
namespace Controllers;

use Dao\BD\CategoryDao as CategoryDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Models\Category as Category;
use Exception as Exception;
use Cross\Session as Session;

class CategoryManagementController
{
    private $categoryDao;
    private $eventByDateDao;
    private $folder = "Management/Category/";

    public function __construct()
    {
        Session::adminLogged();
        $this->categoryDao = new CategoryDao(); //BD
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

        require VIEWS_PATH.$this->folder."CategoryManagement.php";
    }

    public function viewAddCategory()
    {
        require VIEWS_PATH.$this->folder."CategoryManagementAdd.php";
    }

    public function addCategory($categoryName)
    {   
        try{
            if(is_null($this->categoryDao->getByCategoryName($categoryName)))
            {
                $category = new Category();
            
                $args = func_get_args();
                array_unshift($args, null); //put null at first of array for id
                
                $categoryAttributeList = array_combine(array_keys($category->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
                
                foreach ($categoryAttributeList as $attribute => $value) {
                    $category->__set($attribute,$value);
                }
            
                $this->categoryDao->Add($category);

                $alert["title"] = "Categoría agregada exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "Categoría ya existente en el sistema";
                $alert["icon"] = "warning";
            }
            
        }catch (Exception $ex){
            $alert["title"] = "Error al agregar la categoría";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }
        
        $this->index($alert);
    }

    public function categoryList($alert = array())
    {
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        try{
            $categoryList = $this->categoryDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al intentar listar las categorías";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
        }
        
        require VIEWS_PATH.$this->folder."CategoryManagementList.php";
    }

    public function deleteCategory($idCategory)
    {
        try{
            if(empty($this->eventByDateDao->getAllPastNowByCategory($idCategory))){
                $category = $this->categoryDao->getById($idCategory);

                $this->categoryDao->Delete($category);
                
                $alert["title"] = "Categoría eliminada exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "La categoría existe en un calendario futuro";
                $alert["text"] = "No se permite el borrado";
                $alert["icon"] = "warning";
                //you could actually tell here which eventByDates are locking this
            }
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar categoría";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->categoryList($alert);
    }

    /**
     * Recieve id of Category to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editCategory
     */
    public function viewEditCategory($idCategory)
    {   
        try{
            $oldCategory = $this->categoryDao->getById($idCategory);
        } catch (Exception $ex) {
            $alert["title"] = "Error al intentar buscar categoría";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->categoryList($alert);
        }
        
        require VIEWS_PATH.$this->folder."CategoryManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object Category
     * and old object by id, call dao update
     */
    public function editCategory($oldIdCategory, $category)
    {
        try{
            if(is_null($this->categoryDao->getByCategoryName($category)))
            {
                $oldCategory = $this->categoryDao->getById($oldIdCategory);
                $newCategory = new Category();

                $args = func_get_args();
                $categoryAttributeList = array_combine(array_keys($newCategory->getAll()),array_values($args)); 

                foreach ($categoryAttributeList as $attribute => $value) {
                    $newCategory->__set($attribute,$value);
                }

                $this->categoryDao->Update($oldCategory, $newCategory);
                
                $alert["title"] = "Categoría modificado exitosamente";
                $alert["icon"] = "success";
            }else{
                echo "<script> alert('Categoría ya existente');</script>";
            }
        }catch (Exception $ex) {
            $alert["title"] = "Error no se pudo modificar la categoría";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->categoryList($alert);
    }

}
