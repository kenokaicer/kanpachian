<?php

namespace Models;

class Attributes
{
    /**
     * Get array with attribute names as key, and attributes values as value
     */
    public function getAll()
    {
        return get_object_vars($this);
    }

    /**
     * Get array with attribute names only
     */
    public static function getAttributes()
    {
        return get_class_vars(get_called_class()); //use get_called_class() insted of class_name($this) because it's a static method
    }

    /**
     * Magic __set function
     * Sets values to atribues in class
     */
    /*public function __set($name, $value)
    {
        $this->$name = $value;
    }*/

    /**
     * Improved version that uses class own set methods
     */
    public function __set($name, $value)
    {
        $name = ucfirst($name);
        $name = "set".$name;
        $this->$name($value);
    }
}