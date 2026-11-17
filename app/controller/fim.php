<?php
    /**
     * Controller responsável por intermediar o registro de fim da partida e a consulta por qualquer partida.
     *
     * Esse arquivo verifica se o usuário está logado para poder validar informações. A partir disso, importa
     * a conexão com o banco de dados e o model para registro e busca de partidas.
     * Dependendo se o método for POST ou GET recebido ele seguirá dois caminhos:
     * - POST: valida o resultado enviado pelo partida.js (helpers/validacao.php) e grava a partida ligada ao
     * usuário da sessão. Fim normal (meta não atingida) vai para a tela de fim de partida; desistência volta à
     * seleção de modo com um aviso (SPEC, seção 5, decisão 1).
     *
     * - GET: sinaliza uma consulta a uma partida existente. Primeiro verifica se existe id da partida para consulta,
     * depois coleta esse id junto com o id do usuário, e busca a partida no model com essas informações.
     * Se nada for retornado, o sistema volta ao menu principal, caso seja encontrado, isso é exibido na view de fim
     * de partida.
     */
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/partida.php';
    require_once __DIR__ . '/../helpers/validacao.php';
    require_once __DIR__ . '/../helpers/formatacao.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        /* O servidor não confia nos números enviados pelo navegador: validarResultadoPartida confere
        tipos, faixas (tabuleiro 2 a 8, modalidade válida, valores não negativos) e a coerência entre
        pontuação, toupeiras e nível. Se qualquer regra falhar, nada é gravado e o usuário volta ao menu. */
        $resultado = validarResultadoPartida($_POST);

        if($resultado['erro'] !== null) {
            header('Location: menu.php');
            exit;
        }

        $dados = $resultado['dados'];
        // o dono da partida vem sempre da sessão, nunca do formulário
        $dados['usuario_id'] = $_SESSION['usuario_id'];

        $partidaId = salvarPartida($pdo, $dados);

        if($dados['partida_encerrada'] === 0) {
            header('Location: jogo.php?desistencia=1');
            exit;
        }

        header('Location: fim.php?partida_id=' . $partidaId);
        exit;
    }
    else {
        $partidaId = filter_input(INPUT_GET, 'partida_id', FILTER_VALIDATE_INT);

        if($partidaId === false || $partidaId === null || $partidaId < 1) {
            header('Location: menu.php');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];

        $partida = buscarPartida($pdo, $partidaId, $usuarioId);

        if(!$partida) {
            header('Location: menu.php');
            exit;
        }

        require_once __DIR__ . '/../view/fim.php';
    }
?>
