<?php
    /**
     * Controller responsável pelo cadastro de usuários.
     * 
     * Este arquivo processa os dados enviados pelo formulário de cadastro,
     * realiza a validação e, se tudo estiver correto, insere o novo usuário
     * no banco de dados. Em caso de erro, exibe uma mensagem apropriada.
     */
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/usuario.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST['senha_hash'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        try {
            cadastrarUsuario($pdo, $_POST);
            header('Location: ../../public/index.php');
            exit;
        }
        catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw $e;;
            }
            $erro = 'CPF, e-mail ou usuário já cadastrado!';
        }  
    }

    require __DIR__ . '/../view/cadastro.php';
?>