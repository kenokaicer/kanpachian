<?php
namespace Dao\Interfaces;

use Models\CreditCard as CreditCard;

interface ICreditCardDao
{
    public function Add(CreditCard $creditCard);
    public function getById($idClient);
    public function getByCreditCardNumber($creditCardNumber);
    public function getAll();
    public function Update(CreditCard $oldCreditCard, CreditCard $newCreditCard);
    public function Delete(CreditCard $creditCard);
}
