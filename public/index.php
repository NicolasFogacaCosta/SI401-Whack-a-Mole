<?php
  /**
   * Arquivo de entrada principal do sistema.
   *
   * Este arquivo é responsável por exibir a página de login para o usuário. Ele também
   * verifica se há mensagens de erro ou de sucesso na URL e as exibe na página, caso existam.
   * Quem já está logado vai direto ao menu.
   */
  require_once __DIR__ . '/../app/config/sessao.php';

  if (usuarioLogado()) {
    header('Location: ../app/controller/menu.php');
    exit;
  }

  $msg_erro = isset($_GET['erro']) ? 'Usuário ou senha inválidos!' : '';
  $msg_sucesso = isset($_GET['cadastro']) ? 'Conta criada! Faça login para jogar.' : '';
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
    <?php require_once __DIR__ . '/../app/view/login.php'; ?>
  </body>
</html>
