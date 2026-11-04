<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

/**
 * Endpoint de confirmação de submissões externas, usado pelo portal público.
 *
 * O portal não grava nada: ele só repassa o token pendente. A confirmação
 * (registro + e-mail de notificação) continua acontecendo aqui, com o mesmo
 * token de assinatura das submissões de formulário — nenhuma credencial nova.
 */

$body = session::get_body();

$vb_requisicao_post = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST";

if (!$vb_requisicao_post)
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 405);
}

$vs_token_expected = config::get(["submissao_formulario_token"]) ?? '';

if (!submissao_assinatura::token_configurado($vs_token_expected))
{
    return session::send_response(["error" => true, "errors" => "Configuração de submissão indisponível."], 503);
}

# Enxurrada não autenticada (proteção do servidor, não limite por visitante):
# aqui REMOTE_ADDR é o servidor do portal. O limite por visitante vem depois da
# assinatura.
$vs_ip_origem = $_SERVER["REMOTE_ADDR"] ?? "desconhecido";

if (!limite_tentativas::permitir("confirmar_submissao_flood:" . $vs_ip_origem, 600, 900))
{
    return session::send_response(["error" => true, "errors" => "Muitas tentativas. Tente novamente mais tarde."], 429);
}

if (!is_array($body))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$va_assinatura = submissao_assinatura::verificar($body, $vs_token_expected);

if (!$va_assinatura["ok"])
{
    return session::send_response(["error" => true, "errors" => $va_assinatura["erro"]], $va_assinatura["codigo"]);
}

# O corpo só é confiável depois da assinatura: aqui o limite passa a ser por
# visitante, com o IP que o portal informou no payload assinado.
if (config::get(["submissao_limite_por_ip"]) ?? true)
{
    $vs_ip = (string) ($body["ip_origem"] ?? "");

    if (!filter_var($vs_ip, FILTER_VALIDATE_IP))
        $vs_ip = $_SERVER["REMOTE_ADDR"] ?? "desconhecido";

    if (!limite_tentativas::permitir("confirmar_submissao:" . $vs_ip, 30, 900))
    {
        return session::send_response(["error" => true, "errors" => "Muitas tentativas. Tente novamente mais tarde."], 429);
    }
}

$vs_acao = (string) ($body["acao"] ?? "");
$vs_token_pendente = (string) ($body["token_pendente"] ?? "");

if (!in_array($vs_acao, ["espiar", "confirmar"], true) || !submissao_pendente::token_valido($vs_token_pendente))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

if ($vs_acao === "espiar")
{
    $va_pendente = submissao_pendente::espiar($vs_token_pendente);

    if ($va_pendente === null)
    {
        return session::send_response(["error" => true, "errors" => "Link inválido ou expirado."], 404);
    }

    # Só o necessário para a página mostrar de qual endereço se trata.
    return session::send_response([
        "success" => true,
        "email" => (string) ($va_pendente["email"] ?? ""),
        "nome" => (string) ($va_pendente["nome"] ?? ""),
    ]);
}

$va_registro = submissao_pendente::consumir($vs_token_pendente);

if ($va_registro === null)
{
    return session::send_response(["error" => true, "errors" => "Link inválido ou expirado."], 404);
}

$vs_id_objeto = (string) ($va_registro["objeto"] ?? "");
$va_objetos_permitidos = config::get(["submissao_formulario_objetos_permitidos"]);

// O token só pode executar a submissão encenada, para um objeto permitido.
if (!in_array($vs_id_objeto, is_array($va_objetos_permitidos) ? $va_objetos_permitidos : [], true))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$vo_objeto = autorizacao::resolver_objeto($vs_id_objeto, is_array($va_objetos_permitidos) ? $va_objetos_permitidos : []);

if (!($vo_objeto instanceof objeto_base) || !method_exists($vo_objeto, "criar_registro_de_submissao_externa_confirmada"))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$va_payload = $va_registro["payload"] ?? null;
$vs_email = (string) ($va_registro["email"] ?? "");

if (!is_array($va_payload) || !hash_equals($vs_email, strtolower(trim((string) ($va_payload["email"] ?? "")))))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$va_resultado = $vo_objeto->criar_registro_de_submissao_externa_confirmada($va_payload);

session::send_response($va_resultado, !empty($va_resultado["error"]) ? 400 : 200);
