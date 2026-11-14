<?php

session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit ('Método inválido');
}

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$stmt = $pdo-> prepare (
    "SELECT id, usuario, senha_hash
    FROM usuarios
    WHERE usuario = :usuario"
    );

$stmt->execute([
    ':usuario' => $usuario
]);

$usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

if(
    $usuarioEncontrado === false ||
    !password_verify($senha, $usuarioEncontrado['senha_hash'])
){
    exit('Usuário ou senha inválidos.');
}

$_SESSION['usuario_id'] = $usuarioEncontrado['id'];
$_SESSION['usuario'] = $usuarioEncontrado['usuario'];

header('Location: ../view/menu.html');
exit;