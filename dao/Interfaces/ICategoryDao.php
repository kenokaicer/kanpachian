<?php
namespace Dao\Interfaces;

use Models\Category as Category;

interface ICategoryDao
{
    public function Add(Category $category);
    public function getById($idCategory);
    public function getByCategoryName($categoryName);
    public function getAll();
    public function Update(Category $oldCategory, Category $newCategory);
    public function Delete(Category $category);
}
