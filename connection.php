<?php

$dsn = 'mysql:dbname=uke-appointment;host=localhost';
$username = 'root';
$password = '';

try {
    $connection = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo $e->getMessage();
}
