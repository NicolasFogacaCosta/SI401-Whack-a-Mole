<?php
    /**
     * Controller responsável pelo logout do usuário.
     * 
     * Este arquivo processa a requisição de logout, encerrando a sessão do usuário e redirecionando-o
     * de volta para a página de login.
     */
    session_start();
    $_SESSION = array();
    session_destroy();
    header('Location: ../../public/index.php');
    exit;
?>