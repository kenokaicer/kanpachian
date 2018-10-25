<?php
namespace Dao\Interfaces;

use Models\Category as Category;

interface ICategoriesDao
{
    public function Add(Category $category);
    public function RetrieveById($id);
    public function RetrieveAll();
    public function Update(Category $oldCategory, Category $newCategory);
    public function Delete(Category $category);
}
