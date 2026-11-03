<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

if (!isset($vb_ignorar_autenticacao))
{
    session::start_session();
    if (!isset($_SESSION["usuario_token"]))
    {
        if (!session::get_logged_user())
        {
            media_file_handler::send_not_found();
        }
    }

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    if (empty($vb_pode_ler) && empty($vb_pode_editar) && empty($vb_usuario_administrador))
    {
        media_file_handler::send_not_found();
    }
}

if (!isset($_GET['obj']) || !isset($_GET['cod']) || !isset($_GET['tipo_rd']))
{
    media_file_handler::send_not_found();
}

$vs_id_objeto = $_GET['obj'];
$pn_objeto_codigo = (int) $_GET['cod'];
$vs_tipo_rd = $_GET['tipo_rd'];

$vo_objeto = autorizacao::resolver_objeto($vs_id_objeto);

if (!($vo_objeto instanceof objeto_base) || !autorizacao::autorizar_leitura_registro($vo_objeto, $pn_objeto_codigo))
{
    media_file_handler::send_not_found();
}

$va_objeto = $vo_objeto->ler($pn_objeto_codigo, "ficha");

if (!is_array($va_objeto) || !count($va_objeto))
{
    media_file_handler::send_not_found();
}

if ($vs_tipo_rd == "representante_digital_codigo" || $vs_tipo_rd == "arquivo_download_codigo")
{
    $va_files = $va_objeto[$vs_tipo_rd] ?? [];
}
else
{
    media_file_handler::send_not_found();
}

if (!is_array($va_files) || !count($va_files))
{
    media_file_handler::send_not_found();
}

$vs_identificador = utils::sanitize_file_name((string) ($va_objeto['item_acervo_identificador'] ?? ""));

if ($vs_identificador === "")
{
    $vs_identificador = "registro-" . $pn_objeto_codigo;
}

$vs_temp_dir = config::get(["pasta_media", "temp"]);
$vs_zip_name = $vs_identificador . "_" . str_replace("_codigo", "", $vs_tipo_rd) . ".zip";
$vs_zip_path = rtrim($vs_temp_dir, "/\\") . DIRECTORY_SEPARATOR . $vs_zip_name;

$zip = new ZipArchive();
if (!is_writable($vs_temp_dir) || $zip->open($vs_zip_path, ZipArchive::CREATE) !== true)
{
    media_file_handler::send_not_found();
}

$counter = 1;
foreach ($va_files as $va_rd)
{
    $ps_size = 'original';

    $vs_file_path = media_file_handler::get_file_path($va_rd['representante_digital_path'], $ps_size, null, $va_rd['representante_digital_nome_original']);

    if (file_exists($vs_file_path))
    {
        $zip->addFile($vs_file_path, $vs_identificador . "-$counter." . pathinfo($vs_file_path, PATHINFO_EXTENSION));
        $counter++;
    }
}

if ($zip->numFiles > 0 && $zip->close())
{
    media_file_handler::send_file($vs_zip_path, pb_force_download: true, pb_unlink_afterwards: true);
}
else
{
    media_file_handler::send_not_found();
}
