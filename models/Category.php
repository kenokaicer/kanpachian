<?php

namespace Models;

class Category
{
    private $category;

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

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
