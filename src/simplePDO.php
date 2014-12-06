<?php

namespace SimplePdo;

use PDO, Exception, PDOException;
use SimplePdo\Core\BasePdo;

class SimplePdo extends BasePdo {

    protected $dbh;

    public function select($query, $assoc = false)
    {
        $this->statement = $this->dbh->query('SELECT ' . $query);
 
        if ($assoc === true) {
            $this->statement->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $this->statement->setFetchMode(PDO::FETCH_OBJ);
        }

        return $this;
    }

    public function fetch()
    {
        try {
            return $this->statement->fetch();
        } catch (Exception $e) {
            $this->dumpError($e);
        }
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
        try {
            return $this->dbh->exec($statement);
        } catch (\PDOException $e) {
            $this->dumpError($e);
        }
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

    public function exec($sql, array $bindings = array()) {
        if ( ! empty($bindings)) {
            try {
                return $this->dbh->prepare($sql)->execute($bindings);
            } catch (Exception $e) {
                $this->dumpError($e);
            }
        }

        try {
            return $this->dbh->exec($sql);
        } catch (Exception $e) {
            $this->dumpError($e);
        }
    }

    public function isMySqlKeyword($word)
    {
        $keywords = [
            'long', 'select'
        ];

        return in_array($word, $keywords);
    }

    public function tableExists($sql)
    {
        try {
            return $this->select($sql)->fetch()->rows;
        } catch (PDOException $e) {
            if ($e->getCode() === '42S02') { // 42S02: table not found
                return false;
            }
            
            $this->dumpError($e);
        }
    }

    public function idExists($id, $table)
    {
        $result = $this->select('exists(SELECT 1 FROM ' . $table . ' where id=' . $id . ') as `exists`')->fetch();

        return (bool) $result->exists;
    }

    protected function getNextId($id, $table)
    {
        $sql = 'id from ' . $table . ' where id = (select min(id) from ' . $table . ' where id > ' . $id . ')';

        $result = $this->select($sql)->fetch();

        return isset($result) ? $result->id : null;
    }

    protected function addNewColumn($table, $column)
    {
        $sql = 'ALTER TABLE ' . $table . ' ADD ' . $column . ' int(11)';

        $this->dbh->statement($sql);
    }

}