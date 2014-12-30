SimplePDO
===================

###### Under Construction

**Simple wrapper around PDO**

###Installation

- Require it into your composer.json
- `"anthonyvipond/simplePdo": "dev-master"`
- `composer update`


###Configuration
- pass in a configuration array

```php

// Laravel style
$dbCreds = [
    'host'      => 'localhost',
    'driver'    => 'mysql',
    'database'  => 'db_name',
    'username'  => 'root',
    'password'  => 'secret',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];

new SimplePdo($dbCreds);
```

###Usage
```php
$pdo = new SimplePdo($configArray);

$pdo->statement('CREATE TABLE users'); // returns the result object

$pdo->select('* FROM users', true)->fetch(); // returns the result object

$pdo->getTotalRows($table);

$pdo->createCompositeIndex($table, $columns)

```

Check out simplePdo.php to find all the methods!