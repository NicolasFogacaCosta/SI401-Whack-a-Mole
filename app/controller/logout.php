<?php
    /**
     * Controller responsável pelo logout do usuário.
     *
     * Limpa os dados da sessão, apaga o cookie de sessão do navegador, destrói a sessão
     * e redireciona de volta para a página de login.
     */
    require_once __DIR__ . '/../config/sessao.php';

    $_SESSION = array();

    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
    }

    session_destroy();
    header('Location: ../../public/index.php');
    exit;
?>
