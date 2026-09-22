(function () {
  "use strict";

  const NIVEL_DURACAO = 15;
  const META_ACERTOS = 90;
  const INTERVALO_APARECIMENTO = 700;
  const TEMPO_VISIVEL_INICIAL = 1200;
  const REDUCAO_POR_NIVEL = 100;

  const params = new URLSearchParams(window.location.search);
  const linhas = Number(params.get("linhas"));
  const colunas = Number(params.get("colunas"));
  const modo = params.get("modo");

  if (!Number.isInteger(linhas) || !Number.isInteger(colunas) || linhas * colunas < 4 || linhas * colunas > 64 || !["classico", "explosivo"].includes(modo)) {
    window.location.replace("jogo.html");
    return;
  }

  const tabuleiro = document.querySelector("#tabuleiro");
  const hudModalidade = document.querySelector("#hud-modalidade");
  const hudTempo = document.querySelector("#hud-tempo");
  const hudNivel = document.querySelector("#hud-nivel");
  const hudAcertos = document.querySelector("#hud-acertos");
  const hudPercentual = document.querySelector("#hud-percentual");
  const hudBarra = document.querySelector("#hud-barra");
  const hudDimensoes = document.querySelector("#hud-dimensoes");
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

  hudModalidade.textContent = modo === "explosivo" ? "Explosiva" : "Classica";
  hudDimensoes.textContent = linhas + " x " + colunas + " (" + linhas * colunas + " buracos)";
  tabuleiro.dataset.colunas = String(colunas);
  tabuleiro.style.gridTemplateColumns = "repeat(" + colunas + ", minmax(0, 1fr))";

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

  window.requestAnimationFrame(function () {
    tabuleiro.scrollIntoView({
      behavior: "smooth",
      block: "start"
    });
  });

  function registrarTemporizador(funcao, atraso) {
    const id = window.setTimeout(funcao, atraso);
    estado.temporizadores.push(id);
    return id;
  }

  function limparTemporizadores() {
    estado.temporizadores.forEach(window.clearTimeout);
    estado.temporizadores = [];
  }

  function atualizarHud() {
    const percentual = estado.exibidosNoNivel === 0
      ? 0
      : Math.max(0, Math.round((estado.pontosNoNivel / estado.exibidosNoNivel) * 100));

    hudTempo.textContent = "00:" + String(estado.tempoRestante).padStart(2, "0");
    hudTempo.dateTime = "PT" + estado.tempoRestante + "S";
    hudNivel.textContent = String(estado.nivel);
    hudAcertos.textContent = String(estado.toupeirasAcertadas);
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
      estado.pontuacao += 100;
      estado.pontosNoNivel += 1;
      buraco.dataset.estado = "acerto";
      mensagem.textContent = "Boa! Toupeira acertada.";
    } else {
      estado.pontuacao -= 100;
      estado.pontosNoNivel -= 1;
      buraco.dataset.estado = "explosao";
      mensagem.textContent = "Cuidado! Voce acertou uma bomba.";
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
    mensagem.textContent = "Nivel " + estado.nivel + " iniciado.";
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
      finalizarPartida("Voce nao atingiu a meta de " + META_ACERTOS + "% no nivel.");
    }
  }

  function finalizarPartida(motivo) {
    if (estado.finalizado) {
      return;
    }

    estado.finalizado = true;
    limparTemporizadores();
    sessionStorage.setItem("resultadoPartida", JSON.stringify({
      linhas,
      colunas,
      modo,
      acertos: estado.toupeirasAcertadas,
      nivel: estado.nivel,
      pontuacao: estado.pontuacao,
      motivo
    }));
    window.location.href = "fim.html";
  }

  desistir.addEventListener("click", function () {
    finalizarPartida("Partida encerrada pelo jogador.");
  });

  iniciarNivel();
})();
