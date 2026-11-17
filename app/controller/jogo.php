<?php
    /**
     * Controller da tela de seleção de modalidade e tabuleiro.
     *
     * Quando o jogador desiste, o fim.php grava a partida e redireciona para cá com
     * ?desistencia=1, para a view exibir o aviso (SPEC, seção 5, decisão 1).
     */
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    $avisoDesistencia = isset($_GET['desistencia']);

    require_once __DIR__ . '/../view/jogo.php';
?>
