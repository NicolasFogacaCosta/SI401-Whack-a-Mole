<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Ranking</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <link rel="stylesheet" href="../../public/assets/css/ranking.css">
    <link rel="icon" href="../../public/assets/img/toupeira-favicon.ico">
  </head>

  <body>
    <header class="login-screen">
      <div class="login-screen-art">
        <img src="../../public/assets/img/emblema-ranking.jpeg" alt="thumb-art">
      </div>

      <div class="login-screen-title">
        <h1>Maiores Batedores de Toupeira</h1>
      </div>
    </header>

    <main class="forms">
      <div class="forms-card forms-card--largo ranking-card">
        <div class="tabela-wrap">
          <table class="ranking">
            <caption>
              Melhor partida de cada jogador, por pontuação (desempate: mais toupeiras, depois quem fez primeiro)
            </caption>

            <thead>
              <tr>
                <th scope="col" class="col-pos">#</th>
                <th scope="col">Jogador</th>
                <th scope="col">Modalidade</th>
                <th scope="col">Tabuleiro</th>
                <th scope="col" class="col-num">Nível</th>
                <th scope="col" class="col-acertos">Toupeiras</th>
                <th scope="col" class="col-num">Pontuação</th>
              </tr>
            </thead>

            <tbody>
              <?php if ($ranking === []): ?>
                <tr class="tabela-vazia">
                  <td colspan="7">Nenhuma partida registrada ainda. Seja o primeiro!</td>
                </tr>
              <?php endif; ?>

              <?php foreach ($ranking as $posicao => $linha): ?>
                <tr>
                  <td class="col-pos"><?= $posicao + 1 ?></td>
                  <td>
                    <?php if ($linha['usuario'] === $usuarioAtual): ?>
                      <strong><?= e($linha['usuario']) ?></strong> <small>(você)</small>
                    <?php else: ?>
                      <?= e($linha['usuario']) ?>
                    <?php endif; ?>
                  </td>
                  <td><?= e(nomeModalidade($linha['modalidade'])) ?></td>
                  <td><?= e($linha['linhas'] . ' x ' . $linha['colunas']) ?></td>
                  <td class="col-num"><?= e($linha['nivel']) ?></td>
                  <td class="col-acertos"><?= formatarNumero($linha['toupeiras_acertadas']) ?></td>
                  <td class="col-num"><?= formatarNumero($linha['pontuacao']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="field">
          <a href="edicao.php" class="btn btn--primary">Edite seu Cadastro</a>
        </div>

        <a href="menu.php" class="link-secundario">Voltar ao Menu</a>
      </div>
    </main>
  </body>
</html>
