<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Edite o cadastro</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <link rel="stylesheet" href="../../public/assets/css/edicao.css">
    <link rel="icon" href="../../public/assets/img/toupeira-favicon.ico">
  </head>

  <body>
    <header class="login-screen">
      <div class="login-screen-art">
        <img src="../../public/assets/img/emblema-edicao.jpeg" alt="thumb-art">
      </div>

      <div class="login-screen-title">
        <h1>Altere seu cadastro!</h1>
      </div>
    </header>

    <main class="forms">
      <div class="forms-card edicao-card">
        <form id="edicao-form" novalidate>
          <div class="field">
            <label for="full_name">Nome Completo</label>
            <input id="full_name" type="text" required>
          </div>

          <div class="field">
            <label for="birthday">Data de Nascimento</label>
            <input id="birthday" type="date" value="2000-01-15" readonly>
          </div>

          <div class="field">
            <label for="cpf">CPF</label>
            <input id="cpf" type="text" value="000.000.000-00" readonly>
          </div>

          <div class="field">
            <label for="user">Usuário</label>
            <input id="user" type="text" value="ZeDaMarreta" readonly>
          </div>

          <div class="field">
            <label for="phone">Telefone</label>
            <input id="phone" type="text" maxlength="15" placeholder="(00) 00000-0000" required>
          </div>

          <div class="field">
            <label for="email">E-mail</label>
            <input id="email" type="email" required>
          </div>

          <div class="field">
            <label for="password">Senha</label>
            <input id="password" type="password" required>
          </div>

          <p id="form-error" role="alert" hidden></p>
          <!-- Exibir o alerta mediante validacao Java futura -->

          <button type="submit" class="btn btn--primary">Alterar</button>
        </form>

        <a href="ranking.php" class="link-secundario">Voltar ao Ranking</a>
      </div>
    </main>
  </body>
</html>
