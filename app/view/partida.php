<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Partida em andamento</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <link rel="stylesheet" href="../../public/assets/css/partida.css">
    <link rel="icon" href="../../public/assets/img/toupeira-favicon.ico">
  </head>

  <body class="tela-partida">
    <!-- Todo o cabecalho e o placar vivem nesta coluna, para o
         tabuleiro ficar com a altura inteira da tela -->
    <aside class="painel">
      <header class="painel-topo">
        <img class="painel-emblema" src="../../public/assets/img/emblema-jogo.jpeg" alt="Marreta erguida sobre uma toca, prestes a descer">

        <div>
          <h1 class="painel-titulo">Whack-a-Mole</h1>
          <p class="painel-sub">Partida em andamento</p>
        </div>
      </header>

      <div class="hud" role="status">
        <div class="hud-item">
          <span class="hud-rotulo">Modalidade</span>
          <strong class="hud-valor" id="hud-modalidade">-</strong>
        </div>

        <div class="hud-item">
          <span class="hud-rotulo">Tempo restante</span>
          <time class="hud-valor hud-valor--tempo" id="hud-tempo" datetime="PT15S">00:15</time>
        </div>

        <div class="hud-item">
          <span class="hud-rotulo">Nível</span>
          <strong class="hud-valor" id="hud-nivel">1</strong>
        </div>

        <div class="hud-item">
          <span class="hud-rotulo">Toupeiras acertadas</span>
          <output class="hud-valor" id="hud-acertos">0</output>
        </div>

        <!-- Pontuacao da partida ate o momento -->
        <div class="hud-item hud-item--pontos">
          <span class="hud-rotulo">Pontuação</span>
          <output class="hud-valor" id="hud-pontuacao">0</output>
        </div>
      </div>

      <div class="nivel">
        <div class="nivel-topo">
          <span id="nivel-titulo">
            Acertos no nível
            <small class="nivel-meta">meta: <strong id="hud-meta">90%</strong></small>
          </span>
          <output class="nivel-percentual" id="hud-percentual">0%</output>
        </div>

        <div class="barra-wrap">
          <progress class="barra" id="hud-barra" value="0" max="100" aria-labelledby="nivel-titulo">0%</progress>
          <span class="barra-marca" id="barra-marca"></span>
        </div>
      </div>

      <p class="painel-rodape">
        <span>Tabuleiro <strong id="hud-dimensoes">-</strong></span>
        <span id="mensagem-jogo" role="status"></span>
      </p>

      <button type="button" id="desistir" class="btn btn--tertiary">Desistir da partida</button>
    </aside>

    <!-- O lado do buraco e calculado no partida.js a partir do espaco livre desta area -->
    <main class="tabuleiro-wrap">
      <div class="tabuleiro" id="tabuleiro" aria-label="Tabuleiro do jogo"></div>
    </main>

    <script src="../../public/assets/js/partida.js" defer></script>
  </body>
</html>
