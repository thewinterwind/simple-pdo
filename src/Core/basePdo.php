<?php

namespace SimplePdo\Core;

use PDO;

abstract class BasePdo {

protected $dbh;
protected $sql;
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
    
}