<?php
    /**
     * Controller do histórico: lista só as partidas do usuário logado.
     * O id vem da sessão, nunca da URL, então não há como ver o histórico de outra pessoa.
     */
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/partida.php';
    require_once __DIR__ . '/../helpers/formatacao.php';

    $nomeJogador = $_SESSION['usuario'];
    $partidas = listarPartidasDoUsuario($pdo, $_SESSION['usuario_id']);

    require_once __DIR__ . '/../view/historico.php';
?>
