<?php
    /**
     * Controller responsável por intermediar o registro de fim da partida e a consulta por qualquer partida.
     * 
     * Esse arquivo verifica se o usuário está logado para poder validar informações. A partir disso, importa 
     * a conexão com o banco de dados e o model para registro e busca de partidas.
     * Dependendo se o método for POST ou GET recebido ele seguirá dois caminhos:
     * - POST: cria um array com os dados necessários e envia isso ao model para salvar a partida a ligá-la ao
     * usuário correto, e depois vai a tela de fim de partida informando o ID da partida que foi registrado
     * 
     * - GET: sinaliza uma consulta a uma partida existente. Primeiro verifica se existe id da partida para consulta,
     * depois coleta esse id junto com o id do usuário, e busca a partida no model com essas informações.
     * Se nada for retornado, o sistema volta ao menu principal, caso seja encontrado, isso é exibido na view de fim
     * de partida.
     */
    session_start();

    if(!isset($_SESSION['usuario_id'])) {
        header('Location: ../../public/index.php');
        exit;
    }

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/partida.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        /* Abaixo ocorrem as validações dos campos recebidos para gravação no banco.
        Valida-se os campos numéricos se são int, se não são negativos, se estão dentro do
        intevalo esperado, e campos de texto se estão preenchidos corretamente. Após a validação
        todos os dados validade entram no array de dados para serem guardados no banco. Caso qualquer
        validação falhe, volta para menu.php, saindo desse arquivo sem nada gravado. A mesma
        validação ocorre para o request method get, para validar o id da partida para consulta.*/
        $camposNumericos = [
            'linhas',
            'colunas',
            'pontuacao',
            'nivel',
            'toupeiras_acertadas',
        ];

        $valores = [];

        foreach($camposNumericos as $campo) {
            if(!isset($_POST[$campo])) {
                header ('Location: menu.php');
                exit;
            }

            $valor = filter_var($_POST[$campo], FILTER_VALIDATE_INT);

            if($valor === false) {
                header('Location: menu.php');
                exit;
            }

            $valores[$campo] = $valor;
        }

        if($valores['linhas'] !== $valores['colunas'] || !in_array($valores['linhas'], [2, 3, 4, 5, 6, 7, 8], true)) {
            header('Location: menu.php');
            exit;
        }

        if($valores['pontuacao'] < 0 || $valores['nivel'] < 1 || $valores['toupeiras_acertadas'] < 0) {
            header('Location: menu.php');
            exit;
        }

        if(!isset($_POST['modalidade']) || !in_array($_POST['modalidade'], ['classico', 'explosivo'], true)) {
            header('Location: menu.php');
            exit;
        }

        if(!isset($_POST['motivo_encerramento']) || !is_string($_POST['motivo_encerramento']) || trim($_POST['motivo_encerramento']) === '' || strlen($_POST['motivo_encerramento']) > 100) {
            header('Location: menu.php');
            exit;
        }

        $dados = [
            'usuario_id' => $_SESSION['usuario_id'],
            'linhas' => $valores['linhas'],
            'colunas' => $valores['colunas'],
            'modalidade' => $_POST['modalidade'],
            'pontuacao' => $valores['pontuacao'], 
            'nivel' => $valores['nivel'], 
            'toupeiras_acertadas' => $valores['toupeiras_acertadas'], 
            'partida_encerrada' => 1, 
            'motivo_encerramento' => trim($_POST['motivo_encerramento'])
        ];

        $partidaId = salvarPartida($pdo, $dados);

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