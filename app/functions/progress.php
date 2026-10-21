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

// Acompanhamento de exportações: devolve também as partes já concluídas
if ($_POST["export"] ?? false)
{
    header("Content-Type: application/json");

    $vs_manifest_path = $vs_temp_folder . $vs_file . ".parts.json";

    $vb_encontrou = file_exists($vs_file_path) || file_exists($vs_manifest_path);
    $vn_progress = file_exists($vs_file_path) ? (float) file_get_contents($vs_file_path) : 0;

    $va_manifesto = [];

    if (file_exists($vs_manifest_path))
    {
        $va_manifesto = json_decode((string) file_get_contents($vs_manifest_path), true) ?: [];
    }

    if ($vb_encontrou)
    {
        unset($_SESSION[$vs_file]);
    }
    else
    {
        $_SESSION[$vs_file] = (($_SESSION[$vs_file] ?? 0) + 1);
    }

    echo json_encode([
        "found" => $vb_encontrou,
        "progress" => (float) ($va_manifesto["progress"] ?? $vn_progress),
        "done" => (bool) ($va_manifesto["done"] ?? false),
        "cancelled" => (bool) ($va_manifesto["cancelled"] ?? false),
        "parts" => is_array($va_manifesto["parts"] ?? null) ? $va_manifesto["parts"] : [],
    ]);

    exit();
}

if (!file_exists($vs_file_path))
{
    $_SESSION[$vs_file] = $_SESSION[$vs_file] ? $_SESSION[$vs_file] + 1 : 1;
    if ($_SESSION[$vs_file] > 50)
    {
        echo "Não foi possível encontrar o arquivo.";
        unset($_SESSION[$vs_file]);
    }
    exit();
}

$vn_progress = file_get_contents($vs_file_path);

echo $vn_progress;

