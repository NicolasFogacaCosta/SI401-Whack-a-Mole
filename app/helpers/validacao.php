<?php
    /**
     * Helper de validação dos dados recebidos pelo servidor.
     *
     * Funções puras (sem banco, sem sessão, sem HTML): recebem os valores vindos do
     * formulário e devolvem os dados normalizados e/ou a lista de erros.
     *
     * A validação no cliente (public/assets/js/mascaras.js) é só uma ajuda ao usuário:
     * a decisão final é sempre tomada aqui.
     */

    const TAMANHOS_TABULEIRO = [2, 3, 4, 5, 6, 7, 8];
    const MODALIDADES = ['classico', 'explosivo'];
    const SENHA_MIN = 6;
    const SENHA_MAX = 72; // limite do bcrypt usado por password_hash

    function textoDoCampo(array $entrada, string $campo): string {
        $valor = $entrada[$campo] ?? '';
        return is_string($valor) ? trim($valor) : '';
    }

    function somenteDigitos(string $valor): string {
        return preg_replace('/\D/', '', $valor);
    }

    // ---------------------------------------------------------------- CPF

    function cpfValido(string $cpf): bool {
        if (!preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $cpf)) {
            return false;
        }

        $digitos = somenteDigitos($cpf);

        // 000.000.000-00, 111.111.111-11... passam no cálculo, mas não são CPFs válidos
        if (preg_match('/^(\d)\1{10}$/', $digitos)) {
            return false;
        }

        for ($posicao = 9; $posicao < 11; $posicao++) {
            $soma = 0;
            for ($i = 0; $i < $posicao; $i++) {
                $soma += (int) $digitos[$i] * ($posicao + 1 - $i);
            }
            $verificador = ((10 * $soma) % 11) % 10;

            if ((int) $digitos[$posicao] !== $verificador) {
                return false;
            }
        }

        return true;
    }

    // Grava sempre no formato 000.000.000-00, para que a restrição UNIQUE funcione
    // independentemente de o usuário ter digitado com ou sem máscara.
    function formatarCpf(string $cpf): string {
        $d = somenteDigitos($cpf);
        return substr($d, 0, 3) . '.' . substr($d, 3, 3) . '.' . substr($d, 6, 3) . '-' . substr($d, 9, 2);
    }

    // ----------------------------------------------------------- Telefone

    function telefoneValido(string $telefone): bool {
        if (!preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $telefone)) {
            return false;
        }

        $digitos = somenteDigitos($telefone);
        $tamanho = strlen($digitos);

        if ($tamanho !== 10 && $tamanho !== 11) {
            return false;
        }

        // DDD não começa com 0 e celular (11 dígitos) começa com 9
        if ($digitos[0] === '0' || ($tamanho === 11 && $digitos[2] !== '9')) {
            return false;
        }

        return true;
    }

    function formatarTelefone(string $telefone): string {
        $d = somenteDigitos($telefone);
        $meio = strlen($d) === 11 ? 5 : 4;
        return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, $meio) . '-' . substr($d, 2 + $meio);
    }

    // ---------------------------------------------------- Demais campos

    function dataNascimentoValida(string $data, ?DateTimeImmutable $hoje = null): bool {
        $convertida = DateTimeImmutable::createFromFormat('!Y-m-d', $data);

        // createFromFormat aceita 2024-02-31 (vira 02/03); comparar de volta barra isso
        if ($convertida === false || $convertida->format('Y-m-d') !== $data) {
            return false;
        }

        $hoje = $hoje ?? new DateTimeImmutable('today');

        return $convertida <= $hoje && (int) $convertida->format('Y') >= 1900;
    }

    function emailValido(string $email): bool {
        return mb_strlen($email) <= 150 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function nomeValido(string $nome): bool {
        $tamanho = mb_strlen($nome);
        return $tamanho >= 3 && $tamanho <= 150 && preg_match("/^\p{L}[\p{L} '.-]*$/u", $nome) === 1;
    }

    function usuarioValido(string $usuario): bool {
        return preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $usuario) === 1;
    }

    function senhaValida(string $senha): bool {
        $tamanho = strlen($senha);
        return $tamanho >= SENHA_MIN && $tamanho <= SENHA_MAX;
    }

    function normalizarNome(string $nome): string {
        return preg_replace('/\s+/u', ' ', trim($nome));
    }

    // ------------------------------------------------- Formulários inteiros

    /**
     * Valida o formulário de cadastro.
     * Retorna ['dados' => campos normalizados, 'erros' => lista de mensagens].
     * A senha volta em texto puro em 'dados' para o controller gerar o hash.
     */
    function validarCadastro(array $entrada, ?DateTimeImmutable $hoje = null): array {
        $nome = normalizarNome(textoDoCampo($entrada, 'nome_completo'));
        $nascimento = textoDoCampo($entrada, 'data_nascimento');
        $cpf = textoDoCampo($entrada, 'cpf');
        $telefone = textoDoCampo($entrada, 'telefone');
        $email = mb_strtolower(textoDoCampo($entrada, 'email'));
        $usuario = textoDoCampo($entrada, 'usuario');
        $senha = is_string($entrada['senha'] ?? null) ? $entrada['senha'] : '';

        $erros = [];

        if (!nomeValido($nome)) {
            $erros[] = 'Informe o nome completo (de 3 a 150 letras).';
        }
        if (!dataNascimentoValida($nascimento, $hoje)) {
            $erros[] = 'Informe uma data de nascimento válida.';
        }
        if (!cpfValido($cpf)) {
            $erros[] = 'CPF inválido.';
        }
        if (!telefoneValido($telefone)) {
            $erros[] = 'Telefone inválido. Use o formato (00) 00000-0000.';
        }
        if (!emailValido($email)) {
            $erros[] = 'E-mail inválido.';
        }
        if (!usuarioValido($usuario)) {
            $erros[] = 'O usuário deve ter de 3 a 50 caracteres: letras, números, ponto, hífen ou sublinhado.';
        }
        if (!senhaValida($senha)) {
            $erros[] = 'A senha deve ter de ' . SENHA_MIN . ' a ' . SENHA_MAX . ' caracteres.';
        }

        return [
            'dados' => [
                'nome_completo' => $nome,
                'data_nascimento' => $nascimento,
                'cpf' => $erros === [] ? formatarCpf($cpf) : $cpf,
                'telefone' => $erros === [] ? formatarTelefone($telefone) : $telefone,
                'email' => $email,
                'usuario' => $usuario,
                'senha' => $senha,
            ],
            'erros' => $erros,
        ];
    }

    /**
     * Valida o formulário de edição. Só nome, telefone, e-mail e senha são lidos:
     * qualquer outro campo enviado (CPF, nascimento, usuário) é simplesmente ignorado.
     * A senha é opcional; vazia significa manter a atual (senha = null).
     */
    function validarEdicao(array $entrada): array {
        $nome = normalizarNome(textoDoCampo($entrada, 'nome_completo'));
        $telefone = textoDoCampo($entrada, 'telefone');
        $email = mb_strtolower(textoDoCampo($entrada, 'email'));
        $senha = is_string($entrada['senha'] ?? null) ? $entrada['senha'] : '';

        $erros = [];

        if (!nomeValido($nome)) {
            $erros[] = 'Informe o nome completo (de 3 a 150 letras).';
        }
        if (!telefoneValido($telefone)) {
            $erros[] = 'Telefone inválido. Use o formato (00) 00000-0000.';
        }
        if (!emailValido($email)) {
            $erros[] = 'E-mail inválido.';
        }
        if ($senha !== '' && !senhaValida($senha)) {
            $erros[] = 'A nova senha deve ter de ' . SENHA_MIN . ' a ' . SENHA_MAX . ' caracteres.';
        }

        return [
            'dados' => [
                'nome_completo' => $nome,
                'telefone' => telefoneValido($telefone) ? formatarTelefone($telefone) : $telefone,
                'email' => $email,
                'senha' => $senha === '' ? null : $senha,
            ],
            'erros' => $erros,
        ];
    }

    function tabuleiroValido($linhas, $colunas): bool {
        return $linhas === $colunas && in_array($linhas, TAMANHOS_TABULEIRO, true);
    }

?>
