<?php
namespace Controllers;

use Dao\BD\PurchaseLineDao as PurchaseLineDao;
use Dao\BD\CategoryDao as CategoryDao;
use Dao\BD\LoadType as LoadType;
use Exception as Exception;
use Cross\Session as Session;

class CheckSalesByDateOrCategoryController
{   
    private $purchaseLineDao;

    public function __construct()
    {
        Session::adminLogged();
        $this->purchaseLineDao = new PurchaseLineDao();
        $this->categoryDao = new CategoryDao();
    }
    
    public function index($alert = array())
    {	
        try{
            $totalPriceByCategoryArray = $this->getTotalByCategory();
        }catch (Exception $ex){
            $alert["title"] = "Error al cargar precios por categoría";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        require VIEWS_PATH."SalesByDateOrCategory.php";
    }

    private function getTotalByCategory()
    {
        try{
            $purchaseLineList = $this->purchaseLineDao->getAll(LoadType::Lazy1);
            $categoryList = $this->categoryDao->getAll();
            $totalPriceByCategoryArray = array();
            $categoryArray = array();

            /**
             * Create an array with categories as key and totalPrice as value
             */
            foreach ($purchaseLineList as $purchaseLine) {
                $catName = $purchaseLine->getSeatsByEvent()->getEventByDate()->getEvent()->getCategory()->getCategoryName();
                if(!array_key_exists($catName,$totalPriceByCategoryArray)) //check if a category is already set in the array
                {
                    $totalPriceByCategoryArray[$catName] = 0; //if it's no, set that position in the array as an array
                }
                $totalPriceByCategoryArray[$catName] += $purchaseLine->getPrice();
            }

            /**
             * Set 0 to categories that don't have sales
             */
            foreach ($categoryList as $category) {
                $categoryArray[] = $category->getCategoryName();
            }

            $catKeys = array_keys($totalPriceByCategoryArray);

            foreach ($categoryArray as $cat) {
                if(!in_array($cat, $catKeys)){
                    $totalPriceByCategoryArray[$cat] = 0;
                }
            }

            return $totalPriceByCategoryArray;
        }catch (Exception $ex){
            echo "<script>swal({
                title:'Error al calcular total por categoría!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        }
    }
}
