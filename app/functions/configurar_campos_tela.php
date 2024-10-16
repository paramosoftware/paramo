<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    if (!isset($vs_id_objeto_tela))
    {
        if (isset($_GET['obj']))
            $vs_id_objeto_tela = $_GET['obj'];
        else
            exit();
    }

    if (!isset($vs_modo))
    {
        if (isset($_GET['modo']))
            $vs_modo = $_GET['modo'];
        else
            exit();
    }

    if (!isset($vs_id_campo))
    {
        if (isset($_GET['campo']))
            $vs_id_campo = $_GET['campo'];
        else
            $vs_id_campo = "";
    }           

    $va_abas_form = array();
    $va_campos_edicao = array();
    $va_campos_filtro = array();

    // Eis aqui: onde o objeto_base vai montar automaticamente os atributos, campos e visualizações do
    // objeto passado por parâmetro no construtor
    //////////////////////////////////////////////////////////////////////////////////////////////////

    $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);

    //////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////

    $va_form_edicao = array();
    $va_campos = array();

    if ( ($vs_modo == "edicao") || ($vs_modo == "duplicacao") || ($vs_modo == "lote") )
    {
        if (!isset($pn_objeto_codigo))
            $pn_objeto_codigo = "";

        if (!isset($va_objeto))
            $va_objeto = array();

        $vs_modo_form = "completo";

        $va_abas_form = $vo_objeto->get_form_edicao($vs_modo_form);
        $va_campos = $vo_objeto->get_campos_edicao($pn_objeto_codigo, $vn_bibliografia_codigo, $va_objeto);

        $vo_campos_sistema = new campo_sistema;
        $va_campos_sistema = $vo_campos_sistema->ler_lista(["campo_sistema_recurso_sistema_codigo" => $vo_objeto->get_recurso_sistema_codigo()]);

        foreach ($va_campos_sistema as $va_campo_sistema)
        {
            if (isset($va_campo_sistema["campo_sistema_descricao"]) && isset($va_campos[$va_campo_sistema["campo_sistema_nome"]]))
            {
                $va_campos[$va_campo_sistema["campo_sistema_nome"]]["descricao"] = $va_campo_sistema["campo_sistema_descricao"];
            }
        }
        
        // Adicionando aqui a parametrização por formato de ficha/formulário
        ////////////////////////////////////////////////////////////////////

        $vb_habilitar_campo_representante_digital = true;

        if (isset($vn_formato_ficha_codigo) && $vn_formato_ficha_codigo)
        {
            $vo_visualizacao = new visualizacao;
            $va_visualizacao = $vo_visualizacao->ler($vn_formato_ficha_codigo, "ficha");

            $va_campos_temp = array();

            foreach ($va_visualizacao["visualizacao_campo_sistema_codigo"] as $va_campo_sistema)
            {
                if (in_array($va_campo_sistema["visualizacao_campo_sistema_codigo"]["campo_sistema_nome"], array_keys($va_campos)))
                    $va_campos_temp[$va_campo_sistema["visualizacao_campo_sistema_codigo"]["campo_sistema_nome"]] = $va_campos[$va_campo_sistema["visualizacao_campo_sistema_codigo"]["campo_sistema_nome"]];
            }

            $va_campos = $va_campos_temp;
        }
        elseif ($vo_objeto->tipo_hierarquia == "subordinada" && isset($va_objeto[$vo_objeto->get_campo_hierarquico()]))
        {
            $va_campos_temp = array();

            foreach (array_keys($vo_objeto->get_visualizacao("hierarquia_subordinada")["ordem_campos"]) as $vs_key_campo)
            {
                $va_campos_temp[$vs_key_campo] = $va_campos[$vs_key_campo];
            }

            $va_campos = $va_campos_temp;

            $vb_habilitar_campo_representante_digital = false;
        }

        //////////////////////////////////////////////////////////////////////////////
        
        // Só adiciona o campo de representantes digitais para registros já existentes
        //////////////////////////////////////////////////////////////////////////////

        if ($vb_habilitar_campo_representante_digital && $vo_objeto->get_recurso_sistema_codigo() && $pn_objeto_codigo && ($vs_modo != "lote"))
        {
            $va_campo_representante_digital = array("representante_digital_codigo" => [
                "html_representantes_digitais_input", 
                "nome" => "representante_digital_codigo", 
                "label" => "Representantes digitais",
                "tipo" => 1
            ]);

            $va_campo_arquivo_download = array("arquivo_download_codigo" => [
                "html_representantes_digitais_input", 
                "nome" => "arquivo_download_codigo", 
                "label" => "Anexos",
                "tipo" => 2
            ]);

            $va_campos = $va_campo_representante_digital + $va_campos;
        }
        
        foreach ($va_fluxos as $va_fluxo)
        {
            $vs_nome_campo_etapa_fluxo = "etapa_fluxo_codigo_" . $va_fluxo["fluxo_codigo"];
            
            $va_campo_etapa_fluxo = array($vs_nome_campo_etapa_fluxo => [
                "html_combo_input", 
                "nome" => $vs_nome_campo_etapa_fluxo, 
                "label" => "Status do registro", 
                "objeto" => "etapa_fluxo",
                "atributos" => ["etapa_fluxo_codigo", "etapa_fluxo_nome"],
                "atributo" => "etapa_fluxo_codigo",
                "sem_valor" => false,
                "formato" => "radio",
                "valor_padrao" => 1,
                "filtro" => [
                    [
                        "valor" => $va_fluxo["fluxo_codigo"], 
                        "atributo" => "fluxo_codigo"
                    ]
                ],
                "itens_desabilitados" => $va_etapas_sem_acesso
            ]);

            $va_campos = $va_campos + $va_campo_etapa_fluxo;
        }

        if ($vb_habilitar_campo_representante_digital && $vo_objeto->get_recurso_sistema_codigo() && $pn_objeto_codigo && ($vs_modo != "lote"))
            $va_campos = $va_campos + $va_campo_arquivo_download;

        // Por padrão, todos os campos de edição em lote só serão exibidos
        // e habilitados manualmente pelo usuário
        ///////////////////////////////////////////////////////////////////////

        if ($vs_modo == "lote")
        {
            foreach($va_campos as &$va_campo)
            {
                $va_campo["desabilitar"] = true;
            }
        }

        ///////////////////////////////////////////////////////////////////////

        if ($vs_modo == "duplicacao")
            $vs_modo = "edicao";

        $va_telas[$vs_modo][$vs_id_objeto_tela] = $va_campos;
    }
    elseif ($vs_modo == "listagem") 
    {
        if (!isset($pn_objeto_codigo))
            $pn_objeto_codigo = "";

        $va_campos = $vo_objeto->get_filtros_navegacao($vn_bibliografia_codigo);

        if ($vs_id_objeto_tela == "documento")
        {
            foreach ($va_fluxos as $va_fluxo)
            {
                $vs_nome_campo_etapa_fluxo = "etapa_fluxo_codigo_" . $va_fluxo["fluxo_codigo"];
                
                $va_campos[$vs_nome_campo_etapa_fluxo] = [
                    "html_combo_input", 
                    "nome" => $vs_nome_campo_etapa_fluxo, 
                    "label" => "Status do registro", 
                    "objeto" => "etapa_fluxo",
                    "atributos" => ["etapa_fluxo_codigo", "etapa_fluxo_nome"],
                    "atributo" => "etapa_fluxo_codigo",
                    "sem_valor" => true,
                    "filtro" => [
                        [
                            "valor" => $va_fluxo["fluxo_codigo"], 
                            "atributo" => "fluxo_codigo"
                        ]
                    ],
                    "css-class" => "form-select"
                ];
            }
        }

        // Por padrão, todos os campos de filtro só serão exibidos
        // no carregamento da página se estiverem preenchidos com termo de busca
        ///////////////////////////////////////////////////////////////////////

        if (!$vs_id_campo)
        {
            foreach($va_campos as &$va_campo)
            {
                $va_campo["exibir_quando_preenchido"] = true;
            }
        }

        //////////////////////////////////////////////////////////////////////////

        $va_telas["listagem"][$vs_id_objeto_tela] = $va_campos;
    }

    if ($vs_id_campo)
    {
        //if (isset($va_campos[$vs_id_campo]))
        {
            $va_campos = array();

            if (isset($vs_campo_pai) && $vs_campo_pai)
            {
                if (isset($vs_sufixo_nome_campo))
                    $vs_id_subcampo = str_replace($vs_sufixo_nome_campo, "", $vs_id_campo);

                $va_campos[$vs_id_campo] = $va_telas[$vs_modo][$vs_id_objeto_tela][$vs_campo_pai]["subcampos"][$vs_id_subcampo];

                if (isset($vs_sufixo_nome_campo))
                    $va_campos[$vs_id_campo]["sufixo_nome"] = $vs_sufixo_nome_campo;
            }
            else
                $va_campos[$vs_id_campo] = $va_telas[$vs_modo][$vs_id_objeto_tela][$vs_id_campo];

            if (isset($vb_multiplas_instancias_campo) && $vb_multiplas_instancias_campo)
            {
                if (isset($vs_novo_id_campo))
                {
                    if ($va_campos[$vs_id_campo][0] == "html_autocomplete")
                    {
                        $vs_autocomplete_lookup_id = str_replace("_codigo_F_", "_F_", $vs_novo_id_campo);

                        $va_campos[$vs_id_campo]["nome"] = [$vs_autocomplete_lookup_id, $vs_novo_id_campo];
                    }
                    else
                        $va_campos[$vs_id_campo]["nome"] = $vs_novo_id_campo;
                    
                    
                    $va_campos[$vs_id_campo]["busca_combinada"] = true;

                    //$va_campos[$vs_id_campo]["id"] = $vs_novo_id_campo;
                }
            }

            $va_campos[$vs_id_campo]["atualizacao"] = $vb_atualizacao_campo;
        }
        //else
            //$va_campos = array();
    }
    else
        $va_campos = $va_telas[$vs_modo][$vs_id_objeto_tela];
?>