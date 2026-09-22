(function () {
  "use strict";

  const resultado = JSON.parse(sessionStorage.getItem("resultadoPartida") || "null");
  if (!resultado) {
    return;
  }

  const modalidade = resultado.modo === "explosivo" ? "Explosiva" : "Classica";
  document.querySelector("#fim-mensagem").textContent = resultado.motivo;
  document.querySelector("#fim-modalidade").textContent = modalidade;
  document.querySelector("#fim-tabuleiro").textContent = resultado.linhas + " x " + resultado.colunas + " (" + resultado.linhas * resultado.colunas + " buracos)";
  document.querySelector("#fim-acertos").textContent = String(resultado.acertos);
  document.querySelector("#fim-nivel").textContent = String(resultado.nivel);
  document.querySelector("#fim-pontuacao").textContent = resultado.pontuacao.toLocaleString("pt-BR");
})();
