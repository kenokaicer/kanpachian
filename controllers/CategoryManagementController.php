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

    public function index()
    {
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
                echo "<script> alert('Categoría agregada exitosamente');</script>";
            }else{
                echo "<script> alert('Categoría ya existente');</script>";
            }
            
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la categoría. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function categoryList()
    {
        try{
            $categoryList = $this->categoryDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Categorías: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."CategoryManagementList.php";
    }

    public function deleteCategory($idCategory)
    {
        try{
            if(empty($this->eventByDateDao->getAllPastNowByCategory($idCategory))){
                $category = $this->categoryDao->getById($idCategory);

                $this->categoryDao->Delete($category);
                echo "<script> alert('Categoría eliminada exitosamente');</script>";
            }else{
                echo "<script> alert('Categoría existe en un calendario futuro, no se permite el borrado');</script>";
                //you could actually tell here which eventByDates are locking this
            }
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la categoría. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->categoryList();
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
            echo "<script> alert('Error al buscar categoría. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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
                echo "<script> alert('Categoría modificada exitosamente');</script>";
            }else{
                echo "<script> alert('Categoría ya existente');</script>";
            }
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el categoría " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->categoryList();
    }

}
