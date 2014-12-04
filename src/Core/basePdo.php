<?php

namespace SimplePdo\Core;

use PDO;

abstract class BasePdo {

protected $dbh;
protected $sql;
protected $statement;
protected $bindings = [];

    public function __construct(array $config)
    {
        $this->initHandler($config);
    }

    protected function initHandler(array $config)
    {
        try {
            if ( $config['driver'] !== 'sqlite') {
                $this->dbh = new PDO($config['driver'] . ':host=' . $config['host'] . ';
                    dbname=' . $config['database'] . ';
                    charset=' . $config['charset'], 
                    $config['username'], 
                    $config['password'],
                    [
                        PDO::ATTR_EMULATE_PREPARES => false, 
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ]);
            } else {
                $this->dbh = new PDO('sqlite:' . $config['db_path']);
            }
        } catch(PDOException $e) {
            print($e->getMessage());
        }

        unset($config);
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

    protected function dumpError($error) {
        $backtrace = debug_backtrace();

        dd([
            'message' => $error->getMessage(),
            'line' => $error->getLine(),
            'file' => $error->getFile(),
            'code' => $error->getCode(),
            'last_file' => $backtrace[1]['file'],
            'last_line' => $backtrace[1]['line'],
        ]);
    }
    
}