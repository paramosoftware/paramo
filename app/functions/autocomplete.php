<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";
    require_once dirname(__FILE__) . "/../components/ler_valor.php";

    // As listas de sugestões vão ser especificadas aqui, por enquanto,
    // e não criadas automaticamente
    
    $va_listas_sugestao["acervo_acervo"] = [
        "html_combo_input", 
        "nome" => "acervo_acervo", 
        "label" => "Selecionar", 
        "objeto" => "acervo",
        "atributos" => ["acervo_codigo", "acervo_nome"],
        "dependencia" => [
            [
                "campo" => "acervo_nome",
                "atributo" => "acervo_nome",
                "obrigatoria" => true
            ],
            [
                "campo" => "acervo_instituicao_codigo",
                "atributo" => "acervo_instituicao_codigo",
                "obrigatoria" => true
            ]
        ]
    ];

    $va_listas_sugestao["agrupamento"] = [
        "html_combo_input", 
        "nome" => "agrupamento", 
        "label" => "Selecionar", 
        "objeto" => "agrupamento",
        "atributos" => 
        [
            "agrupamento_codigo", 
            "agrupamento_dados_textuais_0_agrupamento_nome" => ["hierarquia" => "agrupamento_agrupamento_superior_codigo"]
        ],
        "dependencia" => [
            [
                "campo" => "agrupamento_dados_textuais_0_agrupamento_nome",
                "atributo" => "agrupamento_dados_textuais_0_agrupamento_nome"
            ]
        ]
    ];

    $va_listas_sugestao["documento_agrupamento"] = [
        "html_combo_input", 
        "nome" => "documento_agrupamento", 
        "label" => "Selecionar", 
        "objeto" => "agrupamento",
        "atributos" => 
        [
            "agrupamento_codigo", 
            "agrupamento_dados_textuais_0_agrupamento_nome" => ["hierarquia" => "agrupamento_agrupamento_superior_codigo"]
        ],
        "dependencia" => [
            [
                "campo" => "agrupamento_dados_textuais_0_agrupamento_nome",
                "atributo" => "agrupamento_dados_textuais_0_agrupamento_nome"
            ],
            [
                "campo" => "agrupamento_acervo_codigo", 
                "atributo" => "agrupamento_acervo_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["documento_contexto"] = [
        "html_combo_input", 
        "nome" => "documento_contexto", 
        "label" => "Selecionar", 
        "objeto" => "contexto",
        "atributos" => 
        [
            "contexto_codigo", 
            "contexto_dados_textuais_0_contexto_nome" => ["hierarquia" => "contexto_contexto_superior_codigo"]
        ],
        "dependencia" => [
            [
                "campo" => "contexto_dados_textuais_0_contexto_nome",
                "atributo" => "contexto_dados_textuais_0_contexto_nome"
            ],
            [
                "campo" => "contexto_acervo_codigo", 
                "atributo" => "contexto_acervo_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["documento_serie"] = [
        "html_combo_input", 
        "nome" => "documento_serie", 
        "label" => "Selecionar", 
        "objeto" => "serie",
        "atributos" => 
        [
            "serie_codigo", 
            "serie_nome"
        ],
        "dependencia" => [
            [
                "campo" => "serie_nome",
                "atributo" => "serie_nome"
            ],
            [
                "campo" => "serie_acervo_codigo", 
                "atributo" => "serie_acervo_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["especie_documental_tipo_documental"] = [
        "html_combo_input", 
        "nome" => "especie_documental_tipo_documental", 
        "label" => "Selecionar", 
        "objeto" => "tipo_documental",
        "atributos" => 
        [
            "tipo_documental_codigo", 
            "tipo_documental_nome"
        ],
        "dependencia" => [
            [
                "campo" => "especie_documental_codigo", 
                "atributo" => "especie_documental_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["item_acervo_item_acervo"] = [
        "html_combo_input",
        "nome" => "item_acervo_item_acervo",
        "label" => "Selecionar",
        "objeto" => "item_acervo",
        "atributos" => ["item_acervo_codigo", "item_acervo_identificador" => ["item_acervo_identificador", "item_acervo_dados_textuais_0_item_acervo_titulo"]],
        "dependencia" => [
            [
                "campo" => "item_acervo_identificador",
                "atributo" => "item_acervo_identificador,item_acervo_dados_textuais_0_item_acervo_titulo"
            ],
            [
                "campo" => "item_acervo_instituicao_codigo",
                "atributo" => "item_acervo_instituicao_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["contexto_item_acervo"] = [
        "html_combo_input",
        "nome" => "contexto_item_acervo",
        "label" => "Selecionar",
        "objeto" => "texto",
        "atributos" => ["texto_codigo", "item_acervo_identificador" => ["item_acervo_identificador", "texto_dados_textuais_0_texto_titulo"]],
        "dependencia" => [
            [
                "campo" => "item_acervo_identificador",
                "atributo" => "item_acervo_identificador,texto_dados_textuais_0_texto_titulo"
            ],
            [
                "campo" => "texto_instituicao_codigo",
                "atributo" => "texto_instituicao_codigo"
            ]
        ],
        "numero_maximo_itens" => 100
    ];

    $va_listas_sugestao["item_acervo_local_armazenamento"] = [
        "html_combo_input", 
        "nome" => "item_acervo_local_armazenamento", 
        "label" => "Selecionar", 
        "objeto" => "local_armazenamento",
        "atributos" => 
        [
            "local_armazenamento_codigo", 
            "local_armazenamento_nome" => ["hierarquia" => "local_armazenamento_local_armazenamento_superior_codigo"]
        ],
        "dependencia" => [
            [
                "campo" => "local_armazenamento_nome",
                "atributo" => "local_armazenamento_nome,local_armazenamento_local_armazenamento_superior_codigo_0_local_armazenamento_nome"
            ],
            [
                "campo" => "local_armazenamento_instituicao_codigo", 
                "atributo" => "local_armazenamento_instituicao_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["livro_colecao"] = [
        "html_combo_input",
        "nome" => "livro_colecao",
        "label" => "Selecionar",
        "objeto" => "colecao",
        "atributos" =>
        [
            "colecao_codigo",
            "colecao_nome"
        ],
        "dependencia" => [
            [
                "campo" => "colecao_nome",
                "atributo" => "colecao_nome"
            ],
            [
                "campo" => "tema_acervo_codigo",
                "atributo" => "colecao_acervo_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["livro_tema"] = [
        "html_combo_input",
        "nome" => "livro_tema",
        "label" => "Selecionar",
        "objeto" => "tema",
        "atributos" =>
        [
            "tema_codigo",
            "tema_nome"
        ],
        "dependencia" => [
            [
                "campo" => "tema_nome",
                "atributo" => "tema_nome"
            ],
            [
                "campo" => "tema_acervo_codigo",
                "atributo" => "tema_acervo_codigo"
            ]
        ]
    ];

    $va_listas_sugestao["texto_entidade"] = [
        "html_combo_input", 
        "nome" => "texto_entidade", 
        "label" => "Selecionar", 
        "objeto" => "entidade",
        "atributos" => 
        [
            "entidade_codigo", 
            "entidade_nome" => ["hierarquia" => "entidade_principal_codigo", "sentido" => "inverso"]
        ],
        "dependencia" => [
            [
                "campo" => "entidade_nome",
                "atributo" => "entidade_nome,entidade_principal_codigo_0_entidade_variacoes_nome_codigo_0_entidade_nome"
            ]
        ]
    ];

    $va_listas_sugestao["usuario_acervo"] = [
        "html_combo_input", 
        "nome" => "usuario_acervo", 
        "label" => "Selecionar", 
        "objeto" => "acervo",
        "atributos" => ["acervo_codigo", "acervo_nome"],
        "dependencia" => [
            [
                "campo" => "acervo_nome",
                "atributo" => "acervo_nome",
                "obrigatoria" => true
            ],
            /*
            [
                "campo" => "acervo_instituicao_codigo",
                "atributo" => "acervo_instituicao_codigo",
                "obrigatoria" => true
            ]
            */
        ]
    ];

    $va_listas_sugestao["usuario_setor"] = [
        "html_combo_input",
        "nome" => "usuario_setor",
        "label" => "Selecionar",
        "objeto" => "setor",
        "atributos" => ["setor_codigo", "setor_nome"],
        "dependencia" => [
            [
                "campo" => "setor_nome",
                "atributo" => "setor_nome"
            ],
            [
                "campo" => "instituicao_codigo",
                "atributo" => "instituicao_codigo"
            ]
        ]
    ];

    ////////////////////////////////////////////////////
    ///////////////////////////////////////////////////
    
    $vs_id_objeto_campo = $vs_id_objeto_tela;

    if (isset($_GET['tela']))
        $vs_id_objeto_tela = $_GET['tela'];
    else
        $vs_id_objeto_tela = "";

    $vs_campo = "";
    if (isset($_GET['campo']))
        $vs_campo = $_GET['campo'];
    
    if (!$vs_campo)
    {
        print "Não é possível carregar a lista de sugestões. (campo)";
        exit();
    }

    $vs_campo_codigos = "";
    if (isset($_GET['campo_codigos']))
        $vs_campo_codigos = $_GET['campo_codigos'];

    $vs_procurar_por = "";
    if (isset($_GET['procurar_por']))
        $vs_procurar_por = $_GET['procurar_por'];
    
    if (!$vs_procurar_por)
    {
        print "Não é possível carregar a lista de sugestões. (procurar por)";
        exit();
    }

    $vs_campo_codigo = "";
    if (isset($_GET['campo_codigo']))
        $vs_campo_codigo = $_GET['campo_codigo'];
    
    if (!$vs_campo_codigo)
    {
        print "Não é possível carregar a lista de sugestões. (campo_codigo)";
        exit();
    }

    $vs_campo_valor = "";
    if (isset($_GET['campo_valor']))
        $vs_campo_valor = $_GET['campo_valor'];
    
    if (!$vs_campo_valor)
    {
        print "Não é possível carregar a lista de sugestões. (campo_valor)";
        exit();
    }

    $vb_permitir_cadastro = false;
    if (isset($_GET['permitir_cadastro']))
        $vb_permitir_cadastro = $_GET['permitir_cadastro'];

    $vs_termo = "";
    if (isset($_GET['termo']))
        $vs_termo = trim($_GET['termo']);

    $vs_operador = $_GET['operador'] ?? "LIKE";

    $vb_configuracao_padrao = $_GET['configuracao_padrao'] ?? 0;

    $vn_item_excluir = "";
    if (isset($_GET['excluir']))
        $vn_item_excluir = trim($_GET['excluir']);

    if (!isset($vb_aplicar_controle_acesso))
        $vb_aplicar_controle_acesso = true;

    if (!$vs_termo)
    {
        exit();
    }

    $va_termo_busca[$vs_procurar_por] = [$vs_termo, strlen($vs_termo) > 3 ? $vs_operador : "LIKERIGHT"];
    $va_termo_busca = array_merge($va_termo_busca, $_GET);

    $vo_objeto_tela = new $vs_id_objeto_tela;

    $va_campos_edicao = $vo_objeto_tela->get_campos_edicao();

    if (isset($va_campos_edicao[$vs_campo_codigos]))
        $va_parametros_campo_pai = $va_campos_edicao[$vs_campo_codigos];

    $vo_objeto = new $vs_id_objeto_campo;

    $va_parametros_campo = array();

    if (!$vb_configuracao_padrao)
        $va_parametros_campo = $vo_objeto->get_campo_autocomplete($vs_campo, $vs_campo_codigo, $va_parametros_campo_pai['modo'] ?? "");

    if (!count($va_parametros_campo))
    {
        if (!isset($va_listas_sugestao[$vs_campo]))
        {
            $va_parametros_campo = [
                "html_combo_input",
                "nome" => $vs_campo,
                "label" => "Selecionar",
                "objeto" => $vs_id_objeto_campo,
                "atributos" => [$vs_campo_codigo, $vs_campo_valor],
                "dependencia" =>
                    [
                        "campo" => $vs_procurar_por,
                        "atributo" => $vs_procurar_por
                    ]
            ];
        }
        else
            $va_parametros_campo = $va_listas_sugestao[$vs_campo];
    }

    if (!isset($va_parametros_campo["numero_maximo_itens"]))
    {
        $va_parametros_campo["numero_maximo_itens"] = 50;
    }

    if (isset($va_parametros_campo_pai["permitir_entrada_avulsa"]))
    {
        $va_parametros_campo["permitir_entrada_avulsa"] = $va_parametros_campo_pai["permitir_entrada_avulsa"];
    }

    if ($vn_item_excluir)
    {
        $va_parametros_campo["filtro"][] =
            [
                "valor" => $vn_item_excluir,
                "atributo" => $vs_campo_codigo,
                "operador" => "NOT IN"
            ];
    }

    $va_parametros_campo["termos_inexistentes"][] = $va_termo_busca["termo"];


    // No form de edição é aqui que eu controlo a exibição de instituições e acervos
    // conforme as permissões do usuário
    ///////////////////////////////////////////////////////////////////////////////

    
    if ( $vb_aplicar_controle_acesso && (in_array($va_parametros_campo["atributos"][0], array_keys($va_parametros_controle_acesso))) )
    {
        if (!in_array($va_parametros_controle_acesso[$va_parametros_campo["atributos"][0]], ["", "_ALL_"]))
            $va_termo_busca[$va_parametros_campo["atributos"][0]] = $va_parametros_controle_acesso[$va_parametros_campo["atributos"][0]];
    }
    //elseif (isset($va_termo_busca[$va_parametros_campo["nome"]]))
        //$va_termo_busca[$va_parametros_campo["atributo"]] = $va_objeto[$va_parametros_campo["nome"]];
    


    $vo_html_selection_list_input = new html_combo_input(null, $vs_campo, "autocomplete");
    $vo_html_selection_list_input->build($va_termo_busca, $va_parametros_campo);
    
    if ($vb_permitir_cadastro && $vb_pode_inserir)
    {
        $vo_objeto_tela = new $vs_id_objeto_tela('');
        $va_campos_edicao_objeto_tela = $vo_objeto_tela->get_campos_edicao();

        $vo_html_link_cadastrar = new html_link_cadastrar($vs_id_objeto_campo, $vs_campo);

        $vo_html_link_cadastrar->set_termo_busca($vs_termo);
        
        $vo_html_link_cadastrar->build($va_campos_edicao_objeto_tela[$vs_campo_codigos]["campo_salvar"] ?? null);
    }
?>
