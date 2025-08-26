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

$vs_file = $_GET['file'] ?? send_not_found_response();
$vs_size = $_GET['size'] ?? null;
$vs_folder = $_GET['folder'] ?? null;
$vb_force_download = $_GET['download'] ?? false;
$vs_download_file_name = $_GET['name'] ?? "";

$vs_file_path = get_file_path($vs_file, $vs_size, $vs_folder);

if (!file_exists($vs_file_path))
{
    send_not_found_response();
}

send_file_response($vs_file_path, $vb_force_download, $vs_download_file_name);