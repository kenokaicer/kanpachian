<?php
namespace Controllers;

use Dao\BD\CategoriesDao as CategoriesDao;
use Models\Category as Category;
use Exception as Exception;

class CategoryManagementController
{
    protected $message;
    private $categoriesDao;

    public function __construct()
    {
        $this->categoriesDao = CategoriesDao::getInstance(); //BD
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT."Views/CategoryManagement.php";
    }

    public function viewAddCategory()
    {
        require ROOT."Views/CategoryManagementAdd.php";
    }

    public function addCategory($categoryName)
    {
        $category = new Category();
        
        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        
        $categoryAtributeList = array_combine(array_keys($category->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
        
        foreach ($categoryAtributeList as $atribute => $value) {
            $category->__set($atribute,$value);
        }

        try{
            $this->categoriesDao->Add($category);
            echo "<script> alert('Categoría agregada exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la categoría. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function categoryList()
    {
        try{
            $categoryList = $this->categoriesDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Categorías: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require ROOT."Views/CategoryManagementList.php";
    }

    public function deleteCategory($id)
    {
        $category = $this->categoriesDao->getByID($idCategory);

        try{
            $this->categoriesDao->Delete($category);
            echo "<script> alert('Categorya eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la categoría. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->categoryList();
    }

    /**
     * Recieve id of Category to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editCategory
     */
    public function viewEditCategory($idCategory)
    {   
        $oldCategory = $this->categoriesDao->getByID($idCategory);

        require ROOT."Views/CategoryManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object Category
     * and old object by id, call dao update
     */
    public function editCategory($oldIdCategory, $category)
    {
        $oldCategory = $this->categoriesDao->getByID($oldIdCategory);
        $newCategory = new Category();

        $args = func_get_args();
        $categoryAtributeList = array_combine(array_keys($newCategory->getAll()),array_values($args)); 

        foreach ($categoryAtributeList as $atribute => $value) {
            $newCategory->__set($atribute,$value);
        }

        try{
            $this->categoriesDao->Update($oldCategory, $newCategory);
            echo "<script> alert('Categoría modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el categoría " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->categoryList();
    }

}
