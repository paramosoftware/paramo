<?php

use PHPMailer\PHPMailer\PHPMailer;

class utils
{
    public const FORMATO_EXPORTACAO_CSV = "csv";
    public const FORMATO_EXPORTACAO_XLSX = "xlsx";
    public const EXTENSAO_EXPORTACAO_CSV = "." . self::FORMATO_EXPORTACAO_CSV;
    public const EXTENSAO_EXPORTACAO_XLSX = "." . self::FORMATO_EXPORTACAO_XLSX;

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

    public static function send_email(
      string $to,
      string $to_name,
      string $subject,
      string $message,
      array $cc = [],
      array $reply_to = [],
    ) : bool
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
        $logo_path = "../" . config::get(["logo"]);

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

            foreach ($cc as $cc_email => $cc_name)
            {
                $mail->addCC($cc_email, $cc_name);
            }

            if (!empty($reply_to))
            {
                foreach ($reply_to as $reply_email => $reply_name)
                {
                    $mail->addReplyTo($reply_email, $reply_name);
                }
            }
            else
            {
                $mail->addReplyTo($from, $from_name);
            }

            $mail->isHTML();
            $mail->Subject = $subject;
            
            if(!empty($logo_path))
                $mail->addEmbeddedImage($logo_path, "logo-image", "custom-email-logo.png");

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

    /**
     * Remove arquivos antigos da pasta temp.
     *
     * Por padrão as exportações ("exportar-") são preservadas, para que os
     * arquivos gerados continuem disponíveis para download mesmo quando outra
     * operação (impressão, importação, cancelamento) limpa a pasta.
     * Para limpar as próprias exportações, informe um prefixo em $part_filename
     * ou uma lista vazia em $pa_prefixos_preservados.
     */
    public static function clear_temp_folder($ps_time = "1 minute", $part_filename = "", $pa_prefixos_preservados = null): void
    {
        if ($pa_prefixos_preservados === null)
        {
            $pa_prefixos_preservados = ($part_filename == "") ? ["exportar-"] : [];
        }

        $vs_temp = config::get(["pasta_media", "temp"]);
        $va_files = scandir($vs_temp);

        foreach ($va_files as $vs_file)
        {
            if (in_array($vs_file, [".", "..", ".gitignore"]))
            {
                continue;
            }

            $vb_preservar = false;

            foreach ($pa_prefixos_preservados as $vs_prefixo)
            {
                if ($vs_prefixo !== "" && strpos($vs_file, $vs_prefixo) === 0)
                {
                    $vb_preservar = true;
                    break;
                }
            }

            if ($vb_preservar)
            {
                continue;
            }

            if ($part_filename != "" && strpos($vs_file, $part_filename) === false)
            {
                continue;
            }

            if (filemtime($vs_temp . $vs_file) < strtotime($ps_time))
            {
                unlink($vs_temp . $vs_file);
            }
        }
    }

    /**
     * Publica o andamento de uma tarefa.
     *
     * Quando $pa_partes_exportacao é um array, também grava o manifesto
     * ({arquivo}.parts.json) com a lista de arquivos já concluídos, permitindo
     * que a interface exiba cada parte assim que ela fica pronta.
     */
    public static function callback_progress($vs_file_name, $vn_progress, $pa_partes_exportacao = null, $pb_exportacao_concluida = false, $pb_exportacao_cancelada = false)
    {
        $vs_temp = config::get(["pasta_media", "temp"]);

        $vs_file_path = $vs_temp . $vs_file_name . ".progress";

        file_put_contents($vs_file_path, $vn_progress);

        if (is_array($pa_partes_exportacao))
        {
            self::gravar_manifesto_exportacao($vs_file_name, $vn_progress, $pa_partes_exportacao, $pb_exportacao_concluida, $pb_exportacao_cancelada);
        }

        if (file_exists($vs_temp . $vs_file_name . ".stop"))
        {
            if (is_array($pa_partes_exportacao))
            {
                # Exportação cancelada: mantém os arquivos já gerados para download
                self::gravar_manifesto_exportacao($vs_file_name, $vn_progress, $pa_partes_exportacao, true, true);
                @unlink($vs_temp . $vs_file_name . ".stop");
                exit();
            }

            utils::clear_temp_folder("1 minute", $vs_file_name);
            exit();
        }
    }

