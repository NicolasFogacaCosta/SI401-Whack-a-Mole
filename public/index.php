<?php
  /**
   * Arquivo de entrada principal do sistema.
   * 
   * Este arquivo é responsável por exibir a página de login para o usuário. Ele também
   * verifica se há mensagens de erro na URL e as exibe na página, caso existam.
   */
  $msg_erro = isset($_GET['erro']) ? 'Usuário ou senha inválidos!' : '';
?>

<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whack-a-Mole | Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="icon" href="assets/img/toupeira-favicon.ico">
  </head>

  <body>
    <!-- Inclusão do arquivo view/login.php que contém o formulário de login -->
    <?php require_once '../app/view/login.php'; ?>
  </body>
</html>