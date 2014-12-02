<?php

namespace SimplePdo\Core;

use PDO;

protected $dbh;

abstract class basePdo {

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        require '../vendor/autoload.php';

        $this->setEnvironment();

        $this->initHandler();
    }

    protected function initHandler()
    {
        try {
            if ( $this->env['dbms'] !== 'sqlite') {
                $this->dbh = new PDO($this->env['dbms'] . ':host=' . $this->env['db_host'] . ';
                    dbname=' . $this->env['db_name'] . ';
                    charset=' . $this->env['charset'], 
                    $this->env['db_user'], 
                    $this->env['db_pass'] 
                    [
                        PDO::ATTR_EMULATE_PREPARES => false, 
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);
            } else {
                $this->dbh = new PDO('sqlite:' . $this->env['db_path']);
            }
        } catch(PDOException $e) {
            print($e->getMessage());
        }

        unset($this->env);
    }
    
    protected function getEnvironment()
    {
        $config = require '..config.php';

        foreach ($config as $environment) {
            if ($environment['hostname'] === gethostname()) {
                $this->env = $environment;
                return;
            }
        }

        print 'You must set your hostname and other config in config.php'; die;
    }
}