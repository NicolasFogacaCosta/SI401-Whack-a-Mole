<?php

$host = '127.0.0.1';
$database = 'whack_a_mole';
$username = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";

try{
    $pdo = new PDO($dsn, $username, $password);

    $pdo ->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    echo 'Conectado ao Banco Whack-A-Mole';
}catch(PDOException $erro){
    echo 'Connection failed: '. $erro ->getMessage();
}