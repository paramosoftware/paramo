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

        //////////////////////////////////////////////////////////////////////////////
        
        // Só adiciona o campo de representantes digitais para registros já existentes
        //////////////////////////////////////////////////////////////////////////////

        if ($vo_objeto->get_recurso_sistema_codigo() && $pn_objeto_codigo && ($vs_modo != "lote"))
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

        if ($vo_objeto->get_recurso_sistema_codigo() && $pn_objeto_codigo && ($vs_modo != "lote"))
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

        $va_telas["edicao"][$vs_id_objeto_tela] = $va_campos;

        $vs_modo = "edicao";
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

    if (!count($va_campos))
    {
        switch ($vs_id_objeto_tela)
        {
            case "audiovisual":
                $vs_objeto_tela = "audiovisual";
                
                

                // --------------------------------------- //

                $va_campos_filtro["texto_codigo_0_texto_titulo"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_titulo", 
                    "label" => "Título", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["texto_codigo_0_texto_entidade_nome"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_entidade_nome", 
                    "label" => "Autor", 
                    "operador_filtro" => "LIKE"
                ];

                // -------------------------------------- //
                
                break;

            case "campo_sistema":
                $vs_objeto_tela = "campo_sistema";

                

                // -------------------------------------------------------- /

                $va_campos_filtro["campo_sistema_nome"] = [
                    "html_text_input", 
                    "nome" => "campo_sistema_nome", 
                    "label" => "Id", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["campo_sistema_recurso_sistema_codigo"] = [
                    "html_combo_input", 
                    "nome" => "campo_sistema_recurso_sistema_codigo", 
                    "label" => "Recurso do sistema", 
                    "objeto" => "recurso_sistema",
                    "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
                    "atributo" => "recurso_sistema_codigo", 
                    "sem_valor" => true,
                    "operador_filtro" => "="
                ];

                break;

            case "editora":
                $vs_objeto_tela = "Editora";

                // ---------------------------------------------- //

                $va_campos_filtro["entidade_codigo_0_entidade_nome"] = [
                    "html_text_input", 
                    "nome" => "entidade_codigo_0_entidade_nome", 
                    "label" => "Nome da editora",
                    "operador_filtro" => "LIKE"
                ];

                break;

            case "entidade":

                $va_campos_filtro["entidade_nome"] = [
                    "html_text_input",
                    "nome" => "entidade_nome,entidade_principal_codigo_0_entidade_nome,entidade_variacoes_nome_codigo_0_entidade_nome",
                    "label" => "Nome",
                    "operador_filtro" => "LIKE",
                    "hierarquia" => true
                ];

                break;

            case "evento":
                $vs_objeto_tela = "Evento";



                // ---------------------------------------- //

                $va_campos_filtro["evento_tipo_evento_codigo"] = [
                    "html_combo_input", 
                    "nome" => "evento_tipo_evento_codigo", 
                    "label" => "Tipo", 
                    "objeto" => "tipo_evento",
                    "parametros_inicializacao" => "tipo_evento",
                    "atributos" => ["tipo_evento_codigo", "tipo_evento_nome"],
                    "atributo" => "tipo_evento_codigo",
                    "sem_valor" => true, 
                    "operador_filtro" => "="
                ];

                $va_campos_filtro["evento_nome_relacionado_codigo_0_entidade_nome"] = [
                    "html_text_input",
                    "nome" => "evento_nome_relacionado_codigo_0_entidade_nome",
                    "label" => "Nome relacionado",
                    "operador_filtro" => "LIKE"
                ];

                break;

            case "exemplar_livro":
                $vs_objeto_tela = "Exemplar_Livro";

                $va_campos_edicao["acervo_codigo"] = [
                    "html_combo_input",
                    "nome" => "acervo_codigo",
                    "label" => "Acervo",
                    "objeto" => "Acervo",
                    "atributo" => "acervo_codigo",
                    "sem_valor" => false,
                    //"conectar" => [
                    //    ["campo" => "documento_agrupamento_codigo", "atributo" => "agrupamento_acervo_codigo"],
                    //    ["campo" => "documento_unidade_armazenamento_codigo", "atributo" => "unidade_armazenamento_acervo_codigo"]
                    //]
                ];

                $va_campos_edicao["texto_codigo"] = [
                    "html_autocomplete",
                    "nome" => ['texto_titulo', 'texto_codigo'],
                    "label" => "Título",
                    "objeto" => "Texto",
                    "atributos" => ["texto_codigo", "texto_titulo"],
                    "multiplos_valores" => false,
                    "procurar_por" => "texto_titulo",
                    "visualizacao" => "lista",
                    "foco" => true
                ];

                $va_campos_edicao["livro_codigo"] = [
                    "html_text_input",
                    "nome" => "livro_codigo",
                    "label" => "Livro",
                    "exibir" => false
                ];

                $va_campos_edicao["texto_entidade_codigo"] = [
                    "html_autocomplete",
                    "nome" => ["texto_entidade", "texto_entidade_codigo"],
                    "label" => "Autor",
                    "objeto" => "entidade",
                    "multiplos_valores" => true,
                    "procurar_por" => "entidade_nome",
                    "visualizacao" => "lista",
                    "permitir_cadastro" => true,
                    "campo_salvar" => "entidade_nome"
                ];

                $va_campos_edicao["texto_idioma_codigo"] = [
                    "html_autocomplete",
                    "nome" => ["texto_idioma", "texto_idioma_codigo"],
                    "label" => "Idioma",
                    "objeto" => "idioma",
                    "atributos" => ["idioma_codigo", "idioma_nome"],
                    "multiplos_valores" => true,
                    "procurar_por" => "idioma_nome",
                    "visualizacao" => "lista",
                    "permitir_cadastro" => true,
                    "campo_salvar" => "idioma_nome"
                ];

                $va_campos_edicao["livro_editora"] = [
                    "html_autocomplete",
                    "nome" => "livro_editora",
                    "label" => "Editora",
                    "objeto" => "Editora",
                    "multiplos_valores" => true,
                    "procurar_por" => "entidade_nome",
                    "visualizacao" => "lista",
                    "formato" => ["campo" => "entidade_nome"]
                ];

                $va_campos_edicao["livro_ano"] = [
                    "html_date_input",
                    "nome" => "livro_ano",
                    "label" => "Ano de publicação"
                ];

                $va_campos_edicao["livro_numero_paginas"] = [
                    "html_text_input",
                    "nome" => "livro_numero_paginas",
                    "label" => "Número de páginas",
                ];

                $va_campos_edicao["exemplar_livro_classificacao"] = [
                    "html_text_input",
                    "nome" => "exemplar_livro_classificacao",
                    "label" => "Classificação",
                ];

                $va_campos_edicao["exemplar_livro_notas"] = [
                    "html_text_input",
                    "nome" => "exemplar_livro_notas",
                    "label" => "Notas",
                ];

                break;

            case "exemplar_periodico":

                break;

            case "formato":
                $vs_objeto_tela = "Formato";
                
                $va_campos_edicao["formato_nome"] = ["html_text_input", "nome" => "formato_nome", "label" => "Nome", "foco" => true];
                $va_campos_edicao["formato_descricao"] = ["html_text_input", "nome" => "formato_descricao", "label" => "Definição", "numero_linhas" => 8];

                $va_campos_filtro["formato_nome"] = ["html_text_input", "nome" => "formato_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "grupo_usuario":
                $vs_objeto_tela = "Grupo_Usuario";
                
                $va_campos_filtro["grupo_usuario_nome"] = ["html_text_input", "nome" => "grupo_usuario_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "idioma":
                $vs_objeto_tela = "idioma";
                
                $va_campos_filtro["idioma_nome"] = ["html_text_input", "nome" => "idioma_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "imagem":
                $vs_objeto_tela = "imagem";

                // --------------------------------------- //

                $va_campos_filtro["texto_titulo"] = [
                    "html_text_input", 
                    "nome" => "texto_titulo", 
                    "label" => "Legenda", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["texto_codigo_0_texto_entidade_codigo"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_entidade_codigo", 
                    "label" => "Fotógrafo", 
                    "operador_filtro" => "LIKE"
                ];

                // -------------------------------------- //
                
                break;
            
            case "instituicao":
                $vs_objeto_tela = "Instituicao";

                //$va_campos_filtro["instituicao_nome"] = ["html_text_input", "nome" => "instituicao_nome", "label" => "Nome", "operador_filtro" => "LIKE"];

                break;
            
            case "livro":
                $vs_objeto_tela = "Livro";

                // --------------------------------------- //

                
                // -------------------------------------- //
                
                break;

            case "localidade":
                $vs_objeto_tela = "Localidade";

                $va_campos_filtro["localidade_nome"] = ["html_text_input", "nome" => "localidade_nome", "label" => "Nome", "operador_filtro" => "LIKE"];

                break;

            case "pagina_site":
                $vs_objeto_tela = "pagina_site";

                // --------------------------------------- //

                $va_campos_filtro["pagina_site_nome"] = ["html_text_input", "nome" => "pagina_site_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "palavra_chave":
                // --------------------------------------- //

                $va_campos_filtro["palavra_chave_nome"] = [
                    "html_text_input",
                    "nome" => "palavra_chave_nome",
                    "label" => "Palavra-chave",
                    "operador_filtro" => "LIKE"
                ];

                break;
                    
            case "papel":
                $vs_objeto_tela = "Papel";
                
                $va_campos_filtro["papel_nome"] = ["html_text_input", "nome" => "papel_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "parte_livro":
                $vs_objeto_tela = "parte_livro";
                
                // --------------------------------------- //

                $va_campos_filtro["texto_codigo_0_texto_titulo"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_titulo", 
                    "label" => "Título", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["texto_codigo_0_texto_autor_codigo"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_autor_codigo", 
                    "label" => "Título", 
                    "operador_filtro" => "LIKE"
                ];

                // -------------------------------------- //
                
                break;
            
            case "periodico":
                $vs_objeto_tela = "Periodico";

                // ---------------------------------------- //

                $va_campos_filtro["entidade_codigo_0_entidade_nome"] = [
                    "html_text_input", 
                    "nome" => "entidade_codigo_0_entidade_nome", 
                    "label" => "Nome", 
                    "operador_filtro" => "LIKE"
                ];
                
                break;

            case "serie":
                $vs_objeto_tela = "serie";
                
                // ------------------------------------ //

                $va_campos_filtro["serie_nome"] = [
                    "html_text_input", 
                    "nome" => "serie_nome", 
                    "label" => "Nome", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["serie_acervo_codigo"] = [
                    "html_combo_input", 
                    "nome" => "serie_acervo_codigo", 
                    "label" =>"Acervo", 
                    "objeto" => "acervo",
                    "atributos" => ["acervo_codigo", "acervo_nome"],
                    "atributo" => "acervo_codigo",
                    "sem_valor" => true, 
                    "operador_filtro" => "="
                ];

                break;
            
            case "setor":
                $vs_objeto_tela = "Setor";
                
                // ------------------------------------------- //

                $va_campos_filtro["setor_nome"] = ["html_text_input", "nome" => "setor_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                $va_campos_filtro["setor_instituicao_codigo"] = [
                    "html_combo_input", 
                    "nome" => "setor_instituicao_codigo", 
                    "label" =>"Instituição", 
                    "objeto" => "instituicao", 
                    "atributos" => ["instituicao_codigo", "entidade_nome"],
                    "atributo" => "instituicao_codigo",
                    "sem_valor" => true, 
                    "operador_filtro" => "="
                ];

                break;

            case "suporte":
                $vs_objeto_tela = "Suporte";
                
                $va_campos_filtro["suporte_nome"] = ["html_text_input", "nome" => "suporte_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "tese":
                $vs_objeto_tela = "tese";                

                // --------------------------------------- //

                $va_campos_filtro["texto_titulo"] = [
                    "html_text_input", 
                    "nome" => "texto_titulo", 
                    "label" => "Nome", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["texto_autor_codigo"] = [
                    "html_autocomplete", 
                    "nome" => ["texto_autor", "texto_autor_codigo"], 
                    "label" => "Autor", 
                    "objeto" => "Entidade", 
                    "atributos" => ["entidade_codigo", "entidade_nome"],
                    "multiplos_valores" => false, 
                    "procurar_por" => "entidade_nome", 
                    "visualizacao" => "lista",
                    "operador_filtro" => "="
                ];

                break;
            
            case "texto_web":
                $vs_objeto_tela = "texto_web";

                // --------------------------------------- //

                $va_campos_filtro["texto_codigo_0_texto_dados_textuais_0_texto_titulo"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_dados_textuais_0_texto_titulo", 
                    "label" => "Título", 
                    "operador_filtro" => "LIKE"
                ];

                $va_campos_filtro["texto_codigo_0_texto_entidade_codigo_com_tipo_0_entidade_nome"] = [
                    "html_text_input", 
                    "nome" => "texto_codigo_0_texto_entidade_codigo_com_tipo_0_entidade_nome", 
                    "label" => "Autor", 
                    "operador_filtro" => "LIKE"
                ];

                /*
                $va_campos_filtro["texto_web_website_codigo"] = [
                    "html_text_input", 
                    "nome" => "texto_web_website_codigo", 
                    "label" => "Website", 
                    "operador_filtro" => "LIKE"
                ];
                */

                // -------------------------------------- //
                
                break;

            case "tipo_dimensao":
                $vs_objeto_tela = "Tipo_Dimensao";
                
                $va_campos_filtro["tipo_dimensao_nome"] = ["html_text_input", "nome" => "tipo_dimensao_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "tipo_documental":
                $vs_objeto_tela = "Tipo_Documental";

                // ----------------------------------------//

                $va_campos_filtro["tipo_documental_nome"] = ["html_text_input", "nome" => "tipo_documental_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "tipo_evento":
                $vs_objeto_tela = "Tipo_Evento";

                $va_campos_filtro["tipo_evento_nome"] = ["html_text_input", "nome" => "tipo_evento_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "tipo_responsabilidade":
                $vs_objeto_tela = "Tipo_Responsabilidade";
                
                $va_campos_filtro["tipo_responsabilidade_nome"] = ["html_text_input", "nome" => "tipo_responsabilidade_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "recurso_sistema":
                $vs_objeto_tela = "recurso_sistema";

                // --------------------------------------------------------- //

                $va_campos_filtro["recurso_sistema_nome_plural"] = ["html_text_input", "nome" => "recurso_sistema_nome_plural", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "selecao":
                $vs_objeto_tela = "Selecao";

                $va_campos_filtro["selecao_nome"] = ["html_text_input", "nome" => "selecao_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;
            
            case "unidade_armazenamento":
                $vs_objeto_tela = "Unidade_Armazenamento";

                // ----------------------------------------------- //

                $va_campos_filtro["unidade_armazenamento_nome"] = [
                    "html_text_input", 
                    "nome" => "unidade_armazenamento_nome", 
                    "label" => "Nome", 
                    "operador_filtro" => "LIKE"
                ];
                
                $va_campos_filtro["unidade_armazenamento_acervo_codigo"] = [
                    "html_combo_input", 
                    "nome" => "unidade_armazenamento_acervo_codigo", 
                    "label" => "Acervo", 
                    "objeto" => "Acervo", 
                    "atributos" => ["acervo_codigo", "entidade_nome"],
                    "atributo" => "acervo_codigo", 
                    "sem_valor" => true, 
                    "operador_filtro" => "=",
                    "dependencia" => [
                        "campo" => "acervo_codigo", 
                        "atributo" => "unidade_armazenamento_acervo_codigo"
                    ]
                ];

                break;

            case "unidade_medida":
                $vs_objeto_tela = "Unidade_Medida";

                $va_campos_filtro["unidade_medida_nome"] = ["html_text_input", "nome" => "unidade_medida_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;
            
            case "usuario":
                $vs_objeto_tela = "Usuario";
                
                // -------------------------------------- //
                
                $va_campos_filtro["usuario_nome"] = ["html_text_input", "nome" => "usuario_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            case "visualizacao":
                $vs_objeto_tela = "Visualizacao";
                
                // -------------------------------------- //
                
                $va_campos_filtro["visualizacao_nome"] = ["html_text_input", "nome" => "visualizacao_nome", "label" => "Nome", "operador_filtro" => "LIKE"];
                
                break;

            default:
                //print "Não é possível carregar formulário de cadastro!";
                //exit();
        }
    
        $va_telas["edicao"][$vs_id_objeto_tela] = $va_campos_edicao;
        $va_telas["listagem"][$vs_id_objeto_tela] = $va_campos_filtro;
    }
    
    if ($vs_id_campo)
    {
        $va_campos = array();

        if (isset($vs_campo_pai) && $vs_campo_pai)
        {
            $va_campos[$vs_id_campo] = $va_telas[$vs_modo][$vs_id_objeto_tela][$vs_campo_pai]["subcampos"][$vs_id_campo];

            if (isset($vs_sufixo_nome_campo))
                $va_campos[$vs_id_campo]["sufixo_nome"] = $vs_sufixo_nome_campo;
        }
        else
            $va_campos[$vs_id_campo] =  $va_telas[$vs_modo][$vs_id_objeto_tela][$vs_id_campo];

        $va_campos[$vs_id_campo]["atualizacao"] = $vb_atualizacao_campo;
    }
    else
        $va_campos = $va_telas[$vs_modo][$vs_id_objeto_tela];
?>