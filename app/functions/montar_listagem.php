<?php

    if (!isset($vb_autenticar_usuario))
        $vb_autenticar_usuario = true;

    if (!isset($vb_usuario_externo))
        $vb_usuario_externo = false;

    if ($vb_autenticar_usuario)
        require_once dirname(__FILE__) . "/autenticar_usuario.php";

    elseif (!isset($vs_id_objeto_tela))
    {

        if (!defined("AUTOLOAD"))
        {
            require_once dirname(__FILE__)."/../../autoload.php";
        }


        $vb_ler_campos_banco = false;
        $vn_bibliografia_codigo = "";
        $vn_recurso_sistema_codigo = "";
        $vn_usuario_logado_instituicao_codigo = "";
        $vn_usuario_logado_acervo_codigo = "";

        if (isset($_POST['obj']))
            $vs_id_objeto_tela = $_POST['obj'];
        elseif (isset($_GET['obj']))
            $vs_id_objeto_tela = $_GET['obj'];
        else
            exit();
    }

    require_once dirname(__FILE__) . "/../components/ler_valor.php";

    if (!isset($vb_fazer_busca))
        $vb_fazer_busca = true;

    $vn_numero_registros = 0;
    $vn_numero_registros_filhos = 0;
    $va_itens_listagem = array();
    $va_itens_listagem_codigos = array();
    $vn_primeiro_registro = 1;
    $vn_numero_registros_lista = 20;

    if (!isset($vs_modo))
        $vs_modo = "listagem";

    if (!isset($vs_output))
        $vs_output = "in";

    if (!isset($vs_formato_listagem))
        $vs_formato_listagem = "card";

    if (!isset($vb_tem_filtros_consulta))
        $vb_tem_filtros_consulta = false;

    if (!isset($va_log_info))
        $va_log_info = array();

    if (!isset($vn_ordenacao))
        $vn_ordenacao = "";

    if (!isset($vs_ordem))
        $vs_ordem = "";

    if (!isset($vb_retornar_valores_vazios))
        $vb_retornar_valores_vazios = false;

    // Eis aqui: onde o objeto_base vai montar automaticamente os atributos, campos e visualizações do
    // objeto passado por parâmetro no construtor
    //////////////////////////////////////////////////////////////////////////////////////////////////

    $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);

    //////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////

    if ( ($vs_modo != "ficha") ) 
    {
        if (!isset($vs_target_ui))
            $vs_target_ui = "";

        if (!isset($va_parametros_filtros_consulta) || isset($vb_montar_filtros_busca))
            require_once dirname(__FILE__) . "/montar_filtros_busca.php";

        if ($vb_fazer_busca)
        {
            if (!isset($va_parametros_filtros_consulta))
                $va_parametros_filtros_consulta = array();

            $va_parametros_filtros_consulta = array_merge($va_parametros_filtros_consulta, $vo_objeto->get_filtros_selecao());

            if (isset($_GET['ordenacao']))
                $vn_ordenacao = $_GET['ordenacao'];
            elseif (isset($_POST['ordenacao']))
                $vn_ordenacao = $_POST['ordenacao'];
            elseif (!isset($vn_ordenacao))
                $vn_ordenacao = null;

            if (isset($_GET['ordem']))
                $vs_ordem = $_GET['ordem'];
            elseif (isset($_POST['ordem']))
                $vs_ordem = $_POST['ordem'];
            elseif (!isset($vs_ordem))
                $vs_ordem = "ASC";

            if (!isset($vs_visualizacao))
            {
                $vs_visualizacao = "navegacao";
                if (isset($_GET['visualizacao_codigo']))
                    $vs_visualizacao = $_GET['visualizacao_codigo'];
                elseif (isset($_POST['visualizacao_codigo']))
                    $vs_visualizacao = $_POST['visualizacao_codigo'];
            }

            if (!$vs_visualizacao)
                $vs_visualizacao = "navegacao";

            // Se tem um campo hierárquico e ele não vem no filtro,
            // então queremos o primeiro nível
            ///////////////////////////////////////////////////////

            if ($vo_objeto->get_campo_hierarquico() && $vo_objeto->exibir_lista_hierarquica)
            {
                if (!isset($va_parametros_filtros_consulta[$vo_objeto->get_campo_hierarquico()]) && ( ((!isset($vb_buscar_niveis_inferiores) || !$vb_buscar_niveis_inferiores) && ($vo_objeto->tipo_hierarquia == "default")) || $vo_objeto->tipo_hierarquia == "subordinada"))
                    $va_parametros_filtros_consulta[$vo_objeto->get_campo_hierarquico()] = [null, "<=>"];
            }

            unset($va_visualizacao_lista);
            $va_visualizacao_lista = $vo_objeto->get_visualizacao($vs_visualizacao);

            if (!isset($vn_ordenacao) || (isset($vn_ordenacao) && !$vn_ordenacao))
            {
                if (isset($va_visualizacao_lista["order_by"]) && count($va_visualizacao_lista["order_by"]))                
                    $vn_ordenacao = array_keys($va_visualizacao_lista["order_by"])[0];
            }

            if (empty($vs_ordem))
            {
               $vs_ordem = $va_visualizacao_lista["sort"] ?? "ASC";
            }

            // Se é uma ordenação por utilização, tem que acrescentar o campo quantidade na mão
            /////////////////////////////////////

            $va_visualizacao_lista["campos"]["Q"] = ["nome" => "Q"];

            $vn_numero_registros = $vo_objeto->ler_numero_registros($va_parametros_filtros_consulta, $va_log_info);

            $va_numero_registros_por_objeto = $vo_objeto->get_numero_registros_por_objeto();
        }
    }
    elseif ($vs_modo == "ficha")
    {
        if (!$vb_usuario_externo && $vb_autenticar_usuario && !$vo_objeto->validar_acesso_registro($vn_objeto_codigo, $va_parametros_controle_acesso))
        {
            $vb_pode_editar = false;
            $vb_aplicar_controle_acesso = false;

            if (isset($_SESSION["instituicao_visualizar_como"]))
            {
                $vo_instituicao = new instituicao;
                $va_instituicoes[] = $vo_instituicao->ler($_SESSION["instituicao_visualizar_como"]);
            }
        }
        elseif ($vb_usuario_externo)
        {
            // Se o usuário é externo, os registros a que ele têm acesso são definidos em seleções compartilhadas com ele
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if (count($va_selecoes_compartilhadas_codigos))
                $va_parametros_filtros_consulta["item_selecao_codigo"] = implode("|", $va_selecoes_compartilhadas_codigos);
        }

        $va_parametros_filtros_consulta[$vo_objeto->get_chave_primaria()[0]] = $vn_objeto_codigo;

        if (!isset($vs_visualizacao))
        {
            if (isset($_POST['visualizacao_codigo']) && ($_POST['visualizacao_codigo']) )
                $vs_visualizacao = $_POST['visualizacao_codigo'];
            else
                $vs_visualizacao = "ficha";
        }            

        $vn_numero_registros = 1;
    }

    if ($vn_numero_registros)
    {
        $_SESSION[$vs_id_objeto_tela]["numero_registros"] = $vn_numero_registros;

        if (!defined("NUMERO_ITENS_PAGINA_LISTAGEM"))
        {
            define("NUMERO_ITENS_PAGINA_LISTAGEM", 20);
        }

        $vn_numero_maximo_paginas = ceil($vn_numero_registros/NUMERO_ITENS_PAGINA_LISTAGEM);

        if (isset($vn_pagina_atual))
        {
            $vn_primeiro_registro = ($vn_pagina_atual -1)*NUMERO_ITENS_PAGINA_LISTAGEM + 1;
            $vn_numero_registros_lista = NUMERO_ITENS_PAGINA_LISTAGEM;
        }
        else
            $vn_numero_registros_lista = $vn_numero_registros;

        $va_objetos_primeiro_lista = $vo_objeto->ler_lista($va_parametros_filtros_consulta, $vs_visualizacao, 1, 1, $vn_ordenacao, $vs_ordem, $va_log_info, 1, $vb_retornar_ramos_inferiores ?? true);
        $va_objetos_ultimo_lista = $vo_objeto->ler_lista($va_parametros_filtros_consulta, $vs_visualizacao, $vn_numero_registros, 1, $vn_ordenacao, $vs_ordem, $va_log_info, 1, $vb_retornar_ramos_inferiores ?? true);
        
        $va_objetos_lista = $vo_objeto->ler_lista($va_parametros_filtros_consulta, $vs_visualizacao, $vn_primeiro_registro, $vn_numero_registros_lista, $vn_ordenacao, $vs_ordem, $va_log_info, 1, $vb_retornar_ramos_inferiores ?? true);

        if ($vs_formato_listagem == "default")
        {
            $va_itens_listagem = $va_objetos_lista;
        }
        else
        {
            $va_visualizacao_lista = $vo_objeto->get_visualizacao($vs_visualizacao);

            if (isset($va_visualizacao_lista["ordem_campos"]))
                $va_campos_visualizacao = $va_visualizacao_lista["ordem_campos"];
            else
                $va_campos_visualizacao = array_keys($va_visualizacao_lista["campos"]);

            foreach ($va_objetos_lista as $va_item)
            {
                $vb_mostrar_registros_filhos = true;

                $vb_contador_nivel = 0;
                $va_items_nivel = array();

                while($vb_mostrar_registros_filhos)
                {
                    $va_item_listagem = array();

                    $va_item_listagem["_objeto"] = $va_item["_objeto"];
                    $va_item_listagem[$vo_objeto->get_chave_primaria()[0]] = $va_item[$vo_objeto->get_chave_primaria()[0]];

                    if (isset($va_item["_number_of_children"]) && $va_item["_number_of_children"] > 0)
                        $va_item_listagem["_number_of_children"] = $va_item["_number_of_children"];
                    else
                        $vb_mostrar_registros_filhos = false;

                    $vs_path = "";
                    if ( isset($va_item["representante_digital_codigo"]) && isset($va_visualizacao_lista["campos"]["representante_digital_codigo"]) )
                        $vs_path = ler_valor1("representante_digital_codigo", $va_item, $va_visualizacao_lista["campos"]["representante_digital_codigo"], 1);

                    $va_item_listagem["representante_digital"] = $vs_path;

                    $va_atributos_item_listagem = array();

                    foreach($va_campos_visualizacao as $vs_key_campo_visualizacao => $vs_label_campo_visualizacao)
                    {
                        $va_atributo_item_listagem = array();

                        $vb_id_field = false;
                        $vb_main_field = false;
                        $vb_descriptive_field = false;

                        if (is_array($vs_label_campo_visualizacao))
                        {
                            if (isset($vs_label_campo_visualizacao["controlado_por"]))
                            {
                                $vs_controlado_por = $vs_label_campo_visualizacao["controlado_por"];

                                if (isset($va_item[$vs_controlado_por]))
                                {
                                    if ($va_item[$vs_controlado_por] == 0)
                                    {
                                        continue;
                                    }
                                }
                            }


                            if (isset($vs_label_campo_visualizacao["id_field"]))
                                $vb_id_field = true;

                            if (isset($vs_label_campo_visualizacao["main_field"]))
                                $vb_main_field = true;

                            if (isset($vs_label_campo_visualizacao["descriptive_field"]))
                                $vb_descriptive_field = true;

                            if (isset($vs_label_campo_visualizacao["label"]))
                                $vs_label_campo_visualizacao = $vs_label_campo_visualizacao["label"];
                        }

                        if (intval($vs_key_campo_visualizacao))
                            $vs_key_campo_visualizacao = $vs_label_campo_visualizacao;

                        $va_campo_visualizacao = null;
                        if (isset($va_visualizacao_lista["campos"][$vs_key_campo_visualizacao]))
                        {
                            $va_campo_visualizacao = $va_visualizacao_lista["campos"][$vs_key_campo_visualizacao];

                            if (isset($va_campos_visualizacao[$vs_key_campo_visualizacao]["formato"]))
                            {
                                $va_campo_visualizacao = array_merge($va_campo_visualizacao, $va_campos_visualizacao[$vs_key_campo_visualizacao]);
                            }


                            if (isset($va_campo_visualizacao["main_field"]))
                                $vb_main_field = true;
                        }

                        $vs_label_campo = $vs_label_campo_visualizacao;
                        if (!$vs_label_campo)
                        {
                            if (isset($va_campo_visualizacao["label"]))
                                $vs_label_campo = $va_campo_visualizacao["label"];
                        }

                        $vs_valor_atributo = ler_valor1($vs_key_campo_visualizacao, $va_item, $va_campo_visualizacao);

                        if ($vb_id_field)
                        {
                            $va_item_listagem["id_field"] = $vs_valor_atributo;
                            $va_item_listagem["id_field_label"] = $vs_label_campo;
                        }

                        elseif ($vb_main_field)
                        {
                            if (!isset($va_item_listagem["main_field"]) && $vs_valor_atributo != "")
                                $va_item_listagem["main_field"] = $vs_valor_atributo;
                            elseif ($vs_valor_atributo != "")
                                $va_item_listagem["main_field"] = $va_item_listagem["main_field"] . ": " . $vs_valor_atributo;

                            $va_item_listagem["main_field_label"] = $vs_label_campo;
                        }
                        elseif ($vb_descriptive_field)
                        {
                            $va_item_listagem["descriptive_field"] = $vs_valor_atributo;

                            $va_item_listagem["descriptive_field_label"] = $vs_label_campo;
                        }

                        if ( ($vs_output == "out") || (!$vb_id_field && !$vb_main_field && !$vb_descriptive_field) )
                        {
                            /* if ($vs_valor_atributo != "")
                            { */

                                $va_atributo_item_listagem["label"] = $vs_label_campo;
                                $va_atributo_item_listagem["valor"] = $vs_valor_atributo;

                                if(isset($va_campo_visualizacao["exibir"]))
                                {
                                    $va_atributo_item_listagem["exibir"] = $va_campo_visualizacao["exibir"];
                                }
                                else
                                {
                                    $va_atributo_item_listagem["exibir"] = true;
                                }


                                $va_atributos_item_listagem[] = $va_atributo_item_listagem;
                           // }
                        }
                    }

                    $va_item_listagem["atributos"] = $va_atributos_item_listagem;

                    $va_item_listagem["_nivel"] = $vb_contador_nivel;

                    $va_itens_listagem[] = $va_item_listagem;
                    $va_itens_listagem_codigos[] = $va_item[$vo_objeto->get_chave_primaria()[0]];

                    if (!($vb_expandir_niveis_hierarquicos ?? false))
                        $vb_mostrar_registros_filhos = false;

                    if ($vb_mostrar_registros_filhos)
                    {
                        $va_registros_filhos = $vo_objeto->ler_lista([$vo_objeto->get_campo_hierarquico() => $va_item[$vo_objeto->get_chave_primaria()[0]]], "navegacao");

                        if (count($va_registros_filhos))
                        {
                            $vn_numero_registros_filhos = $vn_numero_registros_filhos + count($va_registros_filhos);

                            $va_item = array_shift($va_registros_filhos);

                            $vb_contador_nivel++;
                            $va_items_nivel[$vb_contador_nivel] = $va_registros_filhos;
                        }
                    }
                    else
                    {
                        // Quer dizer que eu cheguei ao fim de um nível (o item não tem filho)

                        $vb_achou_irmao = false;

                        while ( ($vb_contador_nivel > 0) && !$vb_achou_irmao)
                        {
                            if (count($va_items_nivel[$vb_contador_nivel]))
                            {
                                $va_item = array_shift($va_items_nivel[$vb_contador_nivel]);
                                $vb_achou_irmao = true;
                                $vb_mostrar_registros_filhos = true;
                            }
                            else
                                $vb_contador_nivel--;
                        }
                    }
                }
            }

            $_SESSION[$vs_id_objeto_tela]["codigo_primeiro_lista"] = $va_objetos_primeiro_lista[0][$vo_objeto->get_chave_primaria()[0]];
            $_SESSION[$vs_id_objeto_tela]["codigo_ultimo_lista"] = $va_objetos_ultimo_lista[0][$vo_objeto->get_chave_primaria()[0]];
            $_SESSION[$vs_id_objeto_tela]["listagem_codigos"] = $va_itens_listagem_codigos;
        }
    }       
?>