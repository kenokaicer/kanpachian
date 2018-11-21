<?php
namespace Dao\Interfaces;

use Models\Client as Client;
use Models\User as User;
use Models\CreditCard as CreditCard;

interface IClientDao
{
    public function Add(Client $client);
    public function getById($idClient);
    public function getByUserId($idUser, $load);
    public function getAll();
    public function Update(Client $oldClient, Client $newClient);
    public function Delete(Client $client);
    public function addCreditCardByClientIdComplete($idClient, CreditCard $creditCard);
    public function addCreditCardByClientId($idClient, $idCreditCard);
}
