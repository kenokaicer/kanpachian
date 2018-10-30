<?php
namespace Dao\Interfaces;

use Models\User as User;

interface IUserDao
{
    public function Add(User $user);
    public function getByID($id);
    public function getAll();
    public function Update(User $oldUser, User $newUser);
    public function Delete(User $user);
}
