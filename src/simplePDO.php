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

    public function getTotalRows($table)
    {
        $sql = 'count(*) as count FROM ' . $table;

        return $this->select($sql)->fetch()->count;
    }

    public function getUniqueRows($dupeTable, $columns)
    {
        $sql = 'DISTINCT ' . tickCommaSeperate($columns);
        
        return $this->select('count(' . $sql . ') as uniques FROM ' . $dupeTable)->fetch()->uniques;
    }

    public function getDuplicateRows($table, array $columns)
    {
        $firstColumn = $columns[0];
        $columns = tickCommaSeperate($columns);

        $sql = ('sum(cnt - 1) as count
        FROM
        (
            SELECT ' . $firstColumn . ', count(*) as cnt
            FROM ' . $table . '
            GROUP BY ' . $columns . '
            HAVING count(*) > 1
        ) x');

        return (int) $this->select($sql)->fetch()->count;
    }

    public function tableExists($table)
    {
        try {
            return is_int($this->select('count(*) as count FROM ' . $table)->fetch()->count);
        } catch (PDOException $e) {
            if ($e->getCode() === '42S02') { // 42S02: table not found
                return false;
            }
            
            $this->dumpError($e);
        }
    }

    public function createIndex($table, $column)
    {
        //$this->indexExists($table, $column . '_drt');

        $sql = 'CREATE INDEX ' . $column . '_drt ON ' . ticks($table) . ' (' ticks($column) . ')';

        $this->statement($sql);
    }

    public function indexExists($table, $keyname)
    {
        // $result = $this->dbh->exec('SHOW INDEX FROM ' . $table . ' WHERE Key_name = ' . ticks($keyname));

        // dd($resut);
    }

    public function createCompositeIndex($table, array $columns)
    {
        $indexName = implode('_', $columns);
        $columns = tickCommaSeperate($columns);

        $sql = 'CREATE INDEX ' . $indexName . ' ON ' . $table . '(' . $columns . ')';

        $this->statement($sql);
    }

    public function copyTable($sourceTable, $newTable)
    {
        $sql = 'CREATE TABLE ' . ticks($newTable) . ' LIKE ' . ticks($sourceTable);
        
        $this->statement($sql);
    }

    public function idExists($id, $table)
    {
        $result = $this->select('exists(SELECT 1 FROM ' . $table . ' where id=' . $id . ') as `exists`')->fetch();

        return (bool) $result->exists;
    }

    public function getNextId($id, $table)
    {
        $sql = 'id from ' . $table . ' where id = (select min(id) from ' . $table . ' where id > ' . $id . ')';

        $result = $this->select($sql)->fetch();

        return isset($result->id) ? $result->id : null;
    }

    public function addIntegerColumn($table, $column)
    {
        $sql = 'ALTER TABLE ' . ticks($table) . ' ADD ' . ticks($column) . ' int(11)';

        $this->statement($sql);
    }

}