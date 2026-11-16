<?php
    /**
     * Controller responsável pelo login do usuário.
     * 
     * Este arquivo processa as requisições de login, verificando as credenciais fornecidas pelo usuário.
     * Se as credenciais forem válidas, o usuário é redirecionado para o menu principal. Caso contrário,
     * ele é redirecionado de volta para a página de login com uma mensagem de erro.
     */
    session_start();
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/usuario.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $usuarioEncontrado = loginUsuario($pdo, $_POST);

        if ($usuarioEncontrado === false || !password_verify($_POST['senha'], $usuarioEncontrado['senha_hash'])) {
            header('Location: ../../public/index.php?erro=1');
            exit;
        }
        else {
            $_SESSION['usuario_id'] = $usuarioEncontrado['id'];
            $_SESSION['usuario'] = $usuarioEncontrado['usuario'];
        }

        header('Location: ../view/menu.php');
        exit;
    }
    else {
        header('Location: ../../public/index.php');
        exit;
    }
?>