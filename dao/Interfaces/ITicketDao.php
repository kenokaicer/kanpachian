<?php
namespace Dao\Interfaces;

use Models\Ticket as Ticket;

interface ITicketDao
{
    public function Add(Ticket $ticket);
    public function getById($id);
    public function getAll();
    public function Update(Ticket $oldTicket, Ticket $newTicket);
    public function Delete(Ticket $ticket);
}
