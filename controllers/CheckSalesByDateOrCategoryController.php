<?php
namespace Controllers;

use Dao\BD\PurchaseLineDao as PurchaseLineDao;
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
    }
    
    public function index()
    {	
        try{
            $totalPriceByCategoryArray = $this->getTotalByCategory();
        }catch (Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH."SalesByDateOrCategory.php";
    }

    private function getTotalByCategory()
    {
        try{
            $purchaseLineList = $this->purchaseLineDao->getAll(LoadType::Lazy1);
            $totalPriceByCategoryArray = array();

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

            return $totalPriceByCategoryArray;
        }catch (Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
    }
}
