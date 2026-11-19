<?php
    /**
     * Script responsável por criar as tabelas no banco de dados.
     * 
     * Este arquivo cria as tabelas necessárias para o funcionamento do sistema, caso elas ainda não existam.
     * As tabelas criadas são: "usuarios" e "partidas".
     * Com o require_once é possível reutilizar o arquivo de conexão de banco e o objeto PDO, evitando repetição
     * de código.
     */
    
    $host = '127.0.0.1';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host=$host;charset=utf8mb4";

    try{
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlDatabase = "CREATE DATABASE IF NOT EXISTS `whack_a_mole` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $pdo->exec($sqlDatabase);

        $pdo->exec("USE `whack_a_mole`");
        
        $sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios(
            id int unsigned auto_increment primary key,
            nome_completo varchar(150) not null,
            data_nascimento date not null,
            cpf char(14) not null unique,
            telefone char(15) not null,
            email varchar(150) not null unique,
            usuario varchar(50) not null unique,
            senha_hash varchar(250) not null,
            criado_em datetime not null default current_timestamp
        )";

        $pdo->exec($sqlUsuarios);

        $sqlPartidas = "CREATE TABLE IF NOT EXISTS partidas(
            id int unsigned auto_increment primary key,
            usuario_id int unsigned not null,
            linhas int not null,
            colunas int not null,
            modalidade enum('classico', 'explosivo') not null,
            pontuacao int not null,
            nivel int not null,
            toupeiras_acertadas int not null,
            partida_encerrada boolean not null,
            motivo_encerramento varchar(100) not null,
            jogada_em datetime not null default current_timestamp,

            foreign key (usuario_id) references usuarios(id)
        )";

        $pdo->exec($sqlPartidas);

        echo "Tabelas criadas com sucesso!";
    }
    catch(PDOException $erro){
        echo "Erro ao criar: " . $erro->getMessage();
    }
?>