    private static function gravar_manifesto_exportacao($ps_file_name, $pn_progress, $pa_partes, $pb_concluida, $pb_cancelada): void
    {
        $vs_temp = config::get(["pasta_media", "temp"]);

        $va_manifesto = [
            "progress" => (float) $pn_progress,
            "done" => (bool) $pb_concluida,
            "cancelled" => (bool) $pb_cancelada,
            "parts" => array_values($pa_partes),
        ];

        # Grava em arquivo temporário e renomeia, para que o polling nunca leia
        # um JSON pela metade
        $vs_arquivo_temporario = $vs_temp . $ps_file_name . ".parts.json.tmp";
        $vb_gravou = file_put_contents($vs_arquivo_temporario, json_encode($va_manifesto, JSON_UNESCAPED_UNICODE));

        if ($vb_gravou !== false)
        {
            @rename($vs_arquivo_temporario, $vs_temp . $ps_file_name . ".parts.json");
        }
    }

    /**
     * Prepara a miniatura reduzida usada na exportação.
     *
     * A versão reduzida é temporária: fica na pasta temp com o nome da tarefa
     * no prefixo, é reaproveitada entre as páginas da mesma exportação e
     * removida ao final (ver remover_thumbs_exportacao_temporarias).
     *
     * Quando a miniatura original já é menor que o tamanho desejado, ela é
     * usada diretamente, sem gerar arquivo temporário.
     */
    public static function preparar_thumb_exportacao($ps_representante_digital, $ps_tarefa = ""): string
    {
        $vs_file_name = basename(str_replace("\\", "/", (string) $ps_representante_digital));

        if ($vs_file_name === "")
        {
            return "";
        }

        $vn_lado = (int) (config::get(["tamanho_thumb_exportacao"]) ?? 120);

        if ($vn_lado <= 0)
        {
            $vn_lado = 120;
        }

        $vs_pasta_temporaria = rtrim((string) config::get(["pasta_thumb_exportacao"]), "/\\") . DIRECTORY_SEPARATOR;
        $vs_tarefa = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $ps_tarefa);
        $vs_arquivo_temporario = ($vs_tarefa !== "") ? $vs_pasta_temporaria . $vs_tarefa . "-thumb-" . $vs_file_name : "";

        if ($vs_arquivo_temporario !== "" && is_file($vs_arquivo_temporario))
        {
            return $vs_arquivo_temporario;
        }

        $vs_thumb = rtrim((string) config::get(["pasta_media", "images", "thumb"]), "/\\") . DIRECTORY_SEPARATOR . $vs_file_name;

        if (!is_file($vs_thumb))
        {
            return "";
        }

        $va_info_imagem = @getimagesize($vs_thumb);

        if ($va_info_imagem && max($va_info_imagem[0], $va_info_imagem[1]) <= $vn_lado)
        {
            return $vs_thumb;
        }

        if ($vs_arquivo_temporario !== "" && self::gerar_thumb_exportacao($vs_thumb, $vs_arquivo_temporario, $vn_lado))
        {
            return $vs_arquivo_temporario;
        }

