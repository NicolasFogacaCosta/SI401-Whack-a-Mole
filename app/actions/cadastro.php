<?php
require_once __DIR__ . '/../config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Método inválido. ');
}

echo '<pre>';
$nomeCompleto = $_POST['nome_completo'];
$dataNascimento = $_POST['data_nascimento'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$usuario = $_POST['usuario'];
$senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
echo '</pre>';

