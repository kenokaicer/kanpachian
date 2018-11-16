<?php namespace Dao;

use Exception as Exception;

/*class SingletonDao
{
    protected static $instance = null;

    public static function getInstance()
        {
            $cls = get_called_class(); // late-static-bound class name
            if (!isset(self::$instance[$cls])) {
                self::$instance[$cls] = new static;
            }
            return self::$instance[$cls];
        }
}*/

class SingletonDao
{
    private static $instances = array();
    protected function __construct() {}
    protected function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        if (!isset(self::$instances[static::class])) {
            self::$instances[static::class] = new static;
        }
        return self::$instances[static::class];
    }


     public function lastInsertId() // parent::connection();
    {
        try {
            $query = "SELECT LAST_INSERT_Id()";

            $resultSet = $this->connection->Execute($query);

            $row = reset($resultSet); //gives first object of array
            $id = reset($row); //get value of previous first object
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ", Error getting last insert id. " . $ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ", Error getting last insert id. " . $ex->getMessage());
            return;
        }

        return $id;
    }
}

/*abstract class SingletonDao
{
    private static $_instances = [];

    protected function __construct()
    {
        //Prevent construction of this class
    }

    final public static function getInstance(){
        
        self::$_instances[static::class] = self::$_instances[static::class] ?? new static();
        return self::$_instances[static::class];
    }

    final private function __clone() {}

    final private function __wakeup() {}
}*/
