<?php
  /* Formatação do texto que será exibido ao final da partida no campo de modalidade
  e cálculo do total de buracos que será exibido junto com a quantidade de linhas e colunas */
  if($partida['modalidade'] === 'classico') {
    $modalidade = 'Clássica';
  }
  else if($partida['modalidade'] === 'explosivo'){
    $modalidade = 'Explosiva';
  }

  $total = $partida['linhas'] * $partida['colunas'];
?>

<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Fim de partida</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <link rel="stylesheet" href="../../public/assets/css/fim.css">
    <link rel="icon" href="../../public/assets/img/toupeira-favicon.ico">
  </head>

  <body>
    <header class="login-screen">
      <div class="login-screen-art art--jogo">
        <img
          src="../../public/assets/img/emblema-jogo.jpeg" alt="Marreta erguida sobre uma toca, prestes a descer">
      </div>

      <div class="login-screen-title">
        <h1>Fim de partida!</h1>
      </div>
    </header>

    <!-- A utilização de htmlspecialchars evita que possíveis tags HTML no banco de dados cadastradas por engano sejam interpretadas
    pelo navegador, exibindo-as apenas como texto puro-->
    <main class="forms">
      <div class="forms-card fim-card">
        <h2 class="sr-apenas">Resumo da partida encerrada</h2>

        <p class="fim-intro">
          <span id="fim-mensagem">
            <?= htmlspecialchars($partida['motivo_encerramento'], ENT_QUOTES, 'UTF-8') ?>
          </span>
        </p>

        <dl class="resumo">
          <div class="resumo-linha">
            <dt>Modalidade</dt>
            <dd id="fim-modalidade">
              <?= htmlspecialchars($modalidade, ENT_QUOTES, 'UTF-8') ?>
            </dd>
          </div>

          <div class="resumo-linha">
            <dt>Tamanho do tabuleiro</dt>
            <dd id="fim-tabuleiro">
              <?= htmlspecialchars($partida['linhas'] . ' X ' . $partida['colunas'] . ' (' . $total . ' buracos)', ENT_QUOTES, 'UTF-8') ?>
            </dd>
          </div>

          <div class="resumo-linha">
            <dt>Toupeiras acertadas</dt>
            <dd id="fim-acertos">
              <?= htmlspecialchars($partida['toupeiras_acertadas'], ENT_QUOTES, 'UTF-8') ?>
            </dd>
          </div>

          <div class="resumo-linha">
            <dt>Nível atingido</dt>
            <dd id="fim-nivel">
              <?= htmlspecialchars($partida['nivel'], ENT_QUOTES, 'UTF-8') ?>
            </dd>
          </div>

          <div class="resumo-linha resumo-linha--destaque">
            <dt>Pontuação final</dt>
            <dd id="fim-pontuacao">
              <?= number_format((int) $partida['pontuacao'], 0, ',', '.') ?>
            </dd>
          </div>
        </dl>

        <div class="field">
          <a href="jogo.php" class="btn btn--primary">Iniciar nova partida</a>
        </div>

        <div class="field">
          <a href="menu.php" class="btn btn--secondary">Voltar ao menu</a>
        </div>

        <a href="historico.php" class="link-secundario">Ver meu histórico</a>
      </div>
    </main>
  </body>
</html>
