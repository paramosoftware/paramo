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
}

if (!isset($_GET['obj']) || !isset($_GET['cod']) || !isset($_GET['tipo_rd']))
{
    send_not_found_response();
}

$vs_id_objeto = $_GET['obj'];
$pn_objeto_codigo = $_GET['cod'];
$vs_tipo_rd = $_GET['tipo_rd'];

$vo_objeto = new $vs_id_objeto;
$va_objeto = $vo_objeto->ler($pn_objeto_codigo, "ficha");


if ($vs_tipo_rd == "representante_digital_codigo" || $vs_tipo_rd == "arquivo_download_codigo")
{
    $va_files = $va_objeto[$vs_tipo_rd];
}
else
{
    send_not_found_response();
}

$vs_identificador = $va_objeto['item_acervo_identificador'];
$vs_temp_dir = config::get(["pasta_media", "temp"]);
$vs_zip_path = $vs_temp_dir . $vs_identificador . "_" . str_replace("_codigo", "", $vs_tipo_rd) . ".zip";

$zip = new ZipArchive();
if (!is_writable($vs_temp_dir) || $zip->open($vs_zip_path, ZipArchive::CREATE) !== true)
{
    send_not_found_response();
}

$counter = 1;
foreach ($va_files as $va_rd)
{
    $ps_size = 'original';
    if ($va_rd['representante_digital_formato'] != "pdf")
    {
        $ps_size = 'large';
    }
    $vs_file_path = get_file_path($va_rd['representante_digital_path'], $ps_size);
    if (file_exists($vs_file_path))
    {
        $zip->addFile($vs_file_path, $vs_identificador . "-$counter." . pathinfo($vs_file_path, PATHINFO_EXTENSION));
        $counter++;
    }
}

if ($zip->numFiles > 0 && $zip->close())
{
    send_file_response($vs_zip_path, pb_force_download: true, pb_unlink_afterwards: true);
}
else
{
    send_not_found_response();
}
