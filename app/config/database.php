<?php
    /**
     * Config responssal por realizar a conexão com o banco de dados MariaDB/MySQL (phpMyAdmin do XAMPP).
     * 
     * Este arquivo realiza a conexão com o banco de dados, definindo o host do servidor, nome do banco,
     * usuário e senha, colocando esses dados em uma string, e usando o objeto PDO para realizar a conexão.
     * Caso ocorra algum erro, o catch é responsável por capturar o erro e exibí-lo.
     */
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
    }
    catch(PDOException $erro){
        throw $erro;
    }
?>