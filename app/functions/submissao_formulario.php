<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

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

$vs_ip = $_SERVER["REMOTE_ADDR"] ?? "desconhecido";

if (!limite_tentativas::permitir("submissao_formulario:" . $vs_ip, 60, 60))
{
    return session::send_response(["error" => true, "errors" => "Muitas requisições. Tente novamente em instantes."], 429);
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

// A partir daqui só os dados da submissão são repassados ao objeto.
unset($body["token"], $body["timestamp"], $body["nonce"], $body["signature"]);

$vs_id_objeto = $body['obj'] ?? null;
$va_objetos_permitidos = config::get(["submissao_formulario_objetos_permitidos"]);

if (!in_array($vs_id_objeto, is_array($va_objetos_permitidos) ? $va_objetos_permitidos : [], true))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$vo_objeto = autorizacao::resolver_objeto($vs_id_objeto, is_array($va_objetos_permitidos) ? $va_objetos_permitidos : []);

if (!($vo_objeto instanceof objeto_base) || !method_exists($vo_objeto, "criar_registro_de_submissao_externa"))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$va_response = $vo_objeto->criar_registro_de_submissao_externa($body);
session::send_response($va_response);
