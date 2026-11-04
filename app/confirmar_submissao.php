<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../autoload.php";
}

$vs_token = (string) ($_POST["token"] ?? ($_GET["token"] ?? ""));
$vs_ip = $_SERVER["REMOTE_ADDR"] ?? "desconhecido";

if (!limite_tentativas::permitir("confirmar_submissao:" . $vs_ip, 20, 900))
{
    http_response_code(429);
    exit("Muitas tentativas. Tente novamente mais tarde.");
}

$va_pendente = submissao_pendente::espiar($vs_token);

if ($va_pendente === null)
{
    http_response_code(404);
    exit("Link inválido ou expirado.");
}

if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST")
{
    $vs_token_html = htmlspecialchars($vs_token, ENT_QUOTES, "UTF-8");
    $vs_email_html = htmlspecialchars((string) ($va_pendente["email"] ?? ""), ENT_QUOTES, "UTF-8");

    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html lang=\"pt-br\"><head><meta charset=\"utf-8\">";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    echo "<title>Confirmar pedido de pesquisa</title></head><body>";
    echo "<h1>Confirmar pedido de pesquisa</h1>";
    echo "<p>Confirme que o endereço <strong>" . $vs_email_html . "</strong> pertence a você.</p>";
    echo "<form method=\"post\" action=\"confirmar_submissao.php\">";
    echo "<input type=\"hidden\" name=\"token\" value=\"" . $vs_token_html . "\">";
    echo "<button type=\"submit\">Confirmar pedido</button>";
    echo "</form></body></html>";
    exit();
}

$va_registro = submissao_pendente::consumir($vs_token);

if ($va_registro === null)
{
    http_response_code(404);
    exit("Link inválido ou expirado.");
}

$vs_id_objeto = (string) ($va_registro["objeto"] ?? "");
$va_objetos_permitidos = config::get(["submissao_formulario_objetos_permitidos"]);

// O token só pode executar a submissão encenada, para um objeto permitido.
if (!in_array($vs_id_objeto, is_array($va_objetos_permitidos) ? $va_objetos_permitidos : [], true))
{
    http_response_code(400);
    exit("Solicitação inválida.");
}

$vo_objeto = autorizacao::resolver_objeto($vs_id_objeto, is_array($va_objetos_permitidos) ? $va_objetos_permitidos : []);

if (!($vo_objeto instanceof objeto_base) || !method_exists($vo_objeto, "criar_registro_de_submissao_externa_confirmada"))
{
    http_response_code(400);
    exit("Solicitação inválida.");
}

$va_payload = $va_registro["payload"] ?? null;
$vs_email = (string) ($va_registro["email"] ?? "");

if (!is_array($va_payload) || !hash_equals($vs_email, strtolower(trim((string) ($va_payload["email"] ?? "")))))
{
    http_response_code(400);
    exit("Solicitação inválida.");
}

$va_resultado = $vo_objeto->criar_registro_de_submissao_externa_confirmada($va_payload);

header("Content-Type: text/html; charset=UTF-8");

if (!empty($va_resultado["error"]))
{
    http_response_code(400);
    echo "<!DOCTYPE html><html lang=\"pt-br\"><head><meta charset=\"utf-8\"><title>Pedido de pesquisa</title></head><body>";
    echo "<h1>Não foi possível concluir o pedido</h1>";
    echo "<p>" . htmlspecialchars((string) ($va_resultado["errors"] ?? "Tente novamente."), ENT_QUOTES, "UTF-8") . "</p>";
    echo "</body></html>";
    exit();
}

echo "<!DOCTYPE html><html lang=\"pt-br\"><head><meta charset=\"utf-8\"><title>Pedido de pesquisa</title></head><body>";
echo "<h1>Pedido confirmado</h1>";
echo "<p>" . htmlspecialchars((string) ($va_resultado["msg"] ?? "Pedido de pesquisa criado com sucesso."), ENT_QUOTES, "UTF-8") . "</p>";
echo "</body></html>";
