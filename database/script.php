<?php

try{
    $conn = new PDO("mysql:host=localhost;dbname=whack_a_mole;charset=utf8mb4", "root", "");

    $sql = "CREATE TABLE IF NOT EXISTS usuarios(
    id int auto_increment primary key,
    nome_completo varchar(150) not null,
    data_nascimento date not null,
    cpf char(14) not null unique,
    telefone char(15) not null,
    email varchar(150) not null unique,
    usuario varchar(50) unique,
    senha varchar(250) not null,
    criado_em datetime not null default current_timestamp
    )";

    $conn->exec($sql);
    echo "Tabela criada com sucesso!";
}catch(PDOException $erro){
    echo "Erro ao criar: " . $erro->getMessage();
}