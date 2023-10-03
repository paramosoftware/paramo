<?php

use PHPMailer\PHPMailer\PHPMailer;

class utils
{
    public static function get_thumb_pdf($ps_image_path): string
    {
        if (!file_exists($ps_image_path))
        {
            return "";
        }

        if (class_exists('Imagick'))
        {
            try
            {
                $img_data = new Imagick();

                $img_data->readImage($ps_image_path . "[0]");
                $img_data->setImageFormat("jpeg");

                return $img_data;
            }
            catch(Exception $e)
            {
                print $e->getMessage();
            }
        }
        
        return "";
    }

    public static function get_image($ps_image_path): string
    {
        if (!file_exists($ps_image_path))
        {
            return "";
        }

        try
        {      
            return file_get_contents($ps_image_path);
        }
        catch(Exception $e)
        {
            print $e->getMessage();
        }
        
        return "";
    }

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

    public static function get_file_base64($file_path): string
    {
        if (!file_exists($file_path))
        {
            return "";
        }
        $type = self::get_file_extension($file_path);
        $data = file_get_contents($file_path);
        return 'data:application/' . $type . ';base64,' . base64_encode($data);
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

    public static function get_embedded_media($url, $width = 190, $height = 100): string
    {
        $url = parse_url($url);
        $url["host"] = str_replace("www.", "", $url["host"]);

        if ($url["host"] == "youtube.com" || $url["host"] == "youtu.be")
        {
            if ($url["host"] == "youtube.com")
            {
                $video_id = explode("v=", $url["query"]);
            }
            else
            {
                $video_id = explode("/", $url["path"]);
            }
            $video_id = $video_id[1];

            return '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        else if ($url["host"] == "vimeo.com")
        {
            $video_id = explode("/", $url["path"]);
            return '<iframe src="https://player.vimeo.com/video/' . $video_id[1] . '" width="' . $width . '" height="' . $height . '" frameborder="0" allow="autoplay; fullscreen;" allowfullscreen></iframe>';
        }
        else if ($url["host"] == "soundcloud.com" || $url["host"] == "on.soundcloud.com")
        {
            $soundcloud_api_url = "https://soundcloud.com/oembed?url=" . $url["scheme"] . "://" . $url["host"] . $url["path"] . "&format=json" . "&maxwidth=" . $width . "&maxheight=" . $height;
            $soundcloud_api_response = file_get_contents($soundcloud_api_url);
            $soundcloud_api_response = json_decode($soundcloud_api_response, true);
            return $soundcloud_api_response["html"] ?? "";
        }

        return "";
    }

    public static function log(string $summary, string $stacktrace) : string
    {
        $logs_folder = config::get(["pasta_logs"]);
        $file = $logs_folder . date("Y-m-d") . ".log";
        $code = md5(uniqid(rand(), true));
        $code = substr($code, 0, 12);

        $stacktrace = str_replace("\r", "", $stacktrace);
        $stacktrace = str_replace("\n", "", $stacktrace);

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

        if ($vs_extensao == "pdf")
        {
            $vs_placeholder = "assets/img/placeholder-pdf.png";
        }
        elseif ($vs_folder == "videos")
        {
            $vs_placeholder = "assets/img/placeholder-video.png";
        }
        elseif ($vs_folder == "audios")
        {
            $vs_placeholder = "assets/img/placeholder-audio.png";
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

        if (in_array($ps_ext, ["jpg", "jpeg", "png", "gif", "pdf", "bmp", "svg", "tiff", "tif", "raw"])) {
            $vs_folder = "images";
        } elseif (in_array($ps_ext, ["mp4", "webm", "avi", "mov", "wmv", "flv", "mkv"])) {
            $vs_folder = "videos";
        } elseif (in_array($ps_ext, ["mp3", "m4a", "wav", "wma"])) {
            $vs_folder = "audios";
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

    public static function start_session(): void
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            $vs_session_name = strtolower(preg_replace("/[^A-Za-z0-9]/", "", config::get(["nome_instituicao"])));
            session_name($vs_session_name);
            session_start();
        }
    }

    public static function validate_user_session(): bool
    {
        return isset($_SESSION["usuario_token"]);
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), "", time() - 3600, "/");
        header("Location: login.php");
    }

}