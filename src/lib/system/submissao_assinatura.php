<?php

/**
 * Contrato de assinatura das submissões externas.
 *
 * O corpo enviado pelo portal público deve conter timestamp, nonce, signature
 * e os dados da submissão. A assinatura é calculada sobre o JSON do corpo sem
 * token, timestamp, nonce e signature, na mesma ordem de chaves recebida.
 */
class submissao_assinatura
{
    public const VALIDADE_SEGUNDOS = 300;

    public static function token_configurado($ps_token): bool
    {
        if (!is_string($ps_token))
            return false;

        $vs_token = trim($ps_token);

        if (strlen($vs_token) < 32)
            return false;

        $va_placeholders = [
            "ABCD1234", "REPLACE_TOKEN", "REPLACE_ME", "CHANGEME", "CHANGE_ME",
            "YOUR_TOKEN", "YOUR_SECRET", "SAMPLE_TOKEN", "TEST_TOKEN", "TOKEN",
            "SECRET", "PASSWORD"
        ];

        if (in_array(strtoupper($vs_token), array_map("strtoupper", $va_placeholders), true))
            return false;

        if (preg_match('/^(REPLACE|CHANGE|YOUR|SAMPLE|TEST|EXAMPLE|TOKEN|SECRET|PASSWORD)/i', $vs_token))
            return false;

        return true;
    }

    public static function payload_json(array $pa_body): string|false
    {
        unset($pa_body["token"], $pa_body["timestamp"], $pa_body["nonce"], $pa_body["signature"]);

        return json_encode($pa_body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function assinatura(array $pa_body, $ps_secret, string $ps_timestamp, string $ps_nonce): string
    {
        $vs_json = self::payload_json($pa_body);

        if ($vs_json === false)
            return "";

        return hash_hmac("sha256", $ps_timestamp . "\n" . $ps_nonce . "\n" . $vs_json, (string) $ps_secret);
    }

    /**
     * Valida assinatura, janela temporal, nonce e replay.
     *
     * @return array{ok:bool,codigo:int,erro:string,json:string}
     */
    public static function verificar(array $pa_body, $ps_secret): array
    {
        $vs_timestamp = isset($pa_body["timestamp"]) ? (string) $pa_body["timestamp"] : "";
        $vs_nonce = isset($pa_body["nonce"]) ? (string) $pa_body["nonce"] : "";
        $vs_signature = isset($pa_body["signature"]) ? (string) $pa_body["signature"] : "";

        if (!preg_match('/\A[0-9]{1,12}\z/', $vs_timestamp))
            return ["ok" => false, "codigo" => 400, "erro" => "Requisição inválida.", "json" => ""];

        if (!preg_match('/\A[a-f0-9]{32}\z/', $vs_nonce))
            return ["ok" => false, "codigo" => 400, "erro" => "Requisição inválida.", "json" => ""];

        if (!preg_match('/\A[a-f0-9]{64}\z/', $vs_signature))
            return ["ok" => false, "codigo" => 403, "erro" => "Assinatura inválida.", "json" => ""];

        $vn_timestamp = (int) $vs_timestamp;

        if (abs(time() - $vn_timestamp) > self::VALIDADE_SEGUNDOS)
            return ["ok" => false, "codigo" => 403, "erro" => "Assinatura expirada.", "json" => ""];

        $vs_json = self::payload_json($pa_body);

        if ($vs_json === false)
            return ["ok" => false, "codigo" => 400, "erro" => "Requisição inválida.", "json" => ""];

        $vs_esperada = hash_hmac("sha256", $vs_timestamp . "\n" . $vs_nonce . "\n" . $vs_json, (string) $ps_secret);

        if (!hash_equals($vs_esperada, $vs_signature))
            return ["ok" => false, "codigo" => 403, "erro" => "Assinatura inválida.", "json" => ""];

        if (!self::registrar_nonce($vs_timestamp, $vs_nonce))
            return ["ok" => false, "codigo" => 403, "erro" => "Requisição já utilizada.", "json" => ""];

        return ["ok" => true, "codigo" => 200, "erro" => "", "json" => $vs_json];
    }

    private static function diretorio_nonces(): string
    {
        return armazenamento_interno::diretorio("pasta_submissoes_nonces", "submissoes_nonces");
    }

    /**
     * Consome o nonce de forma atômica. Retorna false em replay.
     */
    private static function registrar_nonce(string $ps_timestamp, string $ps_nonce): bool
    {
        $vs_diretorio = self::diretorio_nonces();
        $vs_arquivo = $vs_diretorio . $ps_timestamp . "-" . $ps_nonce;

        $vh_handle = @fopen($vs_arquivo, "x");

        if ($vh_handle === false)
            return false;

        fwrite($vh_handle, (string) time());
        fclose($vh_handle);
        @chmod($vs_arquivo, 0600);

        $vn_limite = time() - (self::VALIDADE_SEGUNDOS * 4);

        foreach (glob($vs_diretorio . "*-*") ?: [] as $vs_nonce_arquivo)
        {
            if (is_file($vs_nonce_arquivo) && filemtime($vs_nonce_arquivo) < $vn_limite)
                @unlink($vs_nonce_arquivo);
        }

        return true;
    }
}
