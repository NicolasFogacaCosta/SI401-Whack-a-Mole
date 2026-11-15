<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Método inválido.');
}

$nomeCompleto = $_POST['nome_completo'] ?? '';
$dataNascimento = $_POST['data_nascimento'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$usuarioModel = new Usuario($pdo);
$usuarioModel->cadastrarUsuario(
    $nomeCompleto,
    $dataNascimento,
    $cpf,
    $telefone,
    $email,
    $usuario,
    $senhaHash
);

header('Location: ../../public/index.html');
exit;