<?php
    if (!isset($vb_autenticar_usuario))
        $vb_autenticar_usuario = true;

    if (!isset($vb_usuario_externo))
        $vb_usuario_externo = false;

    // Antes de chamar configurar_campos_tela.php, tenho que definir o modo "listagem"
    //////////////////////////////////////////////////////////////////////////////////

    $vs_modo = $vs_modo ?? "listagem";
    require_once dirname(__FILE__) . "/configurar_campos_tela.php";

    // Vamos inicializar e ler aqui os parâmetros vindos dos filtros
    //////////////////////////////////////////////////

    $va_parametros_filtros_form = array();
    $va_parametros_filtros_consulta = $va_parametros_filtros_consulta ?? [];
    $vb_tem_filtros_consulta = false;

    if (!isset($va_log_info))
        $va_log_info = array(); 

    $va_parametros_submit = array();

    if ( (isset($_GET["back"]) && isset($_SESSION[$vs_id_objeto_tela])) || ($vb_usar_parametros_sessao ?? false))
    {
        // Se a requisição vem de uma página de edição ou ficha (back=1)
        // carrega as variáveis de sessão
        ////////////////////////////////////////////////////////////////

        $va_parametros_submit = $_SESSION[$vs_id_objeto_tela];
        
        if (isset($va_parametros_submit['campo_paginacao']))
        {
            $vs_campo_paginacao = $va_parametros_submit['campo_paginacao'];

            if (isset($va_parametros_submit[$vs_campo_paginacao]))
                $vn_pagina_atual = $va_parametros_submit[$vs_campo_paginacao];
        }
    }
    elseif (count($_GET))
        $va_parametros_submit = $_GET;
    elseif (count($_POST))
        $va_parametros_submit = $_POST;

    if (isset($va_campos))
    {
        $vb_aplicar_controle_acesso = true;
        $vb_busca_combinada = false;
        $va_controles_acesso_aplicados = array();
        $vb_buscar_niveis_inferiores = false;

        foreach ($va_parametros_submit as $vs_key_filtro => $vs_valor_filtro)
        {
            // Se for um filtro de busca que pode aparecer mais de uma vez na tela
            //////////////////////////////////////////////////////////////////////

            $vs_campo = $vs_key_filtro;

            if ( preg_match('/\w+(_F_\d+)$/', $vs_key_filtro) || preg_match('/\w+(_F_\d+)_com_valor$/', $vs_key_filtro) || preg_match('/\w+(_F_\d+)_sem_valor$/', $vs_key_filtro))
            {
                $vs_campo = substr($vs_key_filtro, 0, strpos($vs_key_filtro, "_F_"));
                $vb_busca_combinada = true;
            }
            elseif ((strpos($vs_key_filtro, "_com_valor") != FALSE) || (strpos($vs_key_filtro, "_sem_valor") != FALSE))
            {
                $vs_campo = str_replace("_com_valor", "", $vs_campo);
                $vs_campo = str_replace("_sem_valor", "", $vs_campo);
            }

            if (isset($va_campos[$vs_campo]))
            {
                $va_campo_filtro = $va_campos[$vs_campo];
                $vs_campo = $vs_key_filtro;

                // Vamos tratar as datas
                ////////////////////////

                if ($va_parametros_submit[$vs_campo] == "_data_")
                {
                    $va_parametros_filtros_consulta[$vs_campo] = $va_parametros_filtros_form[$vs_campo] = "_data_";

                    $vb_tem_data_preenchida = false;
                    
                    if (isset($va_parametros_submit[$vs_campo . "_dia_inicial"]) && $va_parametros_submit[$vs_campo . "_dia_inicial"])
                    {
                        $vb_tem_data_preenchida = true;
                        $va_parametros_filtros_form[$vs_campo . "_dia_inicial"] = $va_parametros_submit[$vs_campo . "_dia_inicial"];
                    }
                        
                    if (isset($va_parametros_submit[$vs_campo . "_mes_inicial"]) && $va_parametros_submit[$vs_campo . "_mes_inicial"])
                    {
                        $vb_tem_data_preenchida = true;
                        $va_parametros_filtros_form[$vs_campo . "_mes_inicial"] = $va_parametros_submit[$vs_campo . "_mes_inicial"];
                    }                        

                    if (isset($va_parametros_submit[$vs_campo . "_ano_inicial"]) && $va_parametros_submit[$vs_campo . "_ano_inicial"])
                    {
                        $vb_tem_data_preenchida = true;
                        $va_parametros_filtros_form[$vs_campo . "_ano_inicial"] = $va_parametros_submit[$vs_campo . "_ano_inicial"];
                    }
                        
                    if (isset($va_parametros_submit[$vs_campo . "_dia_final"]) && $va_parametros_submit[$vs_campo . "_dia_final"])
                    {
                        $vb_tem_data_preenchida = true;
                        $va_parametros_filtros_form[$vs_campo . "_dia_final"] = $va_parametros_submit[$vs_campo . "_dia_final"];
                    }

                    if (isset($va_parametros_submit[$vs_campo . "_mes_final"]) && $va_parametros_submit[$vs_campo . "_mes_final"])
                    {
                        $vb_tem_data_preenchida = true;
                        $va_parametros_filtros_form[$vs_campo . "_mes_final"] = $va_parametros_submit[$vs_campo . "_mes_final"];
                    }
                        
                    if (isset($va_parametros_submit[$vs_campo . "_ano_final"]) && $va_parametros_submit[$vs_campo . "_ano_final"])
                    {
                        $vb_tem_data_preenchida = true;
                        $va_parametros_filtros_form[$vs_campo . "_ano_final"] = $va_parametros_submit[$vs_campo . "_ano_final"];
                    }

                    if (!$vb_tem_data_preenchida)
                        unset($va_parametros_filtros_form[$vs_campo]);
                        
                    if (isset($va_parametros_submit[$vs_campo . "_sem_data"]))
                    {
                        $va_parametros_filtros_form[$vs_campo . "_sem_data"] = $va_parametros_submit[$vs_campo . "_sem_data"];
                        $va_parametros_filtros_consulta[$vs_campo . "_sem_data"] = $va_parametros_submit[$vs_campo . "_sem_data"];
                    }

                    if (isset($va_parametros_submit[$vs_campo . "_dia_inicial"]) && $va_parametros_submit[$vs_campo . "_dia_inicial"])
                        $va_parametros_filtros_consulta[$vs_campo . "_dia_inicial"] = $va_parametros_submit[$vs_campo . "_dia_inicial"];

                    if (isset($va_parametros_submit[$vs_campo . "_mes_inicial"]) && $va_parametros_submit[$vs_campo . "_mes_inicial"])
                        $va_parametros_filtros_consulta[$vs_campo . "_mes_inicial"] = $va_parametros_submit[$vs_campo . "_mes_inicial"];

                    if (isset($va_parametros_submit[$vs_campo . "_ano_inicial"]) && $va_parametros_submit[$vs_campo . "_ano_inicial"])
                        $va_parametros_filtros_consulta[$vs_campo . "_ano_inicial"] = $va_parametros_submit[$vs_campo . "_ano_inicial"];

                    if (isset($va_parametros_submit[$vs_campo . "_dia_final"]) && $va_parametros_submit[$vs_campo . "_dia_final"])
                        $va_parametros_filtros_consulta[$vs_campo . "_dia_final"] = $va_parametros_submit[$vs_campo . "_dia_final"];

                    if (isset($va_parametros_submit[$vs_campo . "_mes_final"]) && $va_parametros_submit[$vs_campo . "_mes_final"])
                        $va_parametros_filtros_consulta[$vs_campo . "_mes_final"] = $va_parametros_submit[$vs_campo . "_mes_final"];

                    if (isset($va_parametros_submit[$vs_campo . "_ano_final"]) && $va_parametros_submit[$vs_campo . "_ano_final"])
                        $va_parametros_filtros_consulta[$vs_campo . "_ano_final"] = $va_parametros_submit[$vs_campo . "_ano_final"];
                }
                elseif (strpos($vs_key_filtro, "_sem_valor") !== FALSE)
                {
                    $va_parametros_filtros_form[$vs_key_filtro] = 1;
                    if (isset($va_campo_filtro["exists_busca"]))
                    {
                        $vs_key_filtro = $va_campo_filtro["exists_busca"];
                    }

                    $va_parametros_filtros_consulta[str_replace("_sem_valor", "", $vs_key_filtro)] = ["0", "_EXISTS_"];
                }
                elseif (strpos($vs_key_filtro, "_com_valor") !== FALSE)
                {

                    $va_parametros_filtros_form[$vs_key_filtro] = 1;

                    if (isset($va_campo_filtro["exists_busca"]))
                    {
                        $vs_key_filtro = $va_campo_filtro["exists_busca"];
                    }

                    $va_parametros_filtros_consulta[str_replace("_com_valor", "", $vs_key_filtro)] = ["1", "_EXISTS_"];
                }
                else
                {
                    $vs_valor = $va_parametros_submit[$vs_campo];

                    $vb_tem_valor_busca = true;

                    if (is_array($vs_valor))
                    {
                        foreach ($vs_valor as $vs_valor_individual)
                        {
                            if (trim($vs_valor_individual) == "")
                                $vb_tem_valor_busca = false;
                        }
                    }
                    elseif (trim($vs_valor) == "")
                        $vb_tem_valor_busca = false;
                    
                    // Pensar: só adiciono o filtro se vier algum valor escolhido
                    // Como fazer quando o usuário realmente quiser que o valor seja vazio?
                    ///////////////////////////////////////////////////////////////////////

                    if ($vb_tem_valor_busca)
                    {
                        $va_parametros_filtros_form[$vs_campo] = $vs_valor;
                        
                        $vs_operador_filtro = "=";
                        if (isset($va_campo_filtro["operador_filtro"]))
                            $vs_operador_filtro = $va_campo_filtro["operador_filtro"];
                        
                        $vb_busca_hierarquica = false;
                        if (isset($va_campo_filtro["hierarquia"]))
                            $vb_busca_hierarquica = $va_campo_filtro["hierarquia"];

                        $va_parametros_filtros_consulta[$vs_campo] = [$vs_valor, $vs_operador_filtro, $vb_busca_hierarquica];

                        if (isset($va_campo_filtro["filtro"]))
                        {

                            if (!is_array($va_campo_filtro["filtro"]))
                            {
                                $va_campo_filtro["filtro"] = [$va_campo_filtro["filtro"]];
                            }

                            foreach ($va_campo_filtro["filtro"] as $va_filtro)
                            {

                                if (!isset($va_filtro["atributo"]))
                                {
                                    continue;
                                }

                                $vs_atributo = $va_filtro["atributo"];

                                if (isset($va_parametros_filtros_consulta[$vs_atributo]))
                                {
                                    continue;
                                }

                                if (isset($va_filtro["valor"]))
                                {
                                    $va_parametros_filtros_consulta[$vs_atributo] = [$va_filtro["valor"], $va_filtro["operador_filtro"] ?? "="];
                                }
                            }
                        }

                        if ($va_campo_filtro["buscar_niveis_inferiores"] ?? false)
                            $vb_buscar_niveis_inferiores = true;
                    }
                }
            }

            if (count($va_parametros_filtros_form))
                $vb_tem_filtros_consulta = true;
        }

        foreach ($va_campos as $vs_campo => $va_campo_filtro)
        {
            if (!isset($va_parametros_filtros_consulta[$vs_campo]) && isset($va_campo_filtro["valor_padrao"]))
            {
                $vs_operador_filtro = "=";
                if (isset($va_campo_filtro["operador_filtro"]))
                    $vs_operador_filtro = $va_campo_filtro["operador_filtro"];

                $va_parametros_filtros_consulta[$vs_campo] = [$va_campo_filtro["valor_padrao"], $vs_operador_filtro];
            }
        }

        if (isset($va_parametros_submit["concatenadores"]))
            $va_parametros_filtros_consulta["concatenadores"] = $va_parametros_submit["concatenadores"];


        if (!$vb_usuario_externo && isset($vo_objeto->controlador_acesso) && count($vo_objeto->controlador_acesso))
        {
            // Verificação preliminar da existência de permissões de acesso
            ///////////////////////////////////////////////////////////////

            $va_acessos_por_controlador = array();
    
            foreach ($vo_objeto->controlador_acesso as $vs_key_controlador => $vs_atributo_controlador)
            {
                if (trim($va_parametros_controle_acesso[$vs_key_controlador]) == "")
                    $va_acessos_por_controlador[$vs_key_controlador] = false;
                else
                    $va_acessos_por_controlador[$vs_key_controlador] = true;
            }

            if (isset($va_parametros_controle_acesso["_combinacao_"]) && $va_parametros_controle_acesso["_combinacao_"] == "OR")
                $vb_acesso_invalido_registro = !in_array(true, $va_acessos_por_controlador);
            else
                $vb_acesso_invalido_registro = in_array(false, $va_acessos_por_controlador);


            if ($vb_acesso_invalido_registro)
            {
                $vb_fazer_busca = false;
                //$vb_pode_inserir = false;
                $vb_pode_editar = false;
            }

            // Verificação das permissões de acesso efetivamente atribuídas
            ///////////////////////////////////////////////////////////////

            foreach ($vo_objeto->controlador_acesso as $vs_key_controlador => $vs_atributo_controlador)
            {
                if ( (in_array($vs_key_controlador, array_keys($va_parametros_controle_acesso))) )
                {
                    if ($va_parametros_controle_acesso[$vs_key_controlador] == "_ALL_") continue;

                    if ($va_parametros_controle_acesso[$vs_key_controlador] != "")
                    {
                        if (count(explode("|", $va_parametros_controle_acesso[$vs_key_controlador])) == 1 && isset($va_campos[$vs_atributo_controlador]))
                        {
                            $va_campos[$vs_atributo_controlador]["sem_valor"] = false;
                            unset($va_parametros_filtros_form[$vs_atributo_controlador]);
                        }

                        if (
                            !isset($va_parametros_filtros_consulta[$vs_atributo_controlador])
                            ||
                            ((isset($va_parametros_filtros_consulta[$vs_atributo_controlador][1]) && ($va_parametros_filtros_consulta[$vs_atributo_controlador][1] == "_EXISTS_")) && $va_parametros_filtros_consulta[$vs_atributo_controlador][0] == "1")
                        )
                        {
                            $va_parametros_filtros_consulta[$vs_atributo_controlador] = [$va_parametros_controle_acesso[$vs_key_controlador], "="];

                            $va_controles_acesso_aplicados[] = $vs_atributo_controlador;
                        }
                        elseif ( 
                            isset($va_parametros_filtros_consulta[$vs_atributo_controlador][0])
                            &&
                            !in_array($va_parametros_filtros_consulta[$vs_atributo_controlador][0], explode("|", $va_parametros_controle_acesso[$vs_key_controlador]))
                            &&
                            (!isset($va_parametros_filtros_consulta[$vs_atributo_controlador][1]) || (isset($va_parametros_filtros_consulta[$vs_atributo_controlador][1]) && ($va_parametros_filtros_consulta[$vs_atributo_controlador][1] != "_EXISTS_")))   
                        )
                        {
                            // Se foi postado um valor de filtro, mas ele não está na lista de permissões de acesso
                            ///////////////////////////////////////////////////////////////////////////////////////
    
                            $vb_pode_editar = false;
                            $vb_aplicar_controle_acesso = false;
    
                            if ($vs_key_controlador == "instituicao_codigo")
                            {
                                $vo_instituicao = new instituicao;
                                $va_instituicoes[] = $vo_instituicao->ler($va_parametros_filtros_consulta[$vs_atributo_controlador][0]);
    
                                $_SESSION["instituicao_visualizar_como"] = $va_instituicoes[0]["instituicao_codigo"];
                                $vb_fazer_busca = true;

                                $va_instituicao_visualizar_como_parametros = [$vs_atributo_controlador => $va_parametros_filtros_consulta[$vs_atributo_controlador][0]];
                            }
                        }
                    }
                } 
            }
        }
        elseif ($vb_usuario_externo)
        {
            // Se o usuário é externo, os registros a que ele têm acesso são definidos em seleções compartilhadas com ele
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if (count($va_selecoes_compartilhadas_codigos))
                $va_parametros_filtros_consulta["item_selecao_codigo"] = implode("|", $va_selecoes_compartilhadas_codigos);
        }

        if (!$vb_aplicar_controle_acesso)
        {
            foreach ($va_controles_acesso_aplicados as $vs_controle_acesso_aplicado)
            {
                unset($va_parametros_filtros_consulta[$vs_controle_acesso_aplicado]);
                unset($va_parametros_filtros_form[$vs_controle_acesso_aplicado]);
            }
        }
    }

    $vb_existe_filtro_log = false;

    if (isset($va_parametros_submit["log_data_inicial"]) && ($va_parametros_submit["log_data_inicial"] != ""))
    {
        $va_log_info["log_data_inicial"] = $va_parametros_submit["log_data_inicial"];
        $va_parametros_filtros_form["log_data_inicial"] = $va_parametros_submit["log_data_inicial"];

        $vb_existe_filtro_log = true;
    }

    if (isset($va_parametros_submit["log_data_final"]) && ($va_parametros_submit["log_data_final"] != ""))
    {
        $va_log_info["log_data_final"] = $va_parametros_submit["log_data_final"];
        $va_parametros_filtros_form["log_data_final"] = $va_parametros_submit["log_data_final"];

        $vb_existe_filtro_log = true;
    }

    if (isset($va_parametros_submit["tipo_operacao_log_codigo"]) && ($va_parametros_submit["tipo_operacao_log_codigo"] != ""))
    {
        $va_log_info["log_tipo_operacao_codigo"] = $va_parametros_submit["tipo_operacao_log_codigo"];
        $va_parametros_filtros_form["tipo_operacao_log_codigo"] = $va_parametros_submit["tipo_operacao_log_codigo"];

        $vb_existe_filtro_log = true;
    }

    if (isset($va_parametros_submit["log_usuario_codigo"]) && ($va_parametros_submit["log_usuario_codigo"] != ""))
    {
        $va_log_info["log_usuario_codigo"] = $va_parametros_submit["log_usuario_codigo"];
        $va_parametros_filtros_form["log_usuario_codigo"] = $va_parametros_submit["log_usuario_codigo"];

        $vb_existe_filtro_log = true;
    }

    if (isset($_SESSION))
    {
        unset($_SESSION[$vs_id_objeto_tela]);
    }

    $_SESSION[$vs_id_objeto_tela] = $va_parametros_submit;

    if (isset($vn_ordenacao))
        $_SESSION[$vs_id_objeto_tela]["ordenacao"] = $vn_ordenacao;
?>