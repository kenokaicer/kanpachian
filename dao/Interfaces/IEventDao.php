<?php
namespace Dao\Interfaces;

use Models\Event as Event;

interface IEventDao
{
    public function Add(Event $event);
    public function getById($idEvent);
    public function getAll();
    public function Update(Event $oldEvent, Event $newEvent);
    public function Delete(Event $event);
}
