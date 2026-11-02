<?php

function media_folder_keys(): array
{
    return ["documents", "downloads", "videos", "audios", "temp", "images"];
}

/**
 * Valida um nome de arquivo vindo do request. Rejeita caminhos absolutos,
 * separadores, "..", bytes nulos e nomes ocultos.
 */
function validar_nome_arquivo_media($ps_file): string
{
    if (!is_string($ps_file) || $ps_file === "")
        return "";

    if (strpos($ps_file, "\0") !== false)
        return "";

    if (strpos($ps_file, "/") !== false || strpos($ps_file, "\\") !== false)
        return "";

    if (preg_match('/\A[A-Za-z]:/', $ps_file))
        return "";

    if ($ps_file === "." || $ps_file === ".." || strpos($ps_file, "..") !== false)
        return "";

    if ($ps_file[0] === ".")
        return "";

    if (basename($ps_file) !== $ps_file)
        return "";

    return $ps_file;
}

function caminho_dentro_da_pasta(string $ps_file_path, string $ps_base): bool
{
    $vs_base_real = realpath($ps_base);
    $vs_file_real = realpath($ps_file_path);

    if ($vs_base_real === false || $vs_file_real === false)
        return false;

    $vs_base_real = rtrim($vs_base_real, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    return strpos($vs_file_real, $vs_base_real) === 0;
}

function pasta_media_permitida($ps_folder): string
{
    if (!is_string($ps_folder) || !in_array($ps_folder, media_folder_keys(), true) || $ps_folder === "images")
        return "";

    $vs_pasta = config::get(["pasta_media", $ps_folder]);

    return is_string($vs_pasta) ? $vs_pasta : "";
}

function get_file_path($ps_file, $ps_size = null, $ps_folder = null, $ps_original_file_name = null): string
{
    $vs_file = validar_nome_arquivo_media($ps_file);

    if ($vs_file === "")
        return "";

    $vs_ext = strtolower(pathinfo($vs_file, PATHINFO_EXTENSION));
    $vs_folder = $ps_folder ?? utils::get_media_folder($vs_ext);

    if ($vs_folder == "" || !in_array($vs_folder, media_folder_keys(), true))
    {
        return "";
    }

    if ($vs_folder != "images")
    {
        $vs_pasta = pasta_media_permitida($vs_folder);

        if ($vs_pasta === "")
            return "";

        $vs_file_path = $vs_pasta . $vs_file;

        if (caminho_dentro_da_pasta($vs_file_path, $vs_pasta) && is_file($vs_file_path))
        {
            return $vs_file_path;
        }
    }

    return get_image_path($vs_file, $ps_size, $ps_original_file_name);
}

function get_image_path($ps_file, $ps_size = null, $ps_original_file_name = null): string
{
    $vs_file = validar_nome_arquivo_media($ps_file);

    if ($vs_file === "")
        return "";

    $va_pasta_images = config::get(["pasta_media", "images"]);
    krsort($va_pasta_images);
    $vs_file_path = "";

    if (strpos($vs_file, ".pdf") !== false && $ps_size != "original")
    {
        $vs_file = str_replace(".pdf", ".jpg", $vs_file);
    }

    if ($ps_size)
    {
        if ($ps_size == "original")
        {
            $vs_file_path = $va_pasta_images[$ps_size] . $vs_file;

            if (caminho_dentro_da_pasta($vs_file_path, $va_pasta_images[$ps_size]) && is_file($vs_file_path))
            {
                return $vs_file_path;
            }
            elseif (isset($ps_original_file_name))
            {
                $vs_original_file_name_extension = strtolower(pathinfo($ps_original_file_name, PATHINFO_EXTENSION));
                $vs_file_hash = pathinfo($vs_file, PATHINFO_FILENAME);

                $vs_file_path = $va_pasta_images[$ps_size] . $vs_file_hash . "." . $vs_original_file_name_extension;

                if (caminho_dentro_da_pasta($vs_file_path, $va_pasta_images[$ps_size]) && is_file($vs_file_path))
                    return $vs_file_path;
                else
                    $ps_size == "large";
            }
            else
                $ps_size == "large";
        }

        if (isset($va_pasta_images[$ps_size]))
        {
            $vs_file_path = $va_pasta_images[$ps_size] . $vs_file;

            if (caminho_dentro_da_pasta($vs_file_path, $va_pasta_images[$ps_size]) && is_file($vs_file_path))
            {
                return $vs_file_path;
            }
        }
    }

    foreach ($va_pasta_images as $vs_pasta)
    {
        $vs_file_path = $vs_pasta . $vs_file;

        if (caminho_dentro_da_pasta($vs_file_path, $vs_pasta) && is_file($vs_file_path))
        {
            return $vs_file_path;
        }
    }

    return "";
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