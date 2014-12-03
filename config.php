<?php

return [
    'local' => [
        'dbms'     => 'mysql',
        'hostname' => 'homestead', // type `hostname` in your command line
        'db_host'  => exec('netstat -rn | grep "^0.0.0.0 " | cut -d " " -f10'),
        'db_name'  => 'testdb',
        'db_user'  => 'root',
        'db_pass'  => '',
        'charset'  => 'utf8',
    ],
    'staging' => [
        'dbms'     => 'mysql',
        'hostname' => '',
        'db_host'  => '',
        'db_name'  => '',
        'db_user'  => '',
        'db_pass'  => '',
        'charset'  => 'utf8',
        'db_path'  => '/path/to/database.db', // sqlite only
    ],
    'production' => [
        'dbms'     => 'mysql',
        'hostname' => '',
        'db_host'  => '',
        'db_name'  => '',
        'db_user'  => '',
        'db_pass'  => '',
        'charset'  => 'utf8',
        'db_path'  => '/path/to/database.db', // sqlite only
    ],
];