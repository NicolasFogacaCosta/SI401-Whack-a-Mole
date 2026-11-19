<?php
    /**
     * Controller do ranking global: a melhor partida de cada jogador, ordenada por pontuação
     * (desempate por toupeiras acertadas e depois por quem fez primeiro).
     */
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/partida.php';
    require_once __DIR__ . '/../helpers/formatacao.php';

    // Quantidade de jogadores exibidos (SPEC, seção 5, decisão 4)
    const TAMANHO_RANKING = 10;

    $ranking = listarRanking($pdo, TAMANHO_RANKING);
    $usuarioAtual = $_SESSION['usuario'];

    require_once __DIR__ . '/../view/ranking.php';
?>
