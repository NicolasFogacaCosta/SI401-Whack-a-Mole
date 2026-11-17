<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Cadastro</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <link rel="stylesheet" href="../../public/assets/css/cadastro.css">
    <link rel="icon" href="../../public/assets/img/toupeira-favicon.ico">
  </head>

  <body>
    <header class="login-screen">
      <div class="login-screen-art">
        <img src="../../public/assets/img/emblema-cadastro.jpeg" alt="thumb-art">
      </div>

      <div class="login-screen-title">
        <h1>Cadastre-se agora!</h1>
      </div>
    </header>

    <main class="forms">
      <div class="forms-card cadastro-card">
        <form
        id="cadastro-form"
        action="cadastro.php"
        method="POST"
        >
          <div class="field">
            <label for="full_name">Nome Completo</label>
            <input
            id="full_name"
            name="nome_completo"
            type="text"
            maxlength="150"
            autocomplete="name"
            value="<?= e($valores['nome_completo']) ?>"
            required
            >
          </div>

          <div class="field">
            <label for="birthday">Data de Nascimento</label>
            <input
            id="birthday"
            name="data_nascimento"
            type="date"
            min="1900-01-01"
            max="<?= date('Y-m-d') ?>"
            value="<?= e($valores['data_nascimento']) ?>"
            required
            >
          </div>

          <div class="field">
            <label for="cpf">CPF</label>
            <input
            id="cpf"
            name="cpf"
            type="text"
            inputmode="numeric"
            maxlength="14"
            placeholder="000.000.000-00"
            data-mascara="cpf"
            value="<?= e($valores['cpf']) ?>"
            required
            >
          </div>

          <div class="field">
            <label for="phone">Telefone</label>
            <input
            id="phone"
            name="telefone"
            type="text"
            inputmode="numeric"
            maxlength="15"
            placeholder="(00) 00000-0000"
            data-mascara="telefone"
            autocomplete="tel"
            value="<?= e($valores['telefone']) ?>"
            required
            >
          </div>

          <div class="field">
            <label for="email">E-mail</label>
            <input
            id="email"
            name="email"
            type="email"
            maxlength="150"
            autocomplete="email"
            value="<?= e($valores['email']) ?>"
            required
            >
          </div>

          <div class="field">
            <label for="user">Usuário</label>
            <input
            id="user"
            name="usuario"
            type="text"
            minlength="3"
            maxlength="50"
            pattern="[A-Za-z0-9_.\-]{3,50}"
            title="De 3 a 50 caracteres: letras, números, ponto, hífen ou sublinhado"
            autocomplete="username"
            value="<?= e($valores['usuario']) ?>"
            required
            >
          </div>

          <div class="field">
            <label for="password">Senha</label>
            <input
            id="password"
            name="senha"
            type="password"
            minlength="<?= SENHA_MIN ?>"
            maxlength="<?= SENHA_MAX ?>"
            autocomplete="new-password"
            required
            >
          </div>

          <!-- Erros da validação PHP (controller/cadastro.php); o mascaras.js usa o mesmo
               elemento para avisar antes do envio -->
          <p id="form-error" role="alert"<?= $erros === [] ? ' hidden' : '' ?>><?= implode('<br>', array_map('e', $erros)) ?></p>

          <button type="submit" class="btn btn--primary">Criar conta</button>
        </form>

        <a href="../../public/index.php" class="link-secundario">Voltar ao Login</a>
      </div>
    </main>

    <script src="../../public/assets/js/mascaras.js" defer></script>
  </body>
</html>
