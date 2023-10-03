<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../../autoload.php";
}

utils::start_session();
if (!utils::validate_user_session())
{
    send_not_found_response();
}

$vs_file = $_GET['file'] ?? send_not_found_response();
$vs_size = $_GET['size'] ?? null;

$vs_file_path = get_file_path($vs_file, $vs_size);

if (!file_exists($vs_file_path))
{
    send_not_found_response();
}

send_file_response($vs_file_path);

function get_file_path($ps_file, $ps_size = null): string
{
    $vs_ext = strtolower(pathinfo($ps_file, PATHINFO_EXTENSION));
    $vs_folder = utils::get_media_folder($vs_ext);

    if ($vs_folder == "")
    {
        return "";
    }

    if ($vs_folder != "images")
    {
        $vs_file_path = config::get(["pasta_media", $vs_folder]) . $ps_file;
        if (file_exists($vs_file_path))
        {
            return $vs_file_path;
        }
    }

    return get_image_path($ps_file, $ps_size);
}

function get_image_path($ps_file, $ps_size = null): string
{

    $va_pasta_images = config::get(["pasta_media", "images"]);
    krsort($va_pasta_images);
    $vs_file_path = "";

    if (strpos($ps_file, ".pdf") !== false && $ps_size != "large")
    {
        $ps_file = str_replace(".pdf", ".jpg", $ps_file);
    }

    if ($ps_size)
    {
        $vs_file_path = $va_pasta_images[$ps_size] . $ps_file;
        if (file_exists($vs_file_path))
        {
            return $vs_file_path;
        }
    }

    foreach ($va_pasta_images as $vs_pasta)
    {
        $vs_file_path = $vs_pasta . $ps_file;

        if (file_exists($vs_file_path))
        {
            return $vs_file_path;
        }
    }

    return $vs_file_path;
}

function send_not_found_response() : void
{
    header("HTTP/1.0 404 Not Found");
    exit;
}

function send_file_response($ps_file_path) : void
{
    set_time_limit(0);
    ob_implicit_flush(1);

    $vs_content_type = mime_content_type($ps_file_path);
    header("Content-Type: " . $vs_content_type);
    header("Content-Length: " . filesize($ps_file_path));
    readfile($ps_file_path);

    if (ob_get_level())
    {
        ob_end_clean();
    }

    exit;
}










