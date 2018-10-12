<?php
namespace Dao\BD;

use Dao\SingletonDao as SingletonDao;
use PDOException as PDOException;
use \Exception as Exception;
use \PDO as PDO;

class Connection extends SingletonDao
{
    private $pdo = null;
    private $pdoStatement = null;

    protected function __construct()
    {
        try
        {
            $this->pdo = new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function execute($query, $parameters = array())
    {
        try
        {
            $this->prepare($query);

            foreach ($parameters as $parameterName => $value) {
                $this->pdoStatement->bindParam(":" . $parameterName, $parameters[$parameterName]);
            }

            $this->pdoStatement->execute();

            return $this->pdoStatement->fetchAll();
        } catch (PDOException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function executeNonQuery($query, $parameters)
    {   
        try
        {
            $this->prepare($query);
            
            foreach ($parameters as $parameterName => $value) {
                $this->pdoStatement->bindParam(":" . $parameterName, $parameters[$parameterName]);
            }

            $this->pdoStatement->execute();

            return $this->pdoStatement->rowCount();
        } catch (PDOException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function prepare($query)
    {
        try
        {
            $this->pdoStatement = $this->pdo->prepare($query);
        } catch (PDOException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
