<?php
    //função responsável por salvar os dados da partida finalizada no banco de dados e retornar o último ID da partida salva para uso no sistema
    function salvarPartida($pdo, $dados) {
        $stmt = $pdo->prepare(
            "INSERT INTO partidas (usuario_id, linhas, colunas, modalidade, pontuacao, nivel, toupeiras_acertadas, partida_encerrada, motivo_encerramento)
            VALUES (:usuario_id, :linhas, :colunas, :modalidade, :pontuacao, :nivel, :toupeiras_acertadas, :partida_encerrada, :motivo_encerramento)"
        );

        $stmt->execute([
            ':usuario_id' => $dados['usuario_id'],
            ':linhas' => $dados['linhas'],
            ':colunas' => $dados['colunas'],
            ':modalidade' => $dados['modalidade'],
            ':pontuacao' => $dados['pontuacao'],
            ':nivel' => $dados['nivel'],
            ':toupeiras_acertadas' => $dados['toupeiras_acertadas'],
            ':partida_encerrada' => $dados['partida_encerrada'],
            ':motivo_encerramento' => $dados['motivo_encerramento']
        ]);

        return $pdo->lastInsertId();
    }

    /*função responsável por buscar todos os dados da partida, usando como parâmetro o usuário e o id da partida, para que um usuário
    não acesse uma partida que não seja dele*/
    function buscarPartida($pdo, $partidaId, $usuarioId) {
        $stmt = $pdo->prepare(
            "SELECT * FROM partidas
            WHERE id = :id AND usuario_id = :usuario_id"
        );

        $stmt->execute([
            ':id' => $partidaId,
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // função do histórico: só as partidas do usuário da sessão, da mais recente para a mais antiga
    function listarPartidasDoUsuario($pdo, $usuarioId) {
        $stmt = $pdo->prepare(
            "SELECT p.id, u.usuario, p.linhas, p.colunas, p.modalidade, p.pontuacao, p.nivel,
                    p.toupeiras_acertadas, p.jogada_em
            FROM partidas p
            JOIN usuarios u ON u.id = p.usuario_id
            WHERE p.usuario_id = :usuario_id
            ORDER BY p.jogada_em DESC, p.id DESC"
        );

        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // função do ranking global: a melhor partida de cada jogador.
    function listarRanking($pdo, $limite) {
        $stmt = $pdo->prepare(
            "SELECT u.usuario, r.linhas, r.colunas, r.modalidade, r.pontuacao,
                    r.toupeiras_acertadas, r.nivel, r.jogada_em
            FROM (
                SELECT p.*,
                       ROW_NUMBER() OVER (
                           PARTITION BY p.usuario_id
                           ORDER BY p.pontuacao DESC, p.toupeiras_acertadas DESC, p.jogada_em ASC, p.id ASC
                       ) AS ordem_no_jogador
                FROM partidas p
            ) r
            JOIN usuarios u ON u.id = r.usuario_id
            WHERE r.ordem_no_jogador = 1
            ORDER BY r.pontuacao DESC, r.toupeiras_acertadas DESC, r.jogada_em ASC, r.id ASC
            LIMIT :limite"
        );

        // LIMIT precisa receber um inteiro de verdade, por isso o bindValue com PARAM_INT
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>
