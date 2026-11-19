<?php
    // funcao para cadastrar um novo usuário no banco de dados
    function cadastrarUsuario($pdo, $dados) {
        $stmt = $pdo->prepare(
        "INSERT INTO usuarios (nome_completo, data_nascimento, cpf, telefone, email, usuario, senha_hash)
        VALUES (:nome_completo, :data_nascimento, :cpf, :telefone, :email, :usuario, :senha_hash)"
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
        $stmt = $pdo->prepare (
        "SELECT id, usuario, senha_hash
        FROM usuarios
        WHERE usuario = :usuario"
        );

        $stmt->execute([
            ':usuario' => $dados['usuario']
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // funcao para buscar os dados exibidos na tela de edição (nunca devolve o hash da senha)
    function buscarUsuarioPorId($pdo, $usuarioId) {
        $stmt = $pdo->prepare(
            "SELECT id, nome_completo, data_nascimento, cpf, telefone, email, usuario
            FROM usuarios
            WHERE id = :id"
        );

        $stmt->execute([':id' => $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* funcao que devolve quais campos únicos (cpf, email, usuario) já pertencem a outra conta.
    Em $ignorarId vai o id do próprio usuário na edição, para ele não colidir consigo mesmo.
    Campos passados como null não são verificados. */
    function buscarCamposDuplicados($pdo, $cpf, $email, $usuario, $ignorarId = 0) {
        $stmt = $pdo->prepare(
            "SELECT cpf, email, usuario
            FROM usuarios
            WHERE (cpf = :cpf OR email = :email OR usuario = :usuario)
            AND id <> :ignorar"
        );

        $stmt->execute([
            ':cpf' => $cpf,
            ':email' => $email,
            ':usuario' => $usuario,
            ':ignorar' => $ignorarId
        ]);

        $duplicados = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            if ($cpf !== null && $linha['cpf'] === $cpf) {
                $duplicados['cpf'] = true;
            }
            // e-mail e usuário são comparados sem diferenciar maiúsculas, como a collation do banco
            if ($email !== null && strcasecmp($linha['email'], $email) === 0) {
                $duplicados['email'] = true;
            }
            if ($usuario !== null && strcasecmp($linha['usuario'], $usuario) === 0) {
                $duplicados['usuario'] = true;
            }
        }

        return array_keys($duplicados);
    }

    /* funcao de edição: o UPDATE só conhece nome, telefone, e-mail e senha. Data de nascimento,
    CPF e usuário não aparecem no SQL, então não há como alterá-los por aqui.
    Com senha_hash null, a senha atual é mantida. */
    function atualizarUsuario($pdo, $usuarioId, $dados) {
        $sql = "UPDATE usuarios
            SET nome_completo = :nome_completo, telefone = :telefone, email = :email";

        $parametros = [
            ':nome_completo' => $dados['nome_completo'],
            ':telefone' => $dados['telefone'],
            ':email' => $dados['email'],
            ':id' => $usuarioId
        ];

        if ($dados['senha_hash'] !== null) {
            $sql .= ", senha_hash = :senha_hash";
            $parametros[':senha_hash'] = $dados['senha_hash'];
        }

        $stmt = $pdo->prepare($sql . " WHERE id = :id");
        $stmt->execute($parametros);
    }
?>
