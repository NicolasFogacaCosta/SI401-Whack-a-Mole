<?php
    /**
     * Helper de exibição usado pelas views.
     *
     * Toda saída que vem do banco ou do usuário passa por e(), que aplica
     * htmlspecialchars para que nenhum texto seja interpretado como HTML.
     */

    function e($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }

    function nomeModalidade(string $modalidade): string {
        return $modalidade === 'explosivo' ? 'Explosiva' : 'Clássica';
    }

    function formatarNumero($numero): string {
        return number_format((int) $numero, 0, ',', '.');
    }

    // Recebe o DATETIME do banco (2026-09-13 21:34:00) e devolve 13/09/2026 21:34
    function formatarDataHora(string $dataHora): string {
        $data = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dataHora);
        return $data ? $data->format('d/m/Y H:i') : $dataHora;
    }

    // Valor para o atributo datetime da tag <time> (2026-09-13T21:34)
    function dataHoraIso(string $dataHora): string {
        $data = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dataHora);
        return $data ? $data->format('Y-m-d\TH:i') : '';
    }
?>
