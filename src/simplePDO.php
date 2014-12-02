<?php

namespace SimplePdo;

use SimplePdo\Core\BasePdo;

protected $dbh;

class SimplePdo extends BasePdo {

    public function query(array $params)
    {
        $keys = implode(',', array_keys($params));
        $values = implode(',', $this->escape(array_values($params)));

        foreach ($para as $key => $value) {
            # code...
        }

        $statement = $this->dbh->prepare(' . $keys . ')' . values ' . ' (' . $values . ')';
        $statement->execute($data);
    }

    protected function escape(array $values)
    {
        return array_map('mysqli_real_escape_string', $params);
    }

    protected function select($query, $assoc = false)
    {
        $statement = $this->dbh->query($query);
 
        if ($assoc === true) {
            $statement->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $statement->setFetchMode(PDO::FETCH_OBJ);
        }

        return $statement->fetch();
    }

    protected function insert($table, array $params)
    {
        $keys = implode(',', array_keys($params));

        $bindings = ':' . implode(',:', array_keys($params));

        $sql = 'INSERT INTO ' . $table . ' (' . $keys . ') VALUES (' .  $bindings . ')';
        
        $data = $this->getInsertData($params);

        $query = $this->dbh->prepare($sql);
        
        $q->execute($data);
    }

    protected function getEscapedData($params)
    {
        foreach ($params as $key => $value) {
            $data[':' . $key] = trim($value);
        }

        return $data;
    }
    

}