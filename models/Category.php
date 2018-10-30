<?php

namespace Models;

class Category
{
    private $idCategory;
    private $categoryName;
    
    public function getIdCategory()
	{
		return $this->idCategory;
	}

    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    public function getCategoryName()
	{
		return $this->categoryName;
	}

    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
