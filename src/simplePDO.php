<?php

namespace SimplePdo;

use PDO;
use SimplePdo\Core\BasePdo;

class SimplePdo extends BasePdo {

protected $dbh;

    public function raw($query, $assoc = false)
    {
        $statement = $this->dbh->query($query);
 
        if ($assoc === true) {
            $statement->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $statement->setFetchMode(PDO::FETCH_OBJ);
        }

        return $statement->fetchAll();
    }

    protected function insert($table, array $params)
    {
        $keys = implode(',', array_keys($params));

        $bindings = ':' . implode(',:', array_keys($params));

        $sql = 'INSERT INTO ' . $table . ' (' . $keys . ') VALUES (' .  $bindings . ')';
        
        $data = $this->getInsertData($params);

        $query = $this->dbh->prepare($sql);
        
        return $query->execute($data);
    }

    protected function getInsertData($params)
    {
        foreach ($params as $key => $value) {
            $data[':' . $key] = trim($value);
        }

        return $data;
    }

}