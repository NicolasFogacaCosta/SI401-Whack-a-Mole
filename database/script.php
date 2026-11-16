<?php
    /**
     * Script responsável por criar as tabelas no banco de dados.
     * 
     * Este arquivo cria as tabelas necessárias para o funcionamento do sistema, caso elas ainda não existam.
     * As tabelas criadas são: "usuarios" e "partidas".
     */
    try{
        $conn = new PDO("mysql:host=localhost;dbname=whack_a_mole;charset=utf8mb4", "root", "");

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

        $conn->exec($sqlUsuarios);

        $sqlPartidas = "CREATE TABLE IF NOT EXISTS partidas(
        id int unsigned auto_increment primary key,
        usuario_id int unsigned not null,
        linhas int not null,
        colunas int not null,
        modalidade char(10) not null,
        pontuacao int not null,
        nivel int not null,
        toupeiras_acertadas int not null,
        partida_encerrada boolean not null,
        jogada_em datetime not null default current_timestamp,

        foreign key (usuario_id) references usuarios(id)
        )";

        $conn->exec($sqlPartidas);

        echo "Tabelas criadas com sucesso!";
    }
    catch(PDOException $erro){
        echo "Erro ao criar: " . $erro->getMessage();
    }
?>