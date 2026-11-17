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
?>