(function () {
  "use strict";

  /* O tabuleiro e sempre quadrado, nos tamanhos oferecidos na tela */
  const TAMANHOS_VALIDOS = [2, 3, 4, 5, 6, 7, 8];

  const form = document.querySelector("#configuracao-form");
  const erro = document.querySelector("#configuracao-erro");
  const resumoModo = document.querySelector("#resumo-modo");
  const resumoTamanho = document.querySelector("#resumo-tamanho");

  function escolhaAtual() {
    const tamanho = Number(form.elements.tamanho.value);
    const modo = form.elements.modo.value;

    return { tamanho, modo };
  }

  function atualizarResumo() {
    const escolha = escolhaAtual();

    resumoModo.textContent = escolha.modo === "explosivo" ? "Explosiva" : "Clássica";
    resumoTamanho.textContent = escolha.tamanho + " × " + escolha.tamanho;
  }

  form.addEventListener("change", function () {
    erro.hidden = true;
    atualizarResumo();
  });

  form.addEventListener("submit", function (evento) {
    evento.preventDefault();

    const escolha = escolhaAtual();

    if (!TAMANHOS_VALIDOS.includes(escolha.tamanho)) {
      erro.textContent = "Escolha um dos tamanhos de tabuleiro disponíveis.";
      erro.hidden = false;
      return;
    }

    if (!["classico", "explosivo"].includes(escolha.modo)) {
      erro.textContent = "Escolha uma modalidade para iniciar a partida.";
      erro.hidden = false;
      return;
    }

    /* linhas e colunas seguem separadas na url porque a tela de fim
       de partida e o historico exibem as duas dimensoes */
    const params = new URLSearchParams({
      linhas: String(escolha.tamanho),
      colunas: String(escolha.tamanho),
      modo: escolha.modo
    });

    window.location.href = "partida.html?" + params.toString();
  });

  atualizarResumo();
})();
