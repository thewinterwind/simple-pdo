SimplePDO
===================

###### Under Construction

**Simple wrapper around PDO**

###Installation

- Require it into your composer.json
- `"anthonyvipond/simplePdo": "dev-master"`
- `composer update`


###Configuration
- `cp config.php.sample config.php`
- or require a database file from your project
- Update to your database credentials

###Usage
```php
$pdo = new SimplePdo(require 'database.php');

$pdo->statement('CREATE TABLE users'); // returns the result object

$pdo->select('* FROM users', true)->fetch(); // returns the result object

$pdo->getTotalRows($table);

$pdo->createCompositeIndex($table, $columns)

```

Check out simplePdo.php to find all the methods!