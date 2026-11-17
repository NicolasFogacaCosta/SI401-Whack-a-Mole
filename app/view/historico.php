<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Histórico</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <link rel="stylesheet" href="../../public/assets/css/historico.css">
    <link rel="icon" href="../../public/assets/img/toupeira-favicon.ico">
  </head>

  <body>
    <header class="login-screen">
      <div class="login-screen-art">
        <img src="../../public/assets/img/emblema-historico.jpeg" alt="thumb-art">
      </div>

      <div class="login-screen-title">
        <h1>Registros de Confronto!</h1>
      </div>
    </header>

    <main class="forms">
      <div class="forms-card forms-card--largo historico-card">
        <div class="tabela-wrap">
          <table class="ranking">
            <caption>
              Todas as partidas disputadas por <?= e($nomeJogador) ?>
            </caption>

            <thead>
              <tr>
                <th scope="col">Jogador</th>
                <th scope="col">Tabuleiro</th>
                <th scope="col">Modalidade</th>
                <th scope="col" class="col-num">Pontuação</th>
                <th scope="col" class="col-num">Nível</th>
                <th scope="col">Data / Hora</th>
              </tr>
            </thead>

            <tbody>
              <?php if ($partidas === []): ?>
                <tr class="tabela-vazia">
                  <td colspan="6">Você ainda não jogou nenhuma partida.</td>
                </tr>
              <?php endif; ?>

              <?php foreach ($partidas as $partida): ?>
                <tr>
                  <td><?= e($partida['usuario']) ?></td>
                  <td><?= e($partida['linhas'] . ' x ' . $partida['colunas']) ?></td>
                  <td>
                    <?= e(nomeModalidade($partida['modalidade'])) ?>
                    <?php if ((int) $partida['partida_encerrada'] === 0): ?>
                      <small>(desistência)</small>
                    <?php endif; ?>
                  </td>
                  <td class="col-num"><?= formatarNumero($partida['pontuacao']) ?></td>
                  <td class="col-num"><?= e($partida['nivel']) ?></td>
                  <td>
                    <time datetime="<?= e(dataHoraIso($partida['jogada_em'])) ?>"><?= e(formatarDataHora($partida['jogada_em'])) ?></time>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <a href="menu.php" class="link-secundario">Voltar ao Menu</a>
      </div>
    </main>
  </body>
</html>
