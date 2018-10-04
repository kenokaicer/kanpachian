<?php namespace Dao\BD;

use PDO as PDO;
use PDOException as PDOException;

class Connection
{

    # Métodos

    public function connect()
    {
        try
        {
            $p = new \PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME.";charset=utf8", DB_USER, DB_PASS);
            $p->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $p;
        } catch (PDOException $e) {
            echo $this->e->getMessage();
            die();
        }
    }
}
