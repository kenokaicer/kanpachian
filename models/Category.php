<?php

namespace Models;

class Category extends Attributes
{
    protected $idCategory;
    protected $categoryName;
    
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
}
