<?php

use PHPMailer\PHPMailer\PHPMailer;

class utils
{
    public static function get_image_base64($image_path, $ps_formato='image'): string
    {
        if (!file_exists($image_path))
        {
            return "";
        }
        $type = self::get_file_extension($image_path);
        $data = file_get_contents($image_path);
        return 'data:' . $ps_formato . '/' . $type . ';base64,' . base64_encode($data);
    }

    public static function get_file_extension($file_path)
    {
        return pathinfo($file_path, PATHINFO_EXTENSION);
    }

    public static function send_email(string $to, string $to_name, string $subject, string $message) : bool
    {

        require_once dirname(__FILE__) . "/../vendors/PHPMailer/src/PHPMailer.php";
        require_once dirname(__FILE__) . "/../vendors/PHPMailer/src/SMTP.php";
        require_once dirname(__FILE__) . "/../vendors/PHPMailer/src/Exception.php";

        $from = config::get(["smtp_email"]);
        $password = config::get(["smtp_password"]);
        $host = config::get(["smtp_host"]);
        $port = config::get(["smtp_port"]);
        $from_name = config::get(["smtp_name"]);
        $image_footer_path = config::get(["smtp_email_footer"]);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $from;
            $mail->Password = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $port;

            $mail->setFrom($from, $from_name);
            $mail->addAddress($to, $to_name);
            $mail->addReplyTo($from, $from_name);

            $mail->isHTML();
            $mail->Subject = $subject;
            if ($image_footer_path != "") {
                $mail->addEmbeddedImage($image_footer_path, 'rodape-email', 'custom-email-footer.png');
                $message .= '<img src="cid:rodape-email" alt="rodape email" width="400">';
            }
            $mail->Body = $message;
            $mail->AltBody = $message;
            $mail->CharSet = 'UTF-8';
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function get_embedded_media($url, $width = 190, $height = 120): string
    {

        $context = stream_context_create(
            [
                "http" => [
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                ]
            ]
        );

        $url = parse_url($url);
        $url["host"] = str_replace("www.", "", $url["host"]);

        if (in_array($url["host"], ["youtube.com", "youtu.be", "vimeo.com"]))
        {

            $video_id = $url["host"] == "youtube.com" ? explode("v=", $url["query"])[1] : explode("/", $url["path"])[1];
            $src = $url["host"] == "vimeo.com" ? "https://player.vimeo.com/video/" . $video_id : "https://www.youtube.com/embed/" . $video_id;

            return '<iframe width="' . $width . '" height="' . $height . '" src="' . $src . '" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        else if (in_array($url["host"], ["open.spotify.com", "spotify.com", "spotify"]))
        {
            
            $parts = explode("/", $url["path"]);
            $type = $parts[count($parts) - 2];
            $id = $parts[count($parts) - 1];
            
            return '<iframe width="215" height="120" src="https://open.spotify.com/embed/' . $type . '/'  . $id . '?utm_source=oembed" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>';
        }
        else if (in_array($url["host"], ["soundcloud.com", "on.soundcloud.com"]))
        {
            $soundcloud_api_url = "https://soundcloud.com/oembed?url=" . $url["scheme"] . "://" . $url["host"] . $url["path"] . "&format=json" . "&maxwidth=" . $width . "&maxheight=" . $height;
            $soundcloud_api_response = file_get_contents($soundcloud_api_url, false, $context);
            $soundcloud_api_response = json_decode($soundcloud_api_response, true);

            if (isset($soundcloud_api_response["html"]))
            {
                return $soundcloud_api_response["html"];
            }
        }
        else if (in_array($url["host"], ["drive.google.com", "docs.google.com"]))
        {
            preg_match('/\/d\/(.+?)\//', $url["path"], $matches);
            $file_id = $matches[1];

            $src = "https://drive.google.com/file/d/" . $file_id . "/preview";
            $thumbnail = "https://drive.google.com/thumbnail?authuser=0&id=" . $file_id;

            $response = file_get_contents($src, false, $context);
            $response_ok = strpos($http_response_header[0], "200") !== false;
            preg_match('/"docs-dm":"(.+?)"/', $response, $matches);
            $type = $matches[1] ? explode("/", $matches[1])[0] : null;


            if ($response_ok && !in_array($type, ["audio", "video"]))
            {
                $html = '<span href="' . $src . '" target="_blank">';
                $html .= '<img src="' . $thumbnail . '" class="iframe-viewer" onerror="this.onerror=null;this.src=\'assets/img/placeholder-drive.png\'" width="100%">';
                $html .= '</span>';

            }
            elseif ($response_ok)
            {
                $html = '<div style="overflow-x: auto; width: 100%;">';
                $html .= '<iframe src="' . $src . '" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                $html .= '</div>';
            }
            else
            {
                $html = '<img src="assets/img/placeholder-drive.png">';
            }

            return $html;
        }

        return '<img src="assets/img/placeholder-link.png">';
    }

    public static function log(string $summary, $stacktrace) : string
    {
        $logs_folder = config::get(["pasta_logs"]);
        $file = $logs_folder . date("Y-m-d") . ".log";
        $code = md5(uniqid(rand(), true));
        $code = substr($code, 0, 12);

        if (is_array($stacktrace))
        {
            $stacktrace = implode(" ", $stacktrace);
        }

        $stacktrace = str_replace("\r", " ", $stacktrace);
        $stacktrace = str_replace("\n", " ", $stacktrace);

        $log = date("Y-m-d H:i:s") . "*-*" . $code . "*-*" . $summary . "*-*" . $stacktrace . "\n";
        file_put_contents($file, $log, FILE_APPEND);

        return $code;
    }

    public static function sanitize_file_name($file_name): string
    {
        $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file_name);
        $file = mb_ereg_replace("([\.]{2,})", '', $file);
        return ltrim($file, '.');
    }

    public static function sanitize_string($unsafe_string): string {
        require_once dirname(__FILE__) . '/../vendors/htmlpurifier/library/HTMLPurifier.auto.php';

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        return trim($purifier->purify($unsafe_string));
    }

    public static function get_file_url($ps_file, $ps_size = null): string
    {
        return "functions/serve_file.php?file=" . $ps_file . "&size=" . $ps_size;
    }

    public static function get_img_html_element($ps_image_path, $ps_size = null, $ps_class = null, $ps_id = null, $ps_alt = null): string
    {

        $vs_src = self::get_file_url($ps_image_path, $ps_size);

        $vs_placeholder = self::get_placeholder($ps_image_path);

        $html = '<img ';
        $html .= 'src="' . $vs_src . '" ';
        if ($ps_id != null)
        {
            $html .= 'id="' . $ps_id . '" ';
        }
        if ($ps_class != null)
        {
            $html .= 'class="' . $ps_class . '" ';
        }
        if ($ps_alt != null)
        {
            $html .= 'alt="' . $ps_alt . '" ';
        }
        $html .= 'onerror="this.onerror=null;this.src=\''. $vs_placeholder . '\'"';
        $html .= ' />';

        return $html;
    }

    public static function get_placeholder($ps_image_path): string
    {
        $vs_extensao = pathinfo($ps_image_path, PATHINFO_EXTENSION);
        $vs_folder = self::get_media_folder($vs_extensao);
        $vs_folder = substr($vs_folder, 0, -1);

        if ($vs_extensao == "pdf")
        {
            $vs_placeholder = "assets/img/placeholder-pdf.png";
        }
        elseif (file_exists("assets/img/placeholder-" . $vs_folder . ".png"))
        {
            $vs_placeholder = "assets/img/placeholder-" . $vs_folder . ".png";
        }
        else
        {
            $vs_placeholder = "assets/img/placeholder.png";
        }

        return $vs_placeholder;
    }

    public static function get_media_folder($ps_ext): string
    {
        $vs_folder = "";

        $media_types = config::get(["media_types"]);

        foreach ($media_types as $vs_mime_type => $va_media_type)
        {
            if ($va_media_type["format"] == $ps_ext)
            {
                $vs_folder = $va_media_type["folder"];
                break;
            }
        }

        return $vs_folder;
    }

    public static function get_media_html_element($ps_object_path, $ps_id = null): string
    {

        $vs_src = self::get_file_url($ps_object_path);
        $va_mime_types = config::get(["extensoes_permitidas"]);
        $vs_extensao = pathinfo($ps_object_path, PATHINFO_EXTENSION);
        $vs_mime_type = $va_mime_types[$vs_extensao] ?? "application/octet-stream";

        if (strpos($vs_mime_type, "audio") !== false)
        {
            $html = '<audio controls ';
            if ($ps_id != null)
            {
                $html .= 'id="' . $ps_id . '" ';
            }
            $html .= ' style="width: auto;">';
            $html .= '<source src="' . $vs_src . '" type="' . $vs_mime_type . '">';
            $html .= 'Seu navegador não suporta a reprodução de áudio.';
            $html .= '</audio>';
        }
        else if (strpos($vs_mime_type, "video") !== false)
        {
            $html = '<video controls ';
            if ($ps_id != null)
            {
                $html .= 'id="' . $ps_id . '" ';
            }
            $html .= '>';
            $html .= '<source src="' . $vs_src . '" type="' . $vs_mime_type . '">';
            $html .=  'Seu navegador não suporta a reprodução de vídeo.';
            $html .= '</video>';
        } else {
            $html = '<object data="' . $vs_src . '" type="' . $vs_mime_type . '" width="100%" height="100%">';
            $html .= '</object>';
        }


        return $html;
    }

    public static function clear_temp_folder($ps_time = "1 minute", $part_filename = ""): void
    {
        $va_files = scandir(config::get(["pasta_media", "temp"]));

        foreach ($va_files as $vs_file)
        {
            if (in_array($vs_file, [".", "..", ".gitignore"]))
            {
                continue;
            }

            if ($part_filename != "" && strpos($vs_file, $part_filename) === false)
            {
                continue;
            }

            if (filemtime(config::get(["pasta_media", "temp"]) . $vs_file) < strtotime($ps_time))
            {
                unlink(config::get(["pasta_media", "temp"]) . $vs_file);
            }
        }
    }

    public static function callback_progress($vs_file_name, $vn_progress)
    {
        $vs_temp = config::get(["pasta_media", "temp"]);

        $vs_file_path = $vs_temp . $vs_file_name . ".progress";

        file_put_contents($vs_file_path, $vn_progress);

        if (file_exists($vs_temp . $vs_file_name . ".stop")) {
            utils::clear_temp_folder("1 minute", $vs_file_name);
            exit();
        }
    }


}