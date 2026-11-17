<?php
    /**
     * Controller responsável pelo login do usuário.
     *
     * Este arquivo processa as requisições de login, verificando as credenciais fornecidas pelo usuário.
     * Se as credenciais forem válidas, o id da sessão é regenerado (evita fixação de sessão) e o usuário
     * é redirecionado para o menu principal. Caso contrário, ele é redirecionado de volta para a página
     * de login com uma mensagem de erro.
     */
    require_once __DIR__ . '/../config/sessao.php';
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/usuario.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $usuario = is_string($_POST['usuario'] ?? null) ? trim($_POST['usuario']) : '';
        $senha = is_string($_POST['senha'] ?? null) ? $_POST['senha'] : '';

        $usuarioEncontrado = $usuario === '' ? false : loginUsuario($pdo, ['usuario' => $usuario]);

        if ($usuarioEncontrado === false || !password_verify($senha, $usuarioEncontrado['senha_hash'])) {
            header('Location: ../../public/index.php?erro=1');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuarioEncontrado['id'];
        $_SESSION['usuario'] = $usuarioEncontrado['usuario'];

        header('Location: menu.php');
        exit;
    }
    else {
        header('Location: ../../public/index.php');
        exit;
    }
?>
