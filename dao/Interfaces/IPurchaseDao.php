<?php
namespace Dao\Interfaces;

use Models\Purchase as Purchase;

interface IPurchaseDao
{
    public function Add(Purchase $purchase);
    public function getById($id);
    public function getAll();
    public function Update(Purchase $oldPurchase, Purchase $newPurchase);
    public function Delete(Purchase $purchase);
}
