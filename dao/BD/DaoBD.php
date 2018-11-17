<?php

namespace Dao\BD;

class DaoBD
{
    public function lastInsertId()
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