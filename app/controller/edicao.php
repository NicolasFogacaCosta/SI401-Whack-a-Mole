<?php
    /**
     * Controller da edição de cadastro.
     *
     * GET: pré-preenche o formulário com os dados reais do usuário logado.
     * POST: altera só nome, telefone, e-mail e (opcionalmente) a senha. O id vem da sessão, e
     * qualquer valor de CPF, nascimento ou usuário enviado no POST é ignorado: validarEdicao não
     * lê esses campos e o UPDATE do model nem tem essas colunas.
     */
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../model/usuario.php';
    require_once __DIR__ . '/../helpers/validacao.php';
    require_once __DIR__ . '/../helpers/formatacao.php';

    $usuarioId = $_SESSION['usuario_id'];
    $usuario = buscarUsuarioPorId($pdo, $usuarioId);

    // conta removida do banco com a sessão ainda aberta: encerra a sessão
    if (!$usuario) {
        header('Location: logout.php');
        exit;
    }

    $erros = [];
    $sucesso = isset($_GET['sucesso']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validacao = validarEdicao($_POST);
        $dados = $validacao['dados'];
        $erros = $validacao['erros'];

        if ($erros === [] && buscarCamposDuplicados($pdo, null, $dados['email'], null, $usuarioId) !== []) {
            $erros[] = 'E-mail já cadastrado em outra conta.';
        }

        if ($erros === []) {
            $dados['senha_hash'] = $dados['senha'] === null ? null : password_hash($dados['senha'], PASSWORD_DEFAULT);

            try {
                atualizarUsuario($pdo, $usuarioId, $dados);
                header('Location: edicao.php?sucesso=1');
                exit;
            }
            catch (PDOException $e) {
                if ($e->getCode() !== '23000') {
                    throw $e;
                }
                $erros[] = 'E-mail já cadastrado em outra conta.';
            }
        }

        // mostra de volta o que o usuário digitou nos campos editáveis
        $usuario['nome_completo'] = $dados['nome_completo'];
        $usuario['telefone'] = $dados['telefone'];
        $usuario['email'] = $dados['email'];
    }

    require_once __DIR__ . '/../view/edicao.php';
?>
