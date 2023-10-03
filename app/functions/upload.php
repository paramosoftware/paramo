<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    $vn_usuario_logado_codigo = $_SESSION["usuario_logado_codigo"];


    if (isset($_POST['google_drive_files_ids']) || isset($_POST["get_list_all_google_drive_files_ids"]) || isset($_POST["get_files_names_from_ids"]))
    {
        $client = google_drive::get_client();
        $service = google_drive::get_service($client);
        $token = google_drive::get_token($vn_usuario_logado_codigo, 'drive');
        $client->setAccessToken($token);

        if (!empty($token))
        {
            if ($client->isAccessTokenExpired())
            {
                $client = google_drive::refresh_token($client, 'drive', intval($vn_usuario_logado_codigo));
                $service = google_drive::get_service($client);
            }
        }
        else
        {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('error' => 'Erro ao obter token de acesso.')));
        }

        $create_agrupamentos = isset($_POST["create_agrupamentos"]) ? filter_var($_POST["create_agrupamentos"], FILTER_VALIDATE_BOOLEAN) : false;
        $max_folder_depth = isset($_POST["profundidade_pastas"]) ? intval($_POST["profundidade_pastas"]) : 2;

        if (isset($_POST['google_drive_files_ids']))
        {

            ini_set('max_execution_time', 0);

            $ids = $_POST['google_drive_files_ids'];
            $ids = explode('|', $ids);

            $i = 0;
            $files = [];
            foreach ($ids as $id)
            {
                $i++;

                $file = $service->files->get($id, array('fields' => 'id, name, size, mimeType, parents', 'supportsAllDrives' => true));

                if (is_google_drive_folder($file))
                {
                    $files = array_merge($files, download_drive_files_from_folder($service, $id, $client, $create_agrupamentos));
                }
                else
                {
                    $files[] = download_drive_file($file, $service, $client, $create_agrupamentos);
                }
            }
        }

        if (isset($_POST["get_files_names_from_ids"]))
        {
            get_files_names_from_ids($_POST["get_files_names_from_ids"], $service);
            exit();
        }

        if (isset($_POST["get_list_all_google_drive_files_ids"]))
        {
            get_list_files_ids_to_download($_POST["get_list_all_google_drive_files_ids"], $service, $create_agrupamentos, $max_folder_depth);
            exit();
        }
    }


    $_POST["usuario_logado_codigo"] = $vn_usuario_logado_codigo;
    $vs_id_objeto_tela = $_POST['obj'] ?? "";
    $vs_campo_nome = $_POST["representante_digital_campo_nome"] ?? "";
    $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);
    $_FILES = $files ?? $_FILES;

    $vb_salvo = $vo_objeto->salvar_representantes_digitais($vs_campo_nome, $_POST, $_FILES, true);


    if ($vb_salvo && isset($files))
    {
        apagar_arquivos_disco($files);
    }

    function apagar_arquivos_disco($files)
    {
        foreach ($files as $file)
        {
            if (file_exists($file['tmp_name']))
            {
                unlink($file['tmp_name']);
            }
        }
    }


    function get_list_files_ids_to_download($vs_selected_ids, $service, $create_agrupamentos, $max_folder_depth): string
    {
        $va_selected_ids = explode('|', $vs_selected_ids);
        $va_all_ids = [];

        foreach ($va_selected_ids as $id)
        {
            $file = $service->files->get($id, array('fields' => 'id, mimeType', 'supportsAllDrives' => true));

            if (is_google_drive_folder($file))
            {
                $va_all_ids = array_merge($va_all_ids, get_all_files_from_folder($service, $id, $create_agrupamentos, $max_folder_depth));
            }
            else
            {
                $va_all_ids[] = $file->id;
            }
        }

        $vs_all_ids = implode('|', $va_all_ids);
        echo $vs_all_ids;
        return $vs_all_ids;
    }

    function get_all_files_from_folder($service, $folderId, $create_agrupamentos, $max_folder_depth=2): array
    {
        return process_folder_children($service, $folderId, function ($child) {
            return $child->id;
        }, $create_agrupamentos, $max_folder_depth);
    }

    function get_files_names_from_ids($vs_selected_ids, $service)
    {
        $va_ids = explode('|', $vs_selected_ids);
        $va_all_names = [];

        foreach ($va_ids as $id)
        {
            $file = $service->files->get($id, array('fields' => 'id, name', 'supportsAllDrives' => true));
            $va_all_names[] = $file->name;
        }

        utils::log("get_files_names_from_ids", implode('|', $va_all_names));
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($va_all_names));
    }

    function download_drive_files_from_folder($service, $folderId, $client, $create_agrupamentos): array
    {
        return process_folder_children($service, $folderId, function ($child) use ($service, $client, $create_agrupamentos) {
            return download_drive_file($child, $service, $client, $create_agrupamentos);
        });
    }

    function download_drive_file($file, $service, $client, $create_agrupamentos): array
    {
        $id = $file->id;

        $va_mime_types = get_allowed_mime_types();

        $fileNameSanitized = utils::sanitize_file_name($file->name);
        $fileName = config::get(["pasta_media", "downloads"]) . $fileNameSanitized;
        $chunkSizeBytes = 5 * 1024 * 1024;

        $maxFileSize = min((int)ini_get('post_max_size'), (int)ini_get('upload_max_filesize')) * 1024 * 1024;


        if (!in_array($file->mimeType, $va_mime_types))
        {
            header('HTTP/1.0 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            $erro = 'Tipo de arquivo não permitido (' . $file->mimeType . ').';
            die(json_encode(array('file' => $file->name, 'error' => $erro)));
        }

        if ($file->size > $maxFileSize)
        {
           header('HTTP/1.0 400 Bad Request');
           header('Content-Type: application/json; charset=UTF-8');
           $erro = 'Tamanho do arquivo é maior que o permitido (' . $file->size  / 1024 / 1024 . 'MB).';
           die(json_encode(array('file' => $file->name, 'error' => $erro)));
        }

        if ($file->size < $chunkSizeBytes)
        {
            $response = $service->files->get($id, array('alt' => 'media', 'supportsAllDrives' => true));
            $content = $response->getBody()->getContents();
            file_put_contents($fileName, $content);
        }
        else
        {
            $http = $client->authorize();

            $fp = fopen($fileName, 'w+');
            $fileSize = intval($file->size);
            $chunkStart = 0;

            while ($chunkStart < $fileSize)
            {
                $chunkEnd = $chunkStart + $chunkSizeBytes;
                $response = $http->request(
                    'GET',
                    sprintf('/drive/v3/files/%s', $id),
                    [
                        'query' => ['alt' => 'media'],
                        'headers' => [
                            'Range' => sprintf('bytes=%s-%s', $chunkStart, $chunkEnd)
                        ]
                    ]
                );
                $chunkStart = $chunkEnd + 1;
                fwrite($fp, $response->getBody()->getContents());
            }
            fclose($fp);
        }

        chmod($fileName, 0600);

        if ($create_agrupamentos)
        {
            $parentFolderId = $file->parents[0];
            $parentFolderName = $service->files->get($parentFolderId, array('fields' => 'name', 'supportsAllDrives' => true))->name;
            $_POST["documento_agrupamento_codigo"] = get_agrupamento_codigo($parentFolderName);
        }

        return array(
            'name' => $file->name,
            'size' => $file->size,
            'type' => $file->mimeType,
            'error' => 0,
            'tmp_name' => $fileName,
        );
    }

    function process_folder_children($service, $folderId, $callback, $create_agrupamento=false, $max_folder_depth=2, $initial_folder_depth=1): array
    {
        $results = [];

        if ($create_agrupamento && $initial_folder_depth < $max_folder_depth)
        {
            $folder = $service->files->get($folderId, array('fields' => 'name', 'supportsAllDrives' => true));
            $vn_agrupamento_superior = get_agrupamento_codigo($folder->name);
        }

        $pageToken = null;
        do
        {
            $children = $service->files->listFiles(array(
                'q' => "'$folderId' in parents",
                'spaces' => 'drive',
                'pageToken' => $pageToken,
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true,
                'fields' => 'nextPageToken, files(id, name, mimeType, size, parents)',
            ));

            foreach ($children->files as $child)
            {
                if (is_google_drive_folder($child) && $initial_folder_depth < $max_folder_depth)
                {
                    if ($create_agrupamento)
                    {
                        $vn_agrupamento_inferior =  get_agrupamento_codigo($child->name);
                        set_agrupamento_superior($child->name, $vn_agrupamento_inferior, $vn_agrupamento_superior);
                    }

                    $results = array_merge($results, process_folder_children($service, $child->id, $callback, $create_agrupamento, $max_folder_depth, $initial_folder_depth + 1));
                }
                elseif (!is_google_drive_folder($child) && $initial_folder_depth <= $max_folder_depth)
                {

                    $va_mime_types = get_allowed_mime_types();

                    if (in_array($child->mimeType, $va_mime_types))
                    {
                        $results[] = call_user_func($callback, $child);
                    }
                }
            }

            $pageToken = $children->nextPageToken;
        } while ($pageToken != null);

        return $results;
    }

    function is_google_drive_folder($file): bool
    {
        return $file->mimeType == 'application/vnd.google-apps.folder';
    }

    function get_allowed_mime_types(): array
    {
        $vs_campo_representante_digital = $_POST["representante_digital_campo_nome"] ?? "";

        $va_extensoes_permitidas = config::get(["extensoes_permitidas"]);
        $va_mime_types = [];

        if ($vs_campo_representante_digital == "arquivo_download_codigo")
        {
            $va_mime_types = ["application/pdf"];
        }
        else
        {
            foreach ($va_extensoes_permitidas as $vs_extensao => $vs_mime_type)
            {
                $va_mime_types[] = $vs_mime_type;
            }
        }

        return $va_mime_types;
    }

    function get_agrupamento_codigo($ps_folder_name)
    {
        $vn_agrupamento = ler_agrupamento_codigo($ps_folder_name);

        return $vn_agrupamento != 0 ? $vn_agrupamento : criar_agrupamento($ps_folder_name);
    }

    function criar_agrupamento($ps_nome)
    {
        $vo_agrupamento = new agrupamento();
        return $vo_agrupamento->salvar([
                'usuario_logado_codigo' => $_SESSION["usuario_logado_codigo"],
                'agrupamento_dados_textuais_0_agrupamento_nome' => $ps_nome,
                'agrupamento_acervo_codigo' => $_POST["item_acervo_acervo_codigo"] ?? ""
        ]);
    }

    function ler_agrupamento_codigo($ps_agrupamento_nome)
    {
        $vo_agrupamento = new agrupamento();
        $va_agrupamento = $vo_agrupamento->ler_lista([
            'agrupamento_dados_textuais_0_agrupamento_nome' => $ps_agrupamento_nome,
        ]);

        if ($va_agrupamento > 0)
        {
            return $va_agrupamento[0]["agrupamento_codigo"] ?? 0;
        }

        return 0;
    }

    function set_agrupamento_superior($ps_nome, $vn_agrupamento_codigo, $vn_agrupamento_superior_codigo)
    {
        $vo_agrupamento = new agrupamento();
        $vo_agrupamento->atualizar([
            'agrupamento_codigo' => $vn_agrupamento_codigo,
            'agrupamento_dados_textuais_0_agrupamento_nome' => $ps_nome,
            'agrupamento_agrupamento_superior_codigo' => $vn_agrupamento_superior_codigo,
        ]);

    }

?>