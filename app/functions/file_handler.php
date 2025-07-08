<?php

function get_file_path($ps_file, $ps_size = null, $ps_folder = null): string
{
    $vs_ext = strtolower(pathinfo($ps_file, PATHINFO_EXTENSION));
    $vs_folder = $ps_folder ?? utils::get_media_folder($vs_ext);

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

    if (strpos($ps_file, ".pdf") !== false && $ps_size != "original")
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

function send_file_response($ps_file_path, $pb_force_download = false, $ps_download_file_name = "", $pb_unlink_afterwards = false) : void
{
    set_time_limit(0);
    ob_implicit_flush(1);

    $vs_content_type = mime_content_type($ps_file_path);
    header("Content-Type: " . $vs_content_type);
    header("Content-Length: " . filesize($ps_file_path));

    if ($pb_force_download || (strpos($ps_file_path, "/temp/") !== false && filesize($ps_file_path) > 100000000))
    {
        if (trim($ps_download_file_name) != "") 
            $vs_ext = strtolower(pathinfo($ps_file_path, PATHINFO_EXTENSION));

        header("Content-Disposition: attachment; filename=" . ((trim($ps_download_file_name) != "") ? trim($ps_download_file_name) . "." . $vs_ext : basename($ps_file_path)));
        header('Content-Description: File Transfer');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
    }

    if (ob_get_level())
    {
        ob_end_clean();
    }

    readfile($ps_file_path);

    if($pb_unlink_afterwards)
    {
        unlink($ps_file_path);
    }

    exit;
}