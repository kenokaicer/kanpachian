<?php
namespace Dao\Interfaces;

use Models\PurchaseLine as PurchaseLine;

interface IPurchaseLineDao
{
    public function Add(PurchaseLine $purchaseLine, $idPurchase);
    public function getById($id);
    public function getAll();
    public function Update(PurchaseLine $oldPurchaseLine, PurchaseLine $newPurchaseLine);
    public function Delete(PurchaseLine $purchaseLine);
}
