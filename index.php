<?php

// example
$db = new \SimplePdo\SimplePdo;

$result = $db->raw('SELECT * FROM people LIMIT 3');

dd($result);