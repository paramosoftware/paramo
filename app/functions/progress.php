<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

session::start_session();
if (!isset($_SESSION["usuario_token"]))
{
    if (!session::get_logged_user())
    {
        exit();
    }
}

$vs_temp_folder = config::get(["pasta_media", "temp"]);
$vs_file = $_POST['file'];

if ($_POST["stop"] ?? false)
{
    $vs_file_path = $vs_temp_folder . $vs_file . ".stop";
    file_put_contents($vs_file_path, "");
    echo "OK";
    exit();
}


$vs_file_path = $vs_temp_folder . $vs_file . ".progress";

if (!file_exists($vs_file_path))
{
    exit();
}

$vn_progress = file_get_contents($vs_file_path);

echo $vn_progress;

