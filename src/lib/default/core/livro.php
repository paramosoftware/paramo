<?php

class livro extends item_acervo
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->objeto_pai = "item_acervo";
        $this->campo_relacionamento_pai = "item_acervo_codigo";
        $this->excluir_objeto_pai = true;
        $this->pode_ser_incluido_selecao = true;

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->controlador_acesso = [
            "instituicao_codigo" => "item_acervo_codigo_0_item_acervo_instituicao_codigo",
            "acervo_codigo" => "item_acervo_codigo_0_item_acervo_acervo_codigo"
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "livro";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'livro_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['item_acervo_codigo'] = [
            'item_acervo_codigo',
            'coluna_tabela' => 'item_acervo_codigo',
            'tipo_dado' => 'i'
        ];

        $va_atributos['livro_classificacao'] = [
            'livro_classificacao',
            'coluna_tabela' => 'classificacao',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_edicao'] = [
            'livro_edicao',
            'coluna_tabela' => 'edicao',
            'tipo_dado' => 'i'
        ];

        $va_atributos['livro_numero_paginas'] = [
            'livro_numero_paginas',
            'coluna_tabela' => 'numero_paginas',
            'tipo_dado' => 'i'
        ];

        $va_atributos['livro_colecao'] = [
            'livro_colecao',
            'coluna_tabela' => 'colecao',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_numero_item_colecao'] = [
            'livro_numero_item_colecao',
            'coluna_tabela' => 'numero_item_colecao',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_isbn'] = [
            'livro_isbn',
            'coluna_tabela' => 'ISBN',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_issn'] = [
            'livro_issn',
            'coluna_tabela' => 'ISSN',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_creditos'] = [
            'livro_creditos',
            'coluna_tabela' => 'creditos',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_serie'] = [
            'livro_serie',
            'coluna_tabela' => 'serie',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_volume'] = [
            'livro_volume',
            'coluna_tabela' => 'volume',
            'tipo_dado' => 'i'
        ];

        $va_atributos['livro_numero'] = [
            'livro_numero',
            'coluna_tabela' => 'numero',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_tomo'] = [
            'livro_tomo',
            'coluna_tabela' => 'tomo',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_tombo'] = [
            'livro_tombo',
            'coluna_tabela' => 'tombo',
            'tipo_dado' => 's'
        ];

        $va_atributos['livro_tipo_material_codigo'] = [
            'livro_tipo_material_codigo',
            'coluna_tabela' => 'tipo_material_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_material'
        ];

        $va_atributos['livro_genero_textual_codigo'] = [
            'livro_genero_textual_codigo',
            'coluna_tabela' => 'genero_textual_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'genero_textual'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['livro_area_conhecimento_codigo'] = [
            'livro_area_conhecimento_codigo',
            'tabela_intermediaria' => 'livro_area_conhecimento',
            'chave_exportada' => 'livro_codigo',
            'campos_relacionamento' => [
                'livro_area_conhecimento_codigo' => 'area_conhecimento_codigo'
            ],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'area_conhecimento',
            'objeto' => 'area_conhecimento',
            'alias' => 'áreas do conhecimento'
        ];

        $va_relacionamentos['livro_colecao_codigo'] = [
            'livro_colecao_codigo',
            'tabela_intermediaria' => 'livro_colecao',
            'chave_exportada' => 'livro_codigo',
            'campos_relacionamento' => [
                'livro_colecao_codigo' => 'colecao_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'colecao',
            'objeto' => 'colecao',
            'alias' => 'coleções'
        ];

        $va_relacionamentos['livro_editora_codigo'] = [
            ['livro_editora_codigo', 'livro_editora_localidade_codigo'],
            'tabela_intermediaria' => 'livro_editora',
            'chave_exportada' => 'livro_codigo',
            'campos_relacionamento' => [
                'livro_editora_codigo' => 'editora_codigo',
                'livro_editora_localidade_codigo' => ['localidade_codigo', 'objeto' => 'localidade']
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'editora',
            'objeto' => 'editora',
            'alias' => 'editoras'
        ];

        $va_relacionamentos['item_selecao_codigo'] = [
            [
                'item_selecao_codigo',
            ],
            'tabela_intermediaria' => 'selecao_item',
            'chave_exportada' => 'item_codigo',
            'campos_relacionamento' => [
                'item_selecao_codigo' => 'selecao_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            "filtros" => ["selecao_recurso_sistema_codigo" => 1],
            'tabela_relacionamento' => 'selecao',
            'objeto' => 'selecao',
            'alias' => 'seleções'
        ];

        return $va_relacionamentos;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return array(
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "livro",
            "atributos" =>
                [
                    $ps_campo_codigo == '' ? "livro_codigo" : $ps_campo_codigo,
                    "item_acervo_identificador" => [
                        "formato" => [
                            "expressao" => [
                                "item_acervo_identificador",
                                " - " => [
                                    " - ",
                                    "constante" => true,
                                ],
                                "item_acervo_dados_textuais_0_item_acervo_titulo"
                            ]
                        ]
                    ]
                ],
            "dependencia" => [
                [
                    "campo" => "item_acervo_codigo_0_item_acervo_identificador",
                    "atributo" => "item_acervo_codigo_0_item_acervo_identificador,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo"
                ]
            ]
        );
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '', $pn_bibliografia_codigo = '')
    {
        $va_campos_edicao = array();

        $va_campos_edicao["item_acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_instituicao_codigo",
            "label" =>"Entidade custodiadora",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "atributo_obrigatorio" => true,
            "dependencia" => [
                [
                    "campo" => "instituicao_codigo",
                    "atributo" => "instituicao_codigo",
                    "obrigatoria" => true
                ]
            ],
            "conectar" => [
                [
                    "campo" => "item_acervo_acervo_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo"
                ]
            ],
            "sem_valor" => false,
            "filtro" => [
                [
                    "atributo" => "instituicao_acervo_codigo",
                    "valor" => 1,
                    "operador" => "_EXISTS_"
                ],
                [
                    "atributo" => "instituicao_acervo_codigo_0_acervo_setor_sistema_codigo",
                    "valor" => 2,
                ]
            ]
        ];

        $va_campos_edicao["item_acervo_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_acervo_codigo",
            "label" => "Acervo",
            "objeto" => "biblioteca",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => false,
            "atributo_obrigatorio" => true,
            "dependencia" => [
                [
                    "campo" => "item_acervo_instituicao_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo",
                    "obrigatoria" => true
                ],
                [
                    "campo" => "acervo_codigo",
                    "atributo" => "acervo_codigo",
                    "obrigatoria" => true
                ]
            ]
        ];

        $va_campos_edicao["item_acervo_identificador"] = [
            "html_text_input",
            "nome" => "item_acervo_identificador",
            "label" => "Identificador",
            "automatico" => true
        ];

        $va_campos_edicao["livro_tipo_material_codigo"] = [
            "html_combo_input",
            "nome" => "livro_tipo_material_codigo",
            "label" => "Tipo de material",
            "objeto" => "tipo_material",
            "atributo" => "tipo_material_codigo",
            "atributos" => ["tipo_material_codigo", "tipo_material_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["livro_classificacao"] = [
            "html_text_input",
            "nome" => "livro_classificacao",
            "label" => "Classificação"
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_titulo"] = [
            "html_text_input",
            "nome" => 'item_acervo_dados_textuais_0_item_acervo_titulo',
            "label" => "Título principal",
            "foco" => true,
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_subtitulo"] = [
            "html_text_input",
            "nome" => "item_acervo_dados_textuais_0_item_acervo_subtitulo",
            "label" => "Subtítulo"
        ];

        $va_campos_edicao["item_acervo_entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_entidade", "item_acervo_entidade_codigo"],
            "label" => "Autoria",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome",
            "subcampos" => [
                "autor_tipo_autor_codigo" => [
                    "html_combo_input",
                    "nome" => "autor_tipo_autor_codigo",
                    "label" => "Tipo",
                    "campo_pai" => "item_acervo_entidade_codigo",
                    "objeto" => "tipo_autor",
                    "atributos" => ["tipo_autor_codigo", "tipo_autor_nome"],
                    "atributo" => "tipo_autor_codigo",
                    "sem_valor" => true,
                    "valor_padrao" => 1
                ]
            ]
        ];

        $va_campos_edicao["livro_genero_textual_codigo"] = [
            "html_combo_input",
            "nome" => "livro_genero_textual_codigo",
            "label" => "Gênero textual",
            "objeto" => "genero_textual",
            "atributo" => "genero_textual_codigo",
            "atributos" => ["genero_textual_codigo", "genero_textual_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["livro_area_conhecimento_codigo"] = [
            "html_autocomplete",
            "nome" => ["livro_area_conhecimento", "livro_area_conhecimento_codigo"],
            "label" => "Área do conhecimento",
            "objeto" => "area_conhecimento",
            "atributos" => ["area_conhecimento_codigo", "area_conhecimento_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "area_conhecimento_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "area_conhecimento_nome"
        ];

        $va_campos_edicao["item_acervo_idioma_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_idioma", "item_acervo_idioma_codigo"],
            "label" => "Idioma",
            "objeto" => "idioma",
            "atributos" => ["idioma_codigo", "idioma_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "idioma_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "idioma_nome"
        ];

        $va_campos_edicao["livro_isbn"] = [
            "html_text_input",
            "nome" => "livro_isbn",
            "label" => "ISBN",
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_descricao"] = [
            "html_text_input",
            "nome" => "item_acervo_dados_textuais_0_item_acervo_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8,
        ];

        $va_campos_edicao["livro_edicao"] = [
            "html_combo_input",
            "nome" => "livro_edicao",
            "label" => "Edição",
            "objeto" => "edicao",
            "atributos" => ["edicao_codigo", "edicao_nome"],
            "atributo" => "edicao_codigo",
            "sem_valor" => true,
        ];

        $va_campos_edicao["livro_editora_codigo"] = [
            "html_autocomplete",
            "nome" => ["livro_editora", "livro_editora_codigo"],
            "label" => "Editora",
            "objeto" => "editora",
            "atributos" => ["editora_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_codigo_0_entidade_nome",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome",
            "visualizacao" => "lista",
            "formato" => ["campo" => "entidade_nome"]
        ];

        $va_campos_edicao["item_acervo_localidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_localidade", "item_acervo_localidade_codigo"],
            "label" => "Local de publicação",
            "objeto" => "localidade",
            "atributos" => ["localidade_codigo", "localidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "localidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "localidade_nome",
            "subcampos" => [
                "item_acervo_localidade_presumida" => [
                    "html_checkbox_input",
                    "nome" => "item_acervo_localidade_presumida",
                    "label" => "Presumida",
                    "campo_pai" => "item_acervo_localidade_codigo"
                ]
            ]
        ];

        $va_campos_edicao["item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_data",
            "label" => "Data de publicação",
            "formato" => "ano",
            "ano_maximo" => date("Y")
        ];

        $va_campos_edicao["livro_volume"] = [
            "html_number_input",
            "nome" => "livro_volume",
            "label" => "Volume/Número",
            "tamanho_maximo" => 999
        ];

        $va_campos_edicao["livro_numero_paginas"] = [
            "html_number_input",
            "nome" => "livro_numero_paginas",
            "label" => "Total de páginas",
            "tamanho_maximo" => 9999
        ];

        $va_campos_edicao["item_acervo_palavra_chave_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_palavra_chave", "item_acervo_palavra_chave_codigo"],
            "label" => "Palavras-chave",
            "objeto" => "palavra_chave",
            "atributos" => ["palavra_chave_codigo", "palavra_chave_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "palavra_chave_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "palavra_chave_nome"
        ];

        $va_campos_edicao["item_acervo_unidade_armazenamento_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_unidade_armazenamento_codigo",
            "label" => "Unidade de armazenamento",
            "objeto" => "unidade_armazenamento",
            "atributo" => "unidade_armazenamento_codigo",
            "atributos" => [
                "unidade_armazenamento_codigo",
                "unidade_armazenamento_nome" => [
                    "hierarquia" => "unidade_armazenamento_unidade_armazenamento_superior_codigo"
                ]
            ],
            "sem_valor" => true,
            "dependencia" => [
                [
                    "campo" => "item_acervo_acervo_codigo",
                    "atributo" => "serie_acervo_codigo",
                    "obrigatoria" => true
                ]
            ],
        ];

        $va_campos_edicao["item_acervo_publicado_online"] = [
            "html_checkbox_input",
            "nome" => "item_acervo_publicado_online",
            "label" => "Publicar online"
        ];

        return $va_campos_edicao;

    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();
        parent::inicializar_filtros_navegacao($pn_bibliografia_codigo);

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_codigo_0_item_acervo_instituicao_codigo",
            "label" => "Instituição",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "sem_valor" => true,
            "atributo_obrigatorio" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                [
                    "tipo" => "interface",
                    "campo" => "instituicao_codigo",
                    "atributo" => "instituicao_codigo",
                    "obrigatoria" => false
                ]
            ],
            "conectar" => [
                [
                    "campo" => "item_acervo_codigo_0_item_acervo_acervo_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo"
                ]
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_codigo_0_item_acervo_acervo_codigo",
            "label" => "Acervo",
            "objeto" => "biblioteca",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => true,
            "atributo_obrigatorio" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                [
                    "tipo" => "interface",
                    "campo" => "item_acervo_codigo_0_item_acervo_instituicao_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo",
                    "obrigatoria" => true
                ],
                [
                    "tipo" => "interface",
                    "campo" => "acervo_codigo",
                    "atributo" => "acervo_codigo",
                    "obrigatoria" => true
                ]
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_identificador"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_identificador",
            "label" => "Identificador",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_subtitulo,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_observacoes"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_subtitulo,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_observacoes",
            "label" => "Título, subtítulo, descrição ou observações",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome,item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_principal_codigo_0_entidade_nome"] = [
            "html_text_input",
            "exists_busca" => "item_acervo_codigo_0_item_acervo_entidade_codigo",
            "nome" => "item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome,item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_principal_codigo_0_entidade_nome",
            "label" => "Autoria",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_codigo_0_item_acervo_data",
            "label" => "Data de publicação",
            "operador_filtro" => "=",
            "exibir_presumido" => false,
        ];

        $va_filtros_navegacao["livro_codigo"] = [
            "html_text_input",
            "nome" => "livro_codigo",
            "label" => "Código de barras",
            "operador_filtro" => "="
        ];


        return array_merge($va_filtros_navegacao, $this->filtros_navegacao);
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["livro_codigo"] = [
            "nome" => "livro_codigo",
            "exibir" => false,
        ];

        $va_campos_visualizacao["item_acervo_colecao_codigo"] = [
            "nome" => "item_acervo_colecao_codigo",
            "formato" => ["campo" => "colecao_nome"],
            "label" => "Coleção"
        ];

        $this->visualizacoes["lista"]["campos"] = array_merge($this->visualizacoes["lista"]["campos"], $va_campos_visualizacao);
        $this->visualizacoes["lista"]["order_by"] = ["item_acervo_identificador" => "Identificador"];

        $va_campos_visualizacao["livro_classificacao"] = [
            "nome" => "livro_classificacao",
            "label" => "Classificação"
        ];

        $va_campos_visualizacao["livro_tombo"] = [
            "nome" => "livro_tombo",
            "label" => "Tombo"
        ];

        $va_campos_visualizacao["livro_editora_codigo"] = [
            "nome" => "livro_editora_codigo",
            "formato" => [
                "expressao" => [
                    "localidade_nome",
                    ": " => [
                        ": ",
                        "constante" => true,
                        "condicao" => ["localidade_nome", "<>vazio"]
                    ],
                    "entidade_nome"
                ],
            ],
            "label" => "Editora"
        ];

        $va_campos_visualizacao["livro_genero_textual_codigo"] = [
            "nome" => "livro_genero_textual_codigo",
            "formato" => ["campo" => "genero_textual_nome"]
        ];

        $va_campos_visualizacao["livro_area_conhecimento_codigo"] = [
            "nome" => "livro_area_conhecimento_codigo",
            "formato" => ["campo" => "area_conhecimento_nome"]
        ];

        $va_campos_visualizacao["livro_edicao"] = [
            "nome" => "livro_edicao",
            "formato" => [
                "expressao" => [
                    "livro_edicao",
                    ".ª ed." => [
                        ".ª ed.",
                        "constante" => true,
                        "condicao" => ["livro_edicao", "<>vazio"]
                    ],
                ]
            ]
        ];

        $va_campos_visualizacao["livro_numero_paginas"] = [
            "nome" => "livro_numero_paginas",
            "label" => "Número de páginas"
        ];

        $this->visualizacoes["navegacao"]["campos"] = array_merge($va_campos_visualizacao, $this->visualizacoes["navegacao"]["campos"]);
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "livro_codigo",
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "livro_classificacao" => "Classificação",
            "livro_cutter_pha" => "Cutter/PHA",
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Título", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_subtitulo" => ["label" => "Subtítulo", "main_field" => true],
            "item_acervo_entidade_codigo" => "Autoria",
            "livro_editora_codigo" => "Editora",
            "item_acervo_data" => "Data de publicação",
            "livro_numero_paginas" => "Número de páginas"
        ];

        $this->visualizacoes["navegacao"]["order_by"] = ["item_acervo_identificador" => "Identificador"];

        $va_campos_visualizacao["livro_isbn"] = ["nome" => "livro_isbn"];
        $va_campos_visualizacao["livro_issn"] = ["nome" => "livro_issn"];

        $va_campos_visualizacao["livro_creditos"] = [
            "nome" => "livro_creditos",
            "label" => "Créditos"
        ];

        $va_campos_visualizacao["livro_colecao_codigo"] = [
            "nome" => "livro_colecao_codigo",
            "formato" => ["campo" => "colecao_nome"]
        ];

        $va_campos_visualizacao["livro_exemplar"] = ["nome" => "livro_exemplar"];

        $va_campos_visualizacao["livro_serie"] = ["nome" => "livro_serie"];

        $va_campos_visualizacao["livro_volume"] = ["nome" => "livro_volume"];

        $va_campos_visualizacao["livro_numero"] = ["nome" => "livro_numero"];

        $va_campos_visualizacao["livro_tomo"] = ["nome" => "livro_tomo"];

        $va_campos_visualizacao["item_acervo_unidade_armazenamento_codigo"] = [
            "nome" => "item_acervo_unidade_armazenamento_codigo",
            "formato" => ["campo" => "unidade_armazenamento_nome"]
        ];

        $va_campos_visualizacao["livro_tipo_material_codigo"] = [
            "nome" => "livro_tipo_material_codigo",
            "formato" => ["campo" => "tipo_material_nome"],
            "label" => "Tipo de material"
        ];

        $this->visualizacoes["ficha"]["campos"] = array_merge($va_campos_visualizacao, $this->visualizacoes["ficha"]["campos"]);
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "livro_codigo",
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "livro_classificacao" => "Classificação",
            "livro_cutter_pha" => "Cutter/PHA",
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Título", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_subtitulo" => ["label" => "Subtítulo", "main_field" => true],
            "item_acervo_entidade_codigo" => "Autoria",
            "livro_editora_codigo" => "Editora",
            "item_acervo_data" => "Data de publicação",
            "item_acervo_idioma_codigo" => "Idioma",
            "livro_numero_paginas" => "Número de páginas",
            "item_acervo_dados_textuais_0_item_acervo_descricao" => "Descrição",
        ];

    }

}

?>