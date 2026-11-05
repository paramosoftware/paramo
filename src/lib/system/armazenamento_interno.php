<?php

/**
 * Resolve as pastas de escrita interna do aplicativo (limitador de
 * tentativas, nonces e submissões pendentes).
 *
 * A pasta configurada é a primeira opção, mas só é criada quando algum
 * ancestral existente permite escrita: uma chamada de mkdir() sem permissão
 * gera E_WARNING, que o encerramento do PHP registra no log mesmo quando a
 * chamada está silenciada com @.
 *
 * Quando o caminho configurado não serve, usa-se uma subpasta da pasta de
 * logs (o aplicativo já grava nela) e, por último, o diretório temporário.
 */
class armazenamento_interno
{
    /**
     * Devolve um diretório gravável, terminado em separador de diretório.
     *
     * @param string $ps_configuracao chave de configuração com o caminho desejado
     * @param string $ps_pasta        nome da subpasta usada nas alternativas
     */
    public static function diretorio(string $ps_configuracao, string $ps_pasta): string
    {
        $va_candidatos = [];

        $vs_configurado = config::get([$ps_configuracao]);

        if (is_string($vs_configurado) && $vs_configurado !== "")
            $va_candidatos[] = $vs_configurado;

        $vs_pasta_logs = config::get(["pasta_logs"]);

        if (is_string($vs_pasta_logs) && $vs_pasta_logs !== "")
            $va_candidatos[] = rtrim($vs_pasta_logs, "/\\") . "/" . $ps_pasta . "/";

        $va_candidatos[] = rtrim(sys_get_temp_dir(), "/\\") . DIRECTORY_SEPARATOR
            . "paramo_" . $ps_pasta . DIRECTORY_SEPARATOR;

        foreach ($va_candidatos as $vs_candidato)
        {
            $vs_diretorio = rtrim($vs_candidato, "/\\") . DIRECTORY_SEPARATOR;

            if (self::preparar($vs_diretorio))
                return $vs_diretorio;
        }

        return rtrim(sys_get_temp_dir(), "/\\") . DIRECTORY_SEPARATOR;
    }

    /**
     * Garante que o diretório exista e seja gravável sem provocar aviso do PHP.
     */
    private static function preparar(string $ps_diretorio): bool
    {
        if (is_dir($ps_diretorio))
            return is_writable($ps_diretorio);

        $vs_ancestral = dirname(rtrim($ps_diretorio, "/\\"));

        while ($vs_ancestral !== "" && $vs_ancestral !== "." && $vs_ancestral !== DIRECTORY_SEPARATOR
            && !is_dir($vs_ancestral))
        {
            $vs_superior = dirname($vs_ancestral);

            if ($vs_superior === $vs_ancestral)
                break;

            $vs_ancestral = $vs_superior;
        }

        if (!is_dir($vs_ancestral) || !is_writable($vs_ancestral))
            return false;

        return @mkdir($ps_diretorio, 0700, true) && is_writable($ps_diretorio);
    }
}
