<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

require_once dirname(__FILE__) . "/file_handler.php";

if (!isset($vb_ignorar_autenticacao))
{
    session::start_session();
    if (!isset($_SESSION["usuario_token"]))
    {
        if (!session::get_logged_user())
        {
            send_not_found_response();
        }
    }

    require_once dirname(__FILE__) . "/autenticar_usuario.php";
}

$vs_file = $_GET['file'] ?? "";
$vs_size = $_GET['size'] ?? null;
$vs_folder = $_GET['folder'] ?? null;
$vb_force_download = !empty($_GET['download']);
$vs_download_file_name = $_GET['name'] ?? "";
$vs_download_file_name = preg_replace('/[^\p{L}\p{N} ]+/u', '', $vs_download_file_name);
$vs_download_file_name = substr($vs_download_file_name, 0, 50);

$vs_file = validar_nome_arquivo_media($vs_file);

if ($vs_file === "" || ($vs_folder !== null && !in_array($vs_folder, media_folder_keys(), true)))
{
    send_not_found_response();
}

$vs_file_path = get_file_path($vs_file, $vs_size, $vs_folder);

if ($vs_file_path === "" || !is_file($vs_file_path))
{
    send_not_found_response();
}

// Arquivos temporários pertencem ao usuário que gerou a tarefa.
if ($vs_folder === "temp")
{
    if (!session::autorizar_arquivo_temporario($vs_file))
        send_not_found_response();
}
else
{
    // Arquivos de mídia precisam estar vinculados a um representante digital
    // cujo registro o usuário tenha permissão de leitura.
    $vo_representante = new representante_digital();
    $va_representantes = $vo_representante->ler_lista(
        ["representante_digital_path" => $vs_file],
        "lista",
        0,
        3
    );

    $vb_autorizado = false;

    foreach ($va_representantes as $va_representante)
    {
        $vn_registro_codigo = (int) ($va_representante["representante_digital_registro_codigo"] ?? 0);
        $vn_recurso_codigo = (int) ($va_representante["representante_digital_recurso_sistema_codigo"] ?? 0);

        $vo_objeto = autorizacao::resolver_objeto_por_recurso($vn_recurso_codigo);

        if (autorizacao::autorizar_leitura_registro($vo_objeto, $vn_registro_codigo))
        {
            $vb_autorizado = true;
            break;
        }
    }

    if (!$vb_autorizado)
        send_not_found_response();
}

send_file_response($vs_file_path, $vb_force_download, $vs_download_file_name);
