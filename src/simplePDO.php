<?php

namespace SimplePdo;

use PDO;
use SimplePdo\Core\BasePdo;

class SimplePdo extends BasePdo {

protected $dbh;

    public function select($query, $assoc = false)
    {
        $statement = $this->dbh->query('SELECT ' . $query);
 
        if ($assoc === true) {
            $statement->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $statement->setFetchMode(PDO::FETCH_OBJ);
        }

        return $statement;
    }

    public function insert($table, array $params)
    {
        $placeholders = $this->placeholders($params);

        $this->sql = 'INSERT INTO ' . $table . ' (' . $keys . ') VALUES (' .  $placeholders . ')';
        
        $this->setBindings($params);

        return $this;
    }

    public function update($table, array $params)
    {
        $this->sql = $this->getUpdateSql($table, $params);

        $statement = $this->bindValues($statement, $params);

        return $statement->execute();
    }

    public function statement($statement)
    {
        return $this->dbh->exec($statement);
    }

    protected function placeholders(array $params)
    {
        $keys = implode(',', array_keys($params));

        return ':' . implode(',:', array_keys($params));
    }

    public function where(array $params, $operator = '=')
    {
        $this->sql .= ' WHERE ';

        foreach ($params as $value) {
            $this->sql .= $value . ' ' . $operator . ' :' . $value . ' AND ';
        }

        $this->sql = rtrim($this->sql, ' AND ');

        return $this;
    }

    public function limit($amount, $offset = null)
    {
        $this->sql .= ' LIMIT ' . intval($amount);

        return $this;
    }

    public function exec() {
        return $this->dbh->prepare($this->sql)->execute($this->bindings);
    }

    protected function bindValues($statement, array $params)
    {
        foreach ($params as $key => $value)
        {
            $statement->bindValue(':' . $key, $value);
        }

        return $statement;
    }

    protected function setBindings($params)
    {
        foreach ($params as $key => $value) {
            $this->bindings[':' . $key] = trim($value);
        }
    }

    protected function getUpdateSql($table, array $params)
    {
        $sql = 'UPDATE `' . $table . '` SET ';

        foreach ($params as $key => $value) {
            $sql .= '`' . $key . '` = :' . $key . ', ';
        }

        return rtrim($sql, ', ');
    }

}