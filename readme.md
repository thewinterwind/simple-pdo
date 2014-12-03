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
- Update to your database credentials
- Get your machine hostname by typing `hostname` into your command line

###Usage
```php
$db = new \SimplePdo\SimplePdo;

$db->raw('SELECT * FROM users'); // returns the result object

$db->raw('SELECT * FROM users', true); // returns the result array
```