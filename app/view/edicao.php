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
        <form id="edicao-form" action="edicao.php" method="POST">
          <div class="field">
            <label for="full_name">Nome Completo</label>
            <input id="full_name" name="nome_completo" type="text" maxlength="150" autocomplete="name"
              value="<?= e($usuario['nome_completo']) ?>" required>
          </div>

          <!-- Campos imutáveis: sem atributo name, não são enviados. Mesmo que alguém os
               force no POST, o controller/model ignoram esses valores. -->
          <div class="field">
            <label for="birthday">Data de Nascimento</label>
            <input id="birthday" type="date" value="<?= e($usuario['data_nascimento']) ?>" readonly>
          </div>

          <div class="field">
            <label for="cpf">CPF</label>
            <input id="cpf" type="text" value="<?= e($usuario['cpf']) ?>" readonly>
          </div>

          <div class="field">
            <label for="user">Usuário</label>
            <input id="user" type="text" value="<?= e($usuario['usuario']) ?>" readonly>
          </div>

          <div class="field">
            <label for="phone">Telefone</label>
            <input id="phone" name="telefone" type="text" inputmode="numeric" maxlength="15"
              placeholder="(00) 00000-0000" data-mascara="telefone" autocomplete="tel"
              value="<?= e($usuario['telefone']) ?>" required>
          </div>

          <div class="field">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" maxlength="150" autocomplete="email"
              value="<?= e($usuario['email']) ?>" required>
          </div>

          <div class="field">
            <label for="password">Nova senha</label>
            <input id="password" name="senha" type="password" minlength="<?= SENHA_MIN ?>" maxlength="<?= SENHA_MAX ?>"
              placeholder="Deixe em branco para manter a atual" autocomplete="new-password">
          </div>

          <?php if ($sucesso && $erros === []): ?>
            <p id="form-success" class="form-sucesso" role="status">Cadastro atualizado com sucesso!</p>
          <?php endif; ?>

          <!-- Erros da validação PHP (controller/edicao.php) -->
          <p id="form-error" role="alert"<?= $erros === [] ? ' hidden' : '' ?>><?= implode('<br>', array_map('e', $erros)) ?></p>

          <button type="submit" class="btn btn--primary">Alterar</button>
        </form>

        <a href="ranking.php" class="link-secundario">Voltar ao Ranking</a>
      </div>
    </main>

    <script src="../../public/assets/js/mascaras.js" defer></script>
  </body>
</html>
