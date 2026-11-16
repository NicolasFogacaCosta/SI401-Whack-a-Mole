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
        action="../controller/cadastro.php" 
        method="POST"
        >
          <div class="field">
            <label for="full_name">Nome Completo</label>
            <input 
            id="full_name" 
            name="nome_completo"
            type="text" 
            required
            >
          </div>

          <div class="field">
            <label for="birthday">Data de Nascimento</label>
            <input 
            id="birthday" 
            name="data_nascimento"
            type="date" 
            required
            >
          </div>

          <div class="field">
            <label for="cpf">CPF</label>
            <input 
            id="cpf" 
            name="cpf"
            type="text" 
            maxlength="14" 
            placeholder="000.000.000-00" 
            required
            >
          </div>

          <div class="field">
            <label for="phone">Telefone</label>
            <input 
            id="phone" 
            name="telefone"
            type="text" 
            maxlength="15" 
            placeholder="(00) 00000-0000" 
            required
            >
          </div>

          <div class="field">
            <label for="email">E-mail</label>
            <input 
            id="email" 
            name="email"
            type="email" 
            required
            >
          </div>

          <div class="field">
            <label for="user">Usuário</label>
            <input 
            id="user" 
            name="usuario"
            type="text" 
            required
            >
          </div>

          <div class="field">
            <label for="password">Senha</label>
            <input 
            id="password" 
            name="senha"
            type="password" 
            required
            >
          </div>

          <p id="form-error" role="alert" hidden></p>
          <!-- Exibir o alerta mediante validacao Java futura -->

          <button type="submit" class="btn btn--primary">Criar conta</button>
        </form>

        <a href="../../public/index.php" class="link-secundario">Voltar ao Login</a>
      </div>
    </main>
  </body>
</html>
