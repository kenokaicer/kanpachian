<?php
namespace Dao\Interfaces;

use Models\Client as Client;

interface IClientDao
{
    public function Add(Client $client);
    public function getById($id);
    public function getAll();
    public function Update(Client $oldClient, Client $newClient);
    public function Delete(Client $client);
}
