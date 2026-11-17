<?php
    /**
     * Controller da partida em andamento.
     *
     * Confere no servidor os parâmetros escolhidos na tela de jogo antes de abrir o tabuleiro.
     * Se alguém editar a URL com um tamanho ou modalidade inválidos, volta para a seleção.
     */
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    require_once __DIR__ . '/../helpers/validacao.php';

    $linhas = filter_input(INPUT_GET, 'linhas', FILTER_VALIDATE_INT);
    $colunas = filter_input(INPUT_GET, 'colunas', FILTER_VALIDATE_INT);
    $modo = filter_input(INPUT_GET, 'modo');

    if (!tabuleiroValido($linhas, $colunas) || !in_array($modo, MODALIDADES, true)) {
        header('Location: jogo.php');
        exit;
    }

    require_once __DIR__ . '/../view/partida.php';
?>
