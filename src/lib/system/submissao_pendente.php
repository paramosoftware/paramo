<?php

/**
 * Armazenamento não público de submissões externas aguardando confirmação
 * de posse do e-mail. O token nunca é gravado em claro: apenas o seu hash.
 */
class submissao_pendente
{
    public const VALIDADE_SEGUNDOS = 86400;
    public const MAXIMO_PENDENTES_POR_EMAIL = 5;

    public static function diretorio(): string
    {
        return armazenamento_interno::diretorio("pasta_submissoes_pendentes", "submissoes_pendentes");
    }

    public static function token_valido($ps_token): bool
    {
        return is_string($ps_token) && preg_match('/\A[a-f0-9]{64}\z/', $ps_token) === 1;
    }

    private static function arquivo(string $ps_token): string
    {
        return self::diretorio() . hash("sha256", $ps_token) . ".json";
    }

    /**
     * Cria uma submissão pendente e devolve o token em claro apenas para envio
     * por e-mail. O chamador não deve devolvê-lo na resposta HTTP.
     *
     * @return array{ok:bool,token:string,erro:string}
     */
    public static function criar(string $ps_objeto_id, array $pa_payload, string $ps_email, string $ps_nome, string $ps_url_confirmacao = ""): array
    {
        $vs_email_normalizado = strtolower(trim($ps_email));

        if (!filter_var($vs_email_normalizado, FILTER_VALIDATE_EMAIL))
            return ["ok" => false, "token" => "", "erro" => "E-mail inválido."];

        if (!limite_tentativas::permitir(
            "submissao_pendente:" . $vs_email_normalizado,
            self::MAXIMO_PENDENTES_POR_EMAIL,
            3600
        ))
            return ["ok" => false, "token" => "", "erro" => "Você já solicitou confirmação para este e-mail há pouco tempo. Verifique sua caixa de entrada (inclusive o spam) e aguarde alguns minutos antes de pedir outra."];

        self::limpar_expirados();

        $vs_token = bin2hex(random_bytes(32));
        $va_registro = [
            "objeto" => $ps_objeto_id,
            "payload" => $pa_payload,
            "email" => $vs_email_normalizado,
            "nome" => $ps_nome,
            # Página do portal público que confirma a submissão (informada pela
            # própria submissão, que é assinada).
            "url_confirmacao" => $ps_url_confirmacao,
            "criado_em" => time(),
            "expira_em" => time() + self::VALIDADE_SEGUNDOS,
            "usado" => false,
        ];

        $vs_json = json_encode($va_registro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($vs_json === false)
            return ["ok" => false, "token" => "", "erro" => "Não foi possível preparar a submissão."];

        $vs_arquivo = self::arquivo($vs_token);

        if (file_put_contents($vs_arquivo, $vs_json, LOCK_EX) === false)
            return ["ok" => false, "token" => "", "erro" => "Não foi possível preparar a submissão."];

        @chmod($vs_arquivo, 0600);

        return ["ok" => true, "token" => $vs_token, "erro" => ""];
    }

    /**
     * Lê os metadados sem consumir o token (usado para exibir a confirmação).
     */
    public static function espiar($ps_token): ?array
    {
        if (!self::token_valido($ps_token))
            return null;

        $vs_arquivo = self::arquivo($ps_token);

        if (!is_file($vs_arquivo))
            return null;

        $va_registro = json_decode((string) file_get_contents($vs_arquivo), true);

        if (!is_array($va_registro) || !empty($va_registro["usado"]))
            return null;

        if ((int) ($va_registro["expira_em"] ?? 0) < time())
        {
            @unlink($vs_arquivo);
            return null;
        }

        return $va_registro;
    }

    /**
     * Consome o token de forma atômica e devolve o registro.
     */
    public static function consumir($ps_token): ?array
    {
        if (!self::token_valido($ps_token))
            return null;

        $vs_arquivo = self::arquivo($ps_token);
        $vh_handle = @fopen($vs_arquivo, "c+");

        if ($vh_handle === false)
            return null;

        if (!flock($vh_handle, LOCK_EX))
        {
            fclose($vh_handle);
            return null;
        }

        $va_registro = json_decode((string) stream_get_contents($vh_handle), true);

        if (!is_array($va_registro) || !empty($va_registro["usado"]) || ((int) ($va_registro["expira_em"] ?? 0) < time()))
        {
            flock($vh_handle, LOCK_UN);
            fclose($vh_handle);
            @unlink($vs_arquivo);
            return null;
        }

        $va_registro["usado"] = true;

        rewind($vh_handle);
        ftruncate($vh_handle, 0);
        fwrite($vh_handle, json_encode($va_registro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        fflush($vh_handle);
        flock($vh_handle, LOCK_UN);
        fclose($vh_handle);

        return $va_registro;
    }

    public static function remover($ps_token): void
    {
        if (self::token_valido($ps_token))
            @unlink(self::arquivo($ps_token));
    }

    public static function limpar_expirados(): void
    {
        foreach (glob(self::diretorio() . "*.json") ?: [] as $vs_arquivo)
        {
            $va_registro = json_decode((string) @file_get_contents($vs_arquivo), true);

            if (!is_array($va_registro) || ((int) ($va_registro["expira_em"] ?? 0) < time()))
                @unlink($vs_arquivo);
        }
    }

    /**
     * URL que confirma a submissão.
     *
     * $ps_url_base vem do payload assinado pela submissão: o portal público
     * informa a própria base, então nenhum ambiente precisa configurar nada.
     * "submissao_formulario_url_confirmacao" fica apenas como override.
     */
    public static function url_confirmacao(string $ps_token, string $ps_url_base = ""): string
    {
        $vs_url_configurada = trim($ps_url_base) !== "" ? $ps_url_base : config::get(["submissao_formulario_url_confirmacao"]);

        if (is_string($vs_url_configurada) && $vs_url_configurada !== "")
            return $vs_url_configurada . (strpos($vs_url_configurada, "?") === false ? "?" : "&") . "token=" . rawurlencode($ps_token);

        $vs_scheme = $_SERVER["REQUEST_SCHEME"] ?? "https";
        $vs_host = $_SERVER["HTTP_HOST"] ?? "";
        $vs_script = str_replace("\\", "/", $_SERVER["SCRIPT_NAME"] ?? "");
        $vs_app_base = preg_replace('#/functions/[^/]+$#', '', $vs_script);

        if (!is_string($vs_app_base) || $vs_app_base === $vs_script)
            $vs_app_base = "/app";

        $vs_base = $vs_host !== "" ? ($vs_scheme . "://" . $vs_host . $vs_app_base) : $vs_app_base;

        return $vs_base . "/confirmar_submissao.php?token=" . rawurlencode($ps_token);
    }

    public static function enviar_confirmacao(string $ps_token, string $ps_email, string $ps_nome): bool
    {
        if (!config::get(["f_envio_email"]))
            return false;

        $va_pendente = self::espiar($ps_token);
        $vs_url_confirmacao = (string) ($va_pendente["url_confirmacao"] ?? "");

        $vs_link = htmlspecialchars(self::url_confirmacao($ps_token, $vs_url_confirmacao), ENT_QUOTES, "UTF-8");
        $vs_nome = htmlspecialchars($ps_nome, ENT_QUOTES, "UTF-8");

        $vs_mensagem = "<p>Olá, " . $vs_nome . "</p>";
        $vs_mensagem .= "<p>Recebemos uma solicitação de pedido de pesquisa com este endereço de e-mail.</p>";
        $vs_mensagem .= "<p>Para confirmar que este e-mail pertence a você, acesse o link abaixo:</p>";
        $vs_mensagem .= "<p><a href=\"" . $vs_link . "\">Confirmar pedido de pesquisa</a></p>";
        $vs_mensagem .= "<p>Este link é de uso único e expira em 24 horas. Se você não fez esta solicitação, ignore esta mensagem.</p>";

        # Rodapé: a imagem embutida só aparece no corpo se o HTML a referenciar.
        $vs_mensagem .= "<p style=\"margin-top:24px;\"><img src=\"cid:logo-image\" alt=\"logo e-mail\" width=\"200\"></p>";

        return utils::send_email(
            $ps_email,
            $ps_nome,
            "[" . config::get(["nome_instituicao"]) . "] Confirme seu pedido de pesquisa",
            $vs_mensagem
        );
    }
}
