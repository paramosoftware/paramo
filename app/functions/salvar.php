<?php
    require_once dirname(__FILE__) . "/../components/entry_point.php";

    if (!isset($_POST["escopo"]) || ($_POST["escopo"] != "_senha"))
    {
        if (!$vb_pode_editar && !$vb_pode_inserir)
        {
            utils::log(
                "Tentativa de alteração sem permissão: ",
                __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
            );
            session::redirect();
        }
    }
    elseif (isset($_POST["escopo"]) || ($_POST["escopo"] == "_senha"))
    {
        if (isset($_POST["usuario_codigo"]) && ($_POST["usuario_codigo"] != $vn_usuario_logado_codigo))
        {
            unset($_POST["usuario_senha"]);
            unset($_POST["repetir_senha"]);
            utils::log(
                "Tentativa de alteração de senha de outro usuário: ",
                __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
            );
            session::redirect();
        }

        foreach(array_keys($_POST) as $vs_variavel_postada)
        {
            if (!in_array($vs_variavel_postada, ["usuario_codigo", "usuario_senha", "obj", "modo", "escopo", "usuario_alterar_senha"]))
                unset($_POST[$vs_variavel_postada]);
        }
    }

    $_POST["usuario_logado_codigo"] = $vn_usuario_logado_codigo;
    $_POST["usuario_logado_codigo_grupo_usuario_codigo"] = $vn_usuario_logado_grupo_usuario_codigo;

    $vs_id_objeto_tela = $_POST["obj"];

    $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);
    
    $vs_chave_primaria_objeto = $vo_objeto->get_chave_primaria()[0];

    // Se for uma edição em lote (_modo_ == lote)
    ////////////////////////////////////////////////

    if (isset($_POST["modo"]) && ($_POST["modo"] == "lote"))
    {

        $vo_objeto->iniciar_transacao();

        $va_objetos_codigos = explode("|", $_POST[$vs_chave_primaria_objeto]);
        
        $vo_objeto_temp = $vo_objeto;
        
        while ($vo_objeto_temp->get_objeto_pai())
        {
            $vs_id_objeto_pai = $vo_objeto_temp->get_objeto_pai();

            $vo_objeto_pai = new $vs_id_objeto_pai('');
            $vs_chave_primaria_objeto_pai = $vo_objeto_pai->get_chave_primaria()[0];

            $va_objetos_pais_codigos[$vs_chave_primaria_objeto_pai] = explode("|", $_POST[$vs_chave_primaria_objeto_pai]);

            $vo_objeto_temp = $vo_objeto_pai;
        }

        $vn_indice_objetos = 0;
        foreach($va_objetos_codigos as $vn_objeto_codigo)
        {
            if (!$vb_usuario_administrador || !$vb_usuario_logado_instituicao_admin)
            {
                $vb_acesso_registro = $vo_objeto->validar_acesso_registro($vn_objeto_codigo, $va_parametros_controle_acesso);

                if ($vb_acesso_registro)
                {
                    $vb_edicao_registro = $vo_objeto->validar_edicao_registro($_POST, $va_parametros_controle_acesso);
                }

                if (!$vb_acesso_registro || !$vb_edicao_registro)
                {
                    utils::log(
                        "Tentativa de alteração sem permissão: ",
                        __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
                    );
                    session::redirect();
                }
            }

            $_POST[$vs_chave_primaria_objeto] = $vn_objeto_codigo;
            
            foreach($va_objetos_pais_codigos as $vs_chave_primaria_objeto_pai => $va_objeto_pai_codigos)
            {
                $_POST[$vs_chave_primaria_objeto_pai] = $va_objetos_pais_codigos[$vs_chave_primaria_objeto_pai][$vn_indice_objetos];
            }

            $vo_objeto->salvar($_POST, true, $vn_idioma_catalogacao_codigo);

            $vn_indice_objetos++;
        }

        $vo_objeto->finalizar_transacao();

        $vs_url_retorno = "listar.php?obj=". $vs_id_objeto_tela . "&back=1";
        session::redirect($vs_url_retorno);
    }
    else
    {
        if (empty($_POST[$vs_chave_primaria_objeto]) && !$vb_pode_inserir)
        {
            utils::log(
                "Tentativa de inserção sem permissão: ",
                __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
            );
            exit();
        }

        if (!$vb_usuario_administrador || !$vb_usuario_logado_instituicao_admin)
        {
            $vb_acesso_registro = true;

            if (!empty($_POST[$vs_chave_primaria_objeto]))
            {
                $vb_acesso_registro = $vo_objeto->validar_acesso_registro($_POST[$vs_chave_primaria_objeto], $va_parametros_controle_acesso);
            }

            if ($vb_acesso_registro)
            {
                $vb_edicao_registro = $vo_objeto->validar_edicao_registro($_POST, $va_parametros_controle_acesso);
            }

            if (!$vb_acesso_registro || !$vb_edicao_registro)
            {
                utils::log(
                    "Tentativa de alteração sem permissão: ",
                    __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
                );
                exit();
            }
        }

        $vn_codigo = $vo_objeto->salvar($_POST, true, $vn_idioma_catalogacao_codigo);
        
        $_POST[$vs_id_objeto_tela . "_codigo"] = $vn_codigo;

        if (!isset($_POST["escopo"]))
        {
            $vo_objeto->salvar_representantes_digitais("representante_digital_codigo", $_POST, $_FILES, true);
            $vo_objeto->salvar_representantes_digitais("arquivo_download_codigo", $_POST, $_FILES, true);
        }
    }

    if (isset($_POST["escopo"]))
    {
        if ($_POST["escopo"] == "_in")
        {
            $vs_nome_campo = $_POST["campo"];
            $vs_campo_salvar = $_POST["campo_salvar"];
            
            $va_valores_form = array();
            $va_valores_form = [$vo_objeto->get_chave_primaria()[0] => $vn_codigo];

            $va_parametros_campo = [
                "html_combo_input", 
                "nome" => $vs_nome_campo, 
                "label" => "Selecionar",
                "objeto" => $vs_id_objeto_tela,
                "atributos" => [$vo_objeto->get_chave_primaria()[0], $vs_campo_salvar],
                "dependencia" => 
                    ["campo" => $vo_objeto->get_chave_primaria()[0], "atributo" => $vo_objeto->get_chave_primaria()[0]]
            ];

            $vo_html_selection_list_input = new html_combo_input(null, $vs_nome_campo, "autocomplete");
            $vo_html_selection_list_input->build($va_valores_form, $va_parametros_campo);
        }

        if ($_POST["escopo"] == "_senha")
        {
            session::redirect();
        }
    }
    else
    {
        $vs_url_retorno = "editar.php?obj=". $vs_id_objeto_tela . "&idioma=" . $vn_idioma_catalogacao_codigo;

        if (isset($vn_codigo))
        {
            $vs_url_retorno .= "&cod=" . $vn_codigo;
        }

        if (isset($_POST["modo"]) && ($_POST["modo"] == "dup"))
            $vs_url_retorno .= "&dup=1&save_dup=1";
        else
            $vs_url_retorno .= "&save=1";
	    
        if (isset($_POST["texto_bibliografia_codigo"]))
            $vs_url_retorno .= "&bibliografia=" . $_POST["texto_bibliografia_codigo"];


        session::redirect($vs_url_retorno);
    }
?>