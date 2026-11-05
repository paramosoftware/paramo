<?php

/**
 * Limitador de tentativas simples, baseado em arquivos fora da área pública.
 *
 * Usado para limitação de recuperação de senha e de submissões externas.
 * Não depende de banco de dados.
 */
class limite_tentativas
{
    public static function diretorio(): string
    {
        return armazenamento_interno::diretorio("pasta_rate_limit", "rate_limit");
    }

    private static function arquivo(string $ps_chave): string
    {
        return self::diretorio() . hash("sha256", $ps_chave) . ".json";
    }

    /**
     * Registra uma tentativa e informa se ela ainda é permitida.
     *
     * @return bool true quando a tentativa pode prosseguir
     */
    public static function permitir(string $ps_chave, int $pn_maximo, int $pn_janela_segundos): bool
    {
        $vs_arquivo = self::arquivo($ps_chave);
        $vh_handle = @fopen($vs_arquivo, "c+");

        # Não conseguir abrir/travar o contador não é "limite atingido": bloquear
        # aqui fazia qualquer falha de disco/permissão responder "muitas
        # tentativas" para todo mundo. O que protege os endpoints é a
        # assinatura/token; o limite é melhor esforço.
        if ($vh_handle === false)
        {
            utils::log("Não foi possível abrir o contador de tentativas: " . $vs_arquivo, "limite_tentativas::permitir");

            return true;
        }

        if (!flock($vh_handle, LOCK_EX))
        {
            fclose($vh_handle);

            utils::log("Não foi possível travar o contador de tentativas: " . $vs_arquivo, "limite_tentativas::permitir");

            return true;
        }

        $vs_conteudo = stream_get_contents($vh_handle);
        $va_dados = json_decode((string) $vs_conteudo, true);
        $vn_agora = time();

        if (!is_array($va_dados) || !isset($va_dados["inicio"]) || ($va_dados["inicio"] + $pn_janela_segundos) < $vn_agora)
            $va_dados = ["inicio" => $vn_agora, "tentativas" => 0];

        $vb_permitido = ((int) ($va_dados["tentativas"] ?? 0)) < $pn_maximo;

        if ($vb_permitido)
            $va_dados["tentativas"] = ((int) ($va_dados["tentativas"] ?? 0)) + 1;

        rewind($vh_handle);
        ftruncate($vh_handle, 0);
        fwrite($vh_handle, json_encode($va_dados));
        fflush($vh_handle);
        flock($vh_handle, LOCK_UN);
        fclose($vh_handle);
        @chmod($vs_arquivo, 0600);

        return $vb_permitido;
    }

    public static function limpar_expirados(int $pn_janela_segundos = 86400): void
    {
        $vs_diretorio = self::diretorio();
        $vn_limite = time() - $pn_janela_segundos;

        foreach (glob($vs_diretorio . "*.json") ?: [] as $vs_arquivo)
        {
            if (is_file($vs_arquivo) && filemtime($vs_arquivo) < $vn_limite)
                @unlink($vs_arquivo);
        }
    }
}
