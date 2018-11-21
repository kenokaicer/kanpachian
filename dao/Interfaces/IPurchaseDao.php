<?php
namespace Dao\Interfaces;

use Models\Purchase as Purchase;

interface IPurchaseDao
{
    public function Add(Purchase $purchase);
    public function getById($idPurchase);
    public function getAll($load);
    public function getAllByIdClient($idClient, $load);
    public function getAllNew();
    public function Update(Purchase $oldPurchase, Purchase $newPurchase);
    public function Delete(Purchase $purchase);
}
