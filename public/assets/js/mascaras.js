(function () {
  "use strict";

  /* Mascaras e validacao de CPF e telefone no cliente, usadas no cadastro
     e na edicao. E so uma ajuda ao usuario: o servidor valida tudo de novo
     em app/helpers/validacao.php. */

  function somenteDigitos(valor) {
    return valor.replace(/\D/g, "");
  }

  function mascararCpf(valor) {
    const d = somenteDigitos(valor).slice(0, 11);

    if (d.length <= 3) return d;
    if (d.length <= 6) return d.slice(0, 3) + "." + d.slice(3);
    if (d.length <= 9) return d.slice(0, 3) + "." + d.slice(3, 6) + "." + d.slice(6);
    return d.slice(0, 3) + "." + d.slice(3, 6) + "." + d.slice(6, 9) + "-" + d.slice(9);
  }

  function mascararTelefone(valor) {
    const d = somenteDigitos(valor).slice(0, 11);
    const meio = d.length === 11 ? 5 : 4;

    if (d.length === 0) return "";
    if (d.length <= 2) return "(" + d;
    if (d.length <= 2 + meio) return "(" + d.slice(0, 2) + ") " + d.slice(2);
    return "(" + d.slice(0, 2) + ") " + d.slice(2, 2 + meio) + "-" + d.slice(2 + meio);
  }

  /* Mesmo calculo dos digitos verificadores feito no PHP */
  function cpfValido(valor) {
    const d = somenteDigitos(valor);

    if (d.length !== 11 || /^(\d)\1{10}$/.test(d)) {
      return false;
    }

    for (let posicao = 9; posicao < 11; posicao += 1) {
      let soma = 0;
      for (let i = 0; i < posicao; i += 1) {
        soma += Number(d[i]) * (posicao + 1 - i);
      }
      if (Number(d[posicao]) !== ((10 * soma) % 11) % 10) {
        return false;
      }
    }

    return true;
  }

  function telefoneValido(valor) {
    const d = somenteDigitos(valor);

    if (d.length !== 10 && d.length !== 11) return false;
    if (d[0] === "0") return false;
    return d.length === 10 || d[2] === "9";
  }

  const mascaras = { cpf: mascararCpf, telefone: mascararTelefone };
  const validadores = {
    cpf: { valido: cpfValido, mensagem: "CPF inválido." },
    telefone: { valido: telefoneValido, mensagem: "Telefone inválido. Use o formato (00) 00000-0000." }
  };

  const campos = document.querySelectorAll("[data-mascara]");

  campos.forEach(function (campo) {
    const mascarar = mascaras[campo.dataset.mascara];

    campo.value = mascarar(campo.value);
    campo.addEventListener("input", function () {
      campo.value = mascarar(campo.value);
      campo.setCustomValidity("");
    });
  });

  const form = document.querySelector("form");
  const erro = document.querySelector("#form-error");

  if (!form || !erro) {
    return;
  }

  form.addEventListener("submit", function (evento) {
    const mensagens = [];

    campos.forEach(function (campo) {
      const validador = validadores[campo.dataset.mascara];
      if (!validador.valido(campo.value)) {
        mensagens.push(validador.mensagem);
      }
    });

    if (mensagens.length > 0) {
      evento.preventDefault();
      erro.textContent = mensagens.join(" ");
      erro.hidden = false;
      erro.scrollIntoView({ block: "nearest" });
    }
  });
})();
