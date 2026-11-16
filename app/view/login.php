<!-- Tela de login requerida pelo index.php -->
<header class="login-screen">
    <div class="login-screen-art">
        <img src="assets/img/emblema-login.jpeg" alt="thumb-art">
    </div>

    <div class="login-screen-title">
        <h1>Whack-a-Mole</h1>
    </div>
</header>

<main class="forms">
    <div class="forms-card login-card">
        <form id="login-form" action="../app/controller/login.php" method="POST">
            <div class="field">
                <label for="user">Usuário</label>
                <input id="user" name="usuario" type="text" required>
            </div>

            <div class="field">
                <label for="password">Senha</label>
                <input id="password" name="senha" type="password" required>
            </div>

            <?php
                //Exibicao da mensagem de erro no momento do login, caso o usuario ou senha estejam incorretos
                if(!empty($msg_erro)) {
                    echo"<p id='form-error' role='alert'>" . htmlspecialchars($msg_erro) . "</p>";
                } 
            ?>

          <button type="submit" class="btn btn--primary">Acessar</button>
        </form>

        <a href="../app/view/cadastro.php" class="link-secundario">Criar conta</a>
    </div>
</main>