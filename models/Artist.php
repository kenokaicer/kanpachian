<?php
namespace Models;

class Artist 
//implements JsonSerializable
{
    private $id;
    private $name;
    private $lastname;
    private $image;

    public function getname()
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;

        return $this;
    }

    public function getlastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }
  
    /*public function jsonSerialize(){ //si se puediera implementar interfaz JsonSerializable
    return
    //[
        array(
    'name'   => $this->getname(),
    'lastname' => $this->getlastname()
        );
    //];
    }*/

    //The Bad
    public function toJson() //alternative to JsonSerializable interface
    {
        return json_encode(
            [
                'name' => $this->getname(),
                'lastname' => $this->getlastname(),
            ]
        );
    }

    /**
     * Returns all attributes as an array, used for edit dao
     */
    public function getAll() {
        return get_object_vars($this);
    }


	public function getImage()
	{
		return $this->image;
	}

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