        # Último recurso: usa a miniatura original
        return $vs_thumb;
    }

    /**
     * Remove as miniaturas temporárias criadas por uma exportação.
     */
    public static function remover_thumbs_exportacao_temporarias($ps_tarefa): void
    {
        $vs_tarefa = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $ps_tarefa);

        if ($vs_tarefa === "")
        {
            return;
        }

        $vs_pasta_temporaria = rtrim((string) config::get(["pasta_thumb_exportacao"]), "/\\") . DIRECTORY_SEPARATOR;

        foreach (glob($vs_pasta_temporaria . $vs_tarefa . "-thumb-*") ?: [] as $vs_arquivo)
        {
            @unlink($vs_arquivo);
        }
    }

    /**
     * Gera a miniatura reduzida usada nas exportações.
     *
     * É chamada sob demanda durante a exportação, sempre gravando em um arquivo
     * temporário que é removido quando a tarefa termina.
     */
    public static function gerar_thumb_exportacao($ps_origem, $ps_destino, $pn_lado = null, $pn_qualidade = 75): bool
    {
        if (!is_file($ps_origem))
        {
            return false;
        }

        $vn_lado = (int) ($pn_lado ?? config::get(["tamanho_thumb_exportacao"]) ?? 120);

        if ($vn_lado <= 0)
        {
            $vn_lado = 120;
        }

        $vs_pasta_destino = dirname($ps_destino);

        if (!is_dir($vs_pasta_destino) && !@mkdir($vs_pasta_destino, 0775, true) && !is_dir($vs_pasta_destino))
        {
            return false;
        }

        if (class_exists("Imagick"))
        {
            try
            {
                $vo_imagem = new Imagick($ps_origem);
                $vo_imagem->scaleImage($vn_lado, $vn_lado, true);
                $vo_imagem->setImageFormat("jpeg");
                $vo_imagem->setImageCompressionQuality($pn_qualidade);
                $vo_imagem->stripImage();
                $vo_imagem->writeImage($ps_destino);
                $vo_imagem->clear();

                return is_file($ps_destino);
            }
            catch (Exception $e)
            {
                self::log("erro", "Erro ao gerar miniatura de exportação: " . $e->getMessage() . " - " . $ps_origem);
            }
        }

        if (!function_exists("imagecreatefromjpeg"))
        {
            return false;
        }

        $va_info_imagem = @getimagesize($ps_origem);

        if (!$va_info_imagem)
        {
            return false;
        }

        switch ($va_info_imagem[2])
        {
            case IMAGETYPE_JPEG:
                $vr_origem = @imagecreatefromjpeg($ps_origem);
                break;

            case IMAGETYPE_PNG:
                $vr_origem = @imagecreatefrompng($ps_origem);
                break;

            case IMAGETYPE_GIF:
                $vr_origem = @imagecreatefromgif($ps_origem);
                break;

            default:
                $vr_origem = null;
                break;
        }

        if (!$vr_origem)
        {
            return false;
        }

        $vn_largura = imagesx($vr_origem);
        $vn_altura = imagesy($vr_origem);
        $vn_maior_lado = max($vn_largura, $vn_altura);
        $vn_fator = ($vn_maior_lado > 0) ? min(1, $vn_lado / $vn_maior_lado) : 1;

        $vn_nova_largura = max(1, (int) round($vn_largura * $vn_fator));
        $vn_nova_altura = max(1, (int) round($vn_altura * $vn_fator));

        $vr_destino = imagecreatetruecolor($vn_nova_largura, $vn_nova_altura);
        imagefill($vr_destino, 0, 0, imagecolorallocate($vr_destino, 255, 255, 255));
        imagecopyresampled($vr_destino, $vr_origem, 0, 0, 0, 0, $vn_nova_largura, $vn_nova_altura, $vn_largura, $vn_altura);

        $vb_gerou = imagejpeg($vr_destino, $ps_destino, $pn_qualidade);

        imagedestroy($vr_origem);
        imagedestroy($vr_destino);

        return (bool) $vb_gerou;
    }

    public static function is_phpspreadsheet_installed(): bool 
    {
        $autoloadPath = dirname(__FILE__) . "/../vendor/autoload.php";

        if (file_exists($autoloadPath)) 
        {
            require_once $autoloadPath;
        } 
        else 
        {
            return false;
        }

        if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) 
        {
            return false;
        }

        return true;

    }
}