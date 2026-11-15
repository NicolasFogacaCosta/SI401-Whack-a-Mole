<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Método inválido.');
}

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

$usuarioModel = new Usuario($pdo);
$usuarioEncontrado = $usuarioModel->buscarUsuario($usuario);

if (
    $usuarioEncontrado === false ||
    !password_verify($senha, $usuarioEncontrado['senha_hash'])
) {
    exit('Usuário ou senha inválidos.');
}

$_SESSION['usuario_id'] = $usuarioEncontrado['id'];
$_SESSION['usuario'] = $usuarioEncontrado['usuario'];

header('Location: ../view/menu.html');
exit;