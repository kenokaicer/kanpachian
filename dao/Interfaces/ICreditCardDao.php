<?php
namespace Dao\Interfaces;

use Models\CreditCard as CreditCard;

interface ICreditCardsDao
{
    public function Add(CreditCard $creditCard);
    public function getById($id);
    public function getAll();
    public function Update(CreditCard $oldCreditCard, CreditCard $newCreditCard);
    public function Delete(CreditCard $creditCard);
}
