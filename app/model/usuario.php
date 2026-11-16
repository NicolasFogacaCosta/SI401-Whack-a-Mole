<?php
    // funcao para cadastrar um novo usuário no banco de dados
    function cadastrarUsuario($pdo, $dados) {
        $stmt = $pdo -> prepare(
        "INSERT INTO usuarios(nome_completo, data_nascimento, cpf, telefone, email, usuario, senha_hash)
        VALUES(:nome_completo, :data_nascimento, :cpf, :telefone, :email, :usuario, :senha_hash)"
        );

        $stmt->execute([
            ':nome_completo' => $dados['nome_completo'],
            ':data_nascimento' => $dados['data_nascimento'],
            ':cpf' => $dados['cpf'],
            ':telefone' => $dados['telefone'],
            ':email' => $dados['email'],
            ':usuario' => $dados['usuario'],
            ':senha_hash' => $dados['senha_hash']
        ]);
    }

    // funcao para verificar se o usuario existe no banco de dados e retornar os dados do usuario
    function loginUsuario($pdo, $dados) {
        $stmt = $pdo-> prepare (
        "SELECT id, usuario, senha_hash
        FROM usuarios
        WHERE usuario = :usuario"
        );

        $stmt->execute([
            ':usuario' => $dados['usuario']
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>