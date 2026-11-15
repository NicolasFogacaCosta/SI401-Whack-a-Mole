<?php
class Usuario
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function buscarUsuario(string $usuario): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT id, usuario, senha_hash
             FROM usuarios
             WHERE usuario = :usuario"
        );

        $stmt->execute([
            ':usuario' => $usuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrarUsuario(
        string $nomeCompleto,
        string $dataNascimento,
        string $cpf,
        string $telefone,
        string $email,
        string $usuario,
        string $senhaHash
    ): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO usuarios (
                nome_completo,
                data_nascimento,
                cpf,
                telefone,
                email,
                usuario,
                senha_hash
            ) VALUES (
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
            ':senha_hash' => $senhaHash
        ]);
    }
}