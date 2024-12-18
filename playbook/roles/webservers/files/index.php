<?php

$user = 'root';
$pass = 'root';

$dsn = 'mysql:host=mysqldb;dbname=information_schema;charset=utf8';
$pdo = new PDO($dsn, $user, $pass);

$first = $pdo->query('select * from tables');
$row = $first->fetch();

print_r($row);