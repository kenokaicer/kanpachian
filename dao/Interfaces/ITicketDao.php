<?php
namespace Dao\Interfaces;

use Models\Ticket as Ticket;

interface ITicketDao
{
    public function Add(Ticket $ticket);
    public function getById($idTicket);
    public function getAll();
    public function getAllByClient($idClient);
    public function getAllByPurchase($idPurchase);
    public function Update(Ticket $oldTicket, Ticket $newTicket);
    public function Delete(Ticket $ticket);
}
