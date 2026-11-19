<?php
    /**
     * Controller responsável pelo cadastro de usuários.
     *
     * GET: exibe o formulário vazio.
     * POST: valida todos os campos no servidor (helpers/validacao.php), confere se CPF, e-mail ou
     * usuário já pertencem a outra conta e, se tudo estiver correto, grava o usuário com o hash da
     * senha e volta ao login com uma mensagem de sucesso. Em caso de erro, a view é exibida de novo
     * com as mensagens em #form-error e os campos preenchidos (exceto a senha).
     */
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/usuario.php';
    require_once __DIR__ . '/../helpers/validacao.php';
    require_once __DIR__ . '/../helpers/formatacao.php';

    $erros = [];
    $valores = [
        'nome_completo' => '',
        'data_nascimento' => '',
        'cpf' => '',
        'telefone' => '',
        'email' => '',
        'usuario' => '',
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validacao = validarCadastro($_POST);
        $dados = $validacao['dados'];
        $erros = $validacao['erros'];

        if($erros === []) {
            $nomesCampos = ['cpf' => 'CPF', 'email' => 'E-mail', 'usuario' => 'Usuário'];

            foreach(buscarCamposDuplicados($pdo, $dados['cpf'], $dados['email'], $dados['usuario']) as $campo) {
                $erros[] = $nomesCampos[$campo] . ' já cadastrado.';
            }
        }

        if($erros === []) {
            $dados['senha_hash'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

            try {
                cadastrarUsuario($pdo, $dados);
                header('Location: ../../public/index.php?cadastro=1');
                exit;
            }
            catch (PDOException $e) {
                // 23000 = violação de UNIQUE: duas pessoas cadastrando os mesmos dados ao mesmo tempo
                if ($e->getCode() !== '23000') {
                    throw $e;
                }
                $erros[] = 'CPF, e-mail ou usuário já cadastrado.';
            }
        }

        unset($dados['senha']);
        $valores = array_merge($valores, $dados);
    }

    require __DIR__ . '/../view/cadastro.php';
?>
