<?php

require __DIR__ . '/vendor/autoload.php';

// example
$db = new \SimplePdo\SimplePdo;

$results = $db->raw('SELECT firstname FROM people LIMIT 3');

dd($results);