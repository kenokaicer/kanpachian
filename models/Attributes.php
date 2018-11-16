<?php

namespace Models;

class Attributes
{
    /**
     * Get array with attribute names as key, and attributes values as value.
     * Only return protected attributes, useful for returning only what you want
     * in this case only attributes that are not objects
     *
     *    Public members: member_name
     *    Protected memebers: \0*\0member_name
     *    Private members: \0Class_name\0member_name
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
    public function __set($attribute, $value)
    {
        $attribute = ucfirst($attribute);
        $attribute = "set".$attribute;
        $this->$attribute($value);
    }
}