<?php
require_once __DIR__ . '/../config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Método inválido. ');
}

$nomeCompleto = $_POST['nome_completo'];
$dataNascimento = $_POST['data_nascimento'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$usuario = $_POST['usuario'];
$senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$stmt = $pdo -> prepare(
    "INSERT INTO usuarios(
    nome_completo,
    data_nascimento,
    cpf,
    telefone,
    email,
    usuario,
    senha_hash
)VALUES(
    :nome_completo,
    :data_nascimento,
    :cpf,
    :telefone,
    :email,
    :usuario,
    :senha_hash
)"
);

$stmt->execute([
    ':nome_completo' => $nomeCompleto,
    ':data_nascimento' => $dataNascimento,
    ':cpf' => $cpf,
    ':telefone' => $telefone,
    ':email' => $email,
    ':usuario' => $usuario,
    ':senha_hash' => $senha_hash
]);

echo 'Cadastro realizado com sucesso!';
header('Location: ../../public/index.html');
exit;

