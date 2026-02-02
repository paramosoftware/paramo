<?php

if (!defined("AUTOLOAD")) {
    require_once dirname(__FILE__) . "/../../autoload.php";
}
$body = get_body();

if($errors = session::validate_data(
    $body,
    ["obj" => ["string", "required"]],
    ["obj" => ["string" => "O valor obj é obrigatório ser uma string"]]
)){
    if(isset($errors["error"]))
        return session::send_response($errors, 400);
}

$vs_id_objeto = $body['obj'];

if (!object_authorized($vs_id_objeto) || $_SERVER['REQUEST_METHOD'] != "POST")
    return session::send_response(["error" => true, "errors" => "Unauthorized access."], 401);

$vo_objeto = new $vs_id_objeto();

$vo_objeto->create_($body);

function object_authorized(string $ps_id_objeto)
{
    $vs_id_objetos = config::get(["OBJETOS_PERMITIDOS"]);
    if (gettype($vs_id_objetos) == "string")
        return $vs_id_objetos == $ps_id_objeto;
    elseif (gettype($vs_id_objetos) == "array")
        return in_array($ps_id_objeto, $vs_id_objetos);
    return false;
}

function get_body(): array
{
    $body = file_get_contents("php://input");
    if (str_contains($body, "=")) {
        parse_str($body, $output);
        return $output;
    } else if (str_contains($body, ":") && $body_ = json_decode($body, true)) {
        return $body_;
    } else if (!empty($_POST))
        return $_POST;
    return [];
}