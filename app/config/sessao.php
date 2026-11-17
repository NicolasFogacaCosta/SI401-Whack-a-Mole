<?php
    /**
     * Config responsável pela sessão PHP e pela proteção das páginas.
     *
     * Todo controller de página restrita começa com:
     *     require_once __DIR__ . '/../config/sessao.php';
     *     exigirLogin();
     * Sem sessão ativa, o usuário é mandado de volta ao login.
     */
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function usuarioLogado(): bool {
        return isset($_SESSION['usuario_id']);
    }

    // Os controllers ficam todos em app/controller, por isso o caminho relativo é fixo
    function exigirLogin(): void {
        if (!usuarioLogado()) {
            header('Location: ../../public/index.php');
            exit;
        }
    }
?>
