<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

$body = session::get_body();

$vs_id_objeto = $body['obj'] ?? null;
$vb_requisicao_post = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST";
$va_objetos_permitidos = config::get(["submissao_formulario_objetos_permitidos"]);

$vs_token_expected = config::get(["submissao_formulario_token"]) ?? '';

if (empty($vs_token_expected)) 
{
    return session::send_response(["error" => true, "errors" => "Token de submissão não configurado."], 403);
}

$vs_token_recebido = $body['token'] ?? null;

if ($vs_token_recebido !== $vs_token_expected) 
{
    return session::send_response(["error" => true, "errors" => "Token de submissão inválido."], 403);
}

unset($body['token']);

if (!$vb_requisicao_post || !in_array($vs_id_objeto, $va_objetos_permitidos))
{
    return session::send_response(["error" => true, "errors" => "Requisição inválida."], 400);
}

$vo_objeto = new $vs_id_objeto();

if (method_exists($vo_objeto, "criar_registro_de_submissao_externa"))
{
    $va_response = $vo_objeto->criar_registro_de_submissao_externa($body);
    session::send_response($va_response);
}
else
{
    session::send_response([
        "error" => true,
        "errors" => "O objeto $vs_id_objeto não possui o método criar_registro_de_submissao_externa."
      ], 400);
}