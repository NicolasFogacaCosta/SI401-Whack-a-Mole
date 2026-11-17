(function () {
  "use strict";

  const NIVEL_DURACAO = 15;
  const META_ACERTOS = 90;
  const INTERVALO_APARECIMENTO = 700;
  const TEMPO_VISIVEL_INICIAL = 1200;
  const REDUCAO_POR_NIVEL = 100;
  const PONTOS_TOUPEIRA = 100;

  /* Medidas do tabuleiro: folga entre buracos, espessura da borda
     e teto do lado do buraco, para um tabuleiro 2x2 nao virar
     duas crateras ocupando a tela inteira. */
  const FOLGA = 8;
  const BORDA = 3;
  const LADO_MAXIMO = 150;

  /* O tabuleiro e sempre quadrado, em um dos tamanhos oferecidos
     na tela de nova partida */
  const TAMANHOS_VALIDOS = [2, 3, 4, 5, 6, 7, 8];

  const params = new URLSearchParams(window.location.search);
  const linhas = Number(params.get("linhas"));
  const colunas = Number(params.get("colunas"));
  const modo = params.get("modo");

  if (linhas !== colunas || !TAMANHOS_VALIDOS.includes(linhas) || !["classico", "explosivo"].includes(modo)) {
    window.location.replace("jogo.php");
    return;
  }

  const tabuleiro = document.querySelector("#tabuleiro");
  const tabuleiroArea = document.querySelector(".tabuleiro-wrap");
  const hudModalidade = document.querySelector("#hud-modalidade");
  const hudTempo = document.querySelector("#hud-tempo");
  const hudNivel = document.querySelector("#hud-nivel");
  const hudAcertos = document.querySelector("#hud-acertos");
  const hudPontuacao = document.querySelector("#hud-pontuacao");
  const hudPercentual = document.querySelector("#hud-percentual");
  const hudBarra = document.querySelector("#hud-barra");
  const hudMeta = document.querySelector("#hud-meta");
  const hudDimensoes = document.querySelector("#hud-dimensoes");
  const barraMarca = document.querySelector("#barra-marca");
  const mensagem = document.querySelector("#mensagem-jogo");
  const desistir = document.querySelector("#desistir");

  const buracos = [];
  const estado = {
    nivel: 1,
    tempoRestante: NIVEL_DURACAO,
    toupeirasAcertadas: 0,
    pontuacao: 0,
    exibidosNoNivel: 0,
    pontosNoNivel: 0,
    finalizado: false,
    temporizadores: []
  };

  hudModalidade.textContent = modo === "explosivo" ? "Explosiva" : "Clássica";
  hudDimensoes.textContent = linhas + " x " + colunas + " (" + linhas * colunas + " buracos)";
  hudMeta.textContent = META_ACERTOS + "%";
  barraMarca.style.left = META_ACERTOS + "%";

  for (let indice = 0; indice < linhas * colunas; indice += 1) {
    const buraco = document.createElement("button");
    buraco.type = "button";
    buraco.className = "buraco";
    buraco.dataset.estado = "vazio";
    buraco.setAttribute("aria-label", "Buraco " + (indice + 1) + ", vazio");
    buraco.addEventListener("click", function () {
      acertarBuraco(buraco);
    });
    tabuleiro.appendChild(buraco);
    buracos.push(buraco);
  }

  /* Maior lado de buraco que cabe na area livre com essa folga */
  function ladoQueCabe(folga) {
    const area = tabuleiroArea.getBoundingClientRect();
    const larguraUtil = area.width - 2 * folga - 2 * BORDA - folga * (colunas - 1);
    const alturaUtil = area.height - 2 * folga - 2 * BORDA - folga * (linhas - 1);

    return Math.max(1, Math.floor(Math.min(larguraUtil / colunas, alturaUtil / linhas, LADO_MAXIMO)));
  }

  /* Buraco pequeno pede folga menor, senao o espaco vai todo para o vao */
  function folgaPara(lado) {
    return lado >= 48 ? FOLGA : lado >= 24 ? 4 : 2;
  }

  /* Mede a area livre deixada pelo layout e calcula o lado do buraco,
     para o tabuleiro inteiro caber na tela de 4 ate 64 posicoes.
     Duas passadas: a primeira estima o lado, a segunda refaz a conta
     ja com a folga correspondente aquele tamanho de buraco. */
  function dimensionarTabuleiro() {
    let folga = FOLGA;
    let lado = ladoQueCabe(folga);

    for (let passada = 0; passada < 2; passada += 1) {
      folga = folgaPara(lado);
      lado = ladoQueCabe(folga);
    }

    tabuleiro.style.setProperty("--colunas", String(colunas));
    tabuleiro.style.setProperty("--lado", lado + "px");
    tabuleiro.style.setProperty("--folga", folga + "px");
  }

  dimensionarTabuleiro();
  window.addEventListener("resize", dimensionarTabuleiro);

  function registrarTemporizador(funcao, atraso) {
    const id = window.setTimeout(funcao, atraso);
    estado.temporizadores.push(id);
    return id;
  }

  function limparTemporizadores() {
    estado.temporizadores.forEach(window.clearTimeout);
    estado.temporizadores = [];
  }

  /* Devolve todos os buracos ao estado vazio. Como a troca de nivel
     cancela os temporizadores pendentes, sem isso um buraco poderia
     ficar preso em "acerto" e sair do sorteio pelo resto da partida. */
  function limparBuracos() {
    buracos.forEach(function (buraco, indice) {
      buraco.dataset.estado = "vazio";
      buraco.setAttribute("aria-label", "Buraco " + (indice + 1) + ", vazio");
    });
  }

  function atualizarHud() {
    const percentual = estado.exibidosNoNivel === 0
      ? 0
      : Math.max(0, Math.round((estado.pontosNoNivel / estado.exibidosNoNivel) * 100));

    hudTempo.textContent = "00:" + String(estado.tempoRestante).padStart(2, "0");
    hudTempo.dateTime = "PT" + estado.tempoRestante + "S";
    hudNivel.textContent = String(estado.nivel);
    hudAcertos.textContent = String(estado.toupeirasAcertadas);
    hudPontuacao.textContent = estado.pontuacao.toLocaleString("pt-BR");
    hudPercentual.textContent = percentual + "%";
    hudBarra.value = Math.min(100, percentual);
  }

  function escolherBuracoLivre() {
    const livres = buracos.filter(function (buraco) {
      return buraco.dataset.estado === "vazio";
    });
    return livres[Math.floor(Math.random() * livres.length)];
  }

  function exibirAlvo() {
    if (estado.finalizado) {
      return;
    }

    const buraco = escolherBuracoLivre();
    if (!buraco) {
      registrarTemporizador(exibirAlvo, INTERVALO_APARECIMENTO);
      return;
    }

    const ehBomba = modo === "explosivo" && Math.random() < 0.25;
    const tipo = ehBomba ? "bomba" : "toupeira";
    const tempoVisivel = Math.max(450, TEMPO_VISIVEL_INICIAL - (estado.nivel - 1) * REDUCAO_POR_NIVEL);

    estado.exibidosNoNivel += 1;
    buraco.dataset.estado = tipo;
    buraco.setAttribute("aria-label", ehBomba ? "Bomba à vista" : "Toupeira à vista");
    atualizarHud();

    registrarTemporizador(function () {
      if (buraco.dataset.estado === tipo) {
        buraco.dataset.estado = "vazio";
        buraco.setAttribute("aria-label", "Buraco vazio");
      }
    }, tempoVisivel);

    registrarTemporizador(exibirAlvo, INTERVALO_APARECIMENTO);
  }

  function acertarBuraco(buraco) {
    if (estado.finalizado || !["toupeira", "bomba"].includes(buraco.dataset.estado)) {
      return;
    }

    if (buraco.dataset.estado === "toupeira") {
      estado.toupeirasAcertadas += 1;
      estado.pontuacao += PONTOS_TOUPEIRA;
      estado.pontosNoNivel += 1;
      buraco.dataset.estado = "acerto";
      mensagem.textContent = "Boa! Toupeira acertada.";
    } else {
      /* A pontuacao nao fica negativa: o servidor rejeita valores
         negativos e a partida seria perdida */
      estado.pontuacao = Math.max(0, estado.pontuacao - PONTOS_TOUPEIRA);
      estado.pontosNoNivel -= 1;
      buraco.dataset.estado = "explosao";
      mensagem.textContent = "Cuidado! Você acertou uma bomba.";
    }

    buraco.setAttribute("aria-label", "Buraco vazio");
    atualizarHud();
    registrarTemporizador(function () {
      buraco.dataset.estado = "vazio";
    }, 250);
  }

  function iniciarNivel() {
    estado.tempoRestante = NIVEL_DURACAO;
    estado.exibidosNoNivel = 0;
    estado.pontosNoNivel = 0;
    limparBuracos();
    mensagem.textContent = "Nível " + estado.nivel + " iniciado.";
    atualizarHud();
    exibirAlvo();
    contarTempo();
  }

  function contarTempo() {
    registrarTemporizador(function () {
      if (estado.finalizado) {
        return;
      }

      estado.tempoRestante -= 1;
      atualizarHud();

      if (estado.tempoRestante <= 0) {
        encerrarNivel();
      } else {
        contarTempo();
      }
    }, 1000);
  }

  function encerrarNivel() {
    const percentual = estado.exibidosNoNivel === 0
      ? 0
      : Math.max(0, (estado.pontosNoNivel / estado.exibidosNoNivel) * 100);

    if (percentual >= META_ACERTOS) {
      estado.nivel += 1;
      limparTemporizadores();
      iniciarNivel();
    } else {
      finalizarPartida("Você não atingiu a meta de " + META_ACERTOS + "% no nível.");
    }
  }

  /*Função responsável por finalizar a partida recebendo motivo como parâmetro.
  Se a partida já estiver finalizada, ele apenas da um return para não finalizar
  de forma errada duas vezes. Caso não esteja finalizada, o seu estado finalizado
  muda para true, é limpado os temporizados, e preenchido os campos do formulário
  invisível que está presente em view/partida.php, para que esse formulário passe por
  um controller pho, chegue ao model e seja salvo os dados da partida no banco de dados */
  function finalizarPartida(motivo) {
    if (estado.finalizado) {
      return;
    }

    estado.finalizado = true;
    limparTemporizadores();

    const formulario = document.getElementById("form-resultado");

    document.getElementById("resultado-linhas").value = linhas;
    document.getElementById("resultado-colunas").value = colunas;
    document.getElementById("resultado-modalidade").value = modo;
    document.getElementById("resultado-pontuacao").value = estado.pontuacao;
    document.getElementById("resultado-nivel").value = estado.nivel;
    document.getElementById("resultado-acertos").value = estado.toupeirasAcertadas;
    document.getElementById("resultado-motivo").value = motivo;

    formulario.submit();
  }

  desistir.addEventListener("click", function () {
    finalizarPartida("Partida encerrada pelo jogador.");
  });

  iniciarNivel();
})();
