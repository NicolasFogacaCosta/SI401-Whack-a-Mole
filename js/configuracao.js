(function () {
  "use strict";

  const form = document.querySelector("#configuracao-form");
  const error = document.querySelector("#configuracao-erro");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const linhas = Number(form.elements.linhas.value);
    const colunas = Number(form.elements.colunas.value);
    const modo = form.elements.modo.value;

    if (!Number.isInteger(linhas) || !Number.isInteger(colunas) || linhas < 1 || colunas < 1 || linhas * colunas < 4 || linhas * colunas > 64) {
      error.textContent = "Escolha dimensoes que formem um tabuleiro entre 4 e 64 buracos.";
      error.hidden = false;
      return;
    }

    if (!modo) {
      error.textContent = "Escolha uma modalidade para iniciar a partida.";
      error.hidden = false;
      return;
    }

    const params = new URLSearchParams({
      linhas: String(linhas),
      colunas: String(colunas),
      modo
    });

    window.location.href = "partida.html?" + params.toString();
  });
})();
