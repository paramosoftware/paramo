<?php

class documento extends item_acervo
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->objeto_pai = "item_acervo";
        $this->campo_relacionamento_pai = "item_acervo_codigo";
        $this->excluir_objeto_pai = true;

        $this->pode_ser_incluido_selecao = true;

        $this->inicializar_visualizacoes();

        $this->controlador_acesso = [
            "instituicao_codigo" => "item_acervo_codigo_0_item_acervo_instituicao_codigo",
            "acervo_codigo" => "item_acervo_codigo_0_item_acervo_acervo_codigo"
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "documento";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['documento_codigo'] = [
            'documento_codigo',
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

        $va_atributos['documento_agrupamento_codigo'] = [
            'documento_agrupamento_codigo',
            'coluna_tabela' => 'agrupamento_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'agrupamento'
        ];

        $va_atributos['documento_serie_codigo'] = [
            'documento_serie_codigo',
            'coluna_tabela' => 'serie_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'serie'
        ];

        $va_atributos['documento_documento_pai_codigo'] = [
            'documento_documento_pai_codigo',
            'coluna_tabela' => 'documento_pai_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'documento'
        ];

        $va_atributos['documento_local_armazenamento_codigo'] = [
            'documento_local_armazenamento_codigo',
            'coluna_tabela' => 'local_armazenamento_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'local_armazenamento'
        ];

        $va_atributos['documento_atividade_geradora_codigo'] = [
            'documento_atividade_geradora_codigo',
            'coluna_tabela' => 'atividade_geradora_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'atividade_geradora'
        ];

        $va_atributos['documento_genero_documental_codigo'] = [
            'documento_genero_documental_codigo',
            'coluna_tabela' => 'genero_documental_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'genero_documental'
        ];

        $va_atributos['documento_tecnica_registro_codigo'] = [
            'documento_tecnica_registro_codigo',
            'coluna_tabela' => 'tecnica_registro_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tecnica_registro'
        ];

        $va_atributos['documento_cromia_codigo'] = [
            'documento_cromia_codigo',
            'coluna_tabela' => 'cromia_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'cromia'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['documento_especie_documental_codigo'] = [
            [
                'documento_especie_documental_codigo',
                'documento_tipo_documental_codigo'
            ],
            'tabela_intermediaria' => 'documento_especie_documental',
            'chave_exportada' => 'documento_codigo',
            'campos_relacionamento' => [
                'documento_especie_documental_codigo' => 'especie_documental_codigo',
                'documento_tipo_documental_codigo' => ['tipo_documental_codigo', 'objeto' => 'tipo_documental'],
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'especie_documental',
            'objeto' => 'especie_documental',
            'alias' => 'espécies documentais'
        ];

        $va_relacionamentos['documento_formato_codigo'] = [
            'documento_formato_codigo',
            'tabela_intermediaria' => 'documento_formato',
            'chave_exportada' => 'documento_codigo',
            'campos_relacionamento' => [
                'documento_formato_codigo' => 'formato_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'formato',
            'objeto' => 'formato',
            'alias' => 'formatos'
        ];

        $va_relacionamentos['documento_documento_filho_codigo'] = [
            ['documento_documento_filho_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'documento_pai_codigo',
            'campos_relacionamento' => [
                'documento_documento_filho_codigo' => [
                    ['codigo'],
                    "atributo" => "documento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'tipo' => '1n',
            'alias' => 'documentos inclusos'
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
        return [
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "documento",
            "atributos" => [
                $ps_campo_codigo == '' ? "documento_codigo" : $ps_campo_codigo,
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
        ];
    }


    public function inicializar_campos_edicao()
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
                    "valor" => 1,
                ]
            ]
        ];

        $va_campos_edicao["item_acervo_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_acervo_codigo",
            "label" => "Acervo",
            "objeto" => "conjunto_documental",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => false,
            "atributo_obrigatorio" => true,
            "conectar" => [
                [
                    "campo" => "documento_agrupamento_codigo",
                    "atributo" => "agrupamento_acervo_codigo"
                ],
                [
                    "campo" => "item_acervo_unidade_armazenamento_codigo",
                    "atributo" => "item_acervo_acervo_codigo"
                ]
            ],
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

        $va_campos_edicao["documento_agrupamento_codigo"] = [
            "html_autocomplete",
            "nome" => ["documento_agrupamento", "documento_agrupamento_codigo"],
            "label" => "Grupo e subgrupo",
            "objeto" => "agrupamento",
            "atributos" =>
                [
                    "agrupamento_codigo",
                    "agrupamento_dados_textuais_0_agrupamento_nome" => ["hierarquia" => "agrupamento_agrupamento_superior_codigo"]
                ],
            "multiplos_valores" => false,
            "procurar_por" => "agrupamento_dados_textuais_0_agrupamento_nome",
            "dependencia" => [
                [
                    "campo" => "item_acervo_acervo_codigo",
                    "atributo" => "agrupamento_acervo_codigo"
                ]
            ],
            "visualizacao" => "lista",
        ];

        $va_campos_edicao["documento_agrupamento_codigo"] = [
            "html_autocomplete",
            "nome" => ["documento_agrupamento", "documento_agrupamento_codigo"],
            "label" => "Grupo e subgrupo",
            "objeto" => "agrupamento",
            "atributos" =>
                [
                    "agrupamento_codigo",
                    "agrupamento_dados_textuais_0_agrupamento_nome" => ["hierarquia" => "agrupamento_agrupamento_superior_codigo"]
                ],
            "multiplos_valores" => false,
            "procurar_por" => "agrupamento_dados_textuais_0_agrupamento_nome",
            "dependencia" => [
                [
                    "campo" => "item_acervo_acervo_codigo",
                    "atributo" => "agrupamento_acervo_codigo"
                ]
            ],
            "visualizacao" => "lista"
        ];


        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_titulo"] = [
            "html_text_input",
            "nome" => 'item_acervo_dados_textuais_0_item_acervo_titulo',
            "label" => "Documento",
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_descricao"] = [
            "html_text_input",
            "nome" => "item_acervo_dados_textuais_0_item_acervo_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8,
        ];

        $va_campos_edicao["item_acervo_autoria_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_autoria", "item_acervo_autoria_codigo"],
            "label" => "Autoria",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_data",
            "label" => "Data",
            "ano_maximo" => date("Y"),
            "permitir_escolha_formato" => true
        ];

        $va_campos_edicao["item_acervo_localidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_localidade", "item_acervo_localidade_codigo"],
            "label" => "Local",
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

        $va_campos_edicao["documento_genero_documental_codigo"] = [
            "html_combo_input",
            "nome" => "documento_genero_documental_codigo",
            "label" => "Gênero documental",
            "objeto" => "genero_documental",
            "atributo" => "genero_documental_codigo",
            "atributos" => ["genero_documental_codigo", "genero_documental_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["item_acervo_suporte_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_suporte", "item_acervo_suporte_codigo"],
            "label" => "Suporte",
            "objeto" => "suporte",
            "atributos" => ["suporte_codigo", "suporte_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "suporte_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "suporte_nome"
        ];

        $va_campos_edicao["documento_especie_documental_codigo"] = [
            "html_autocomplete", 
            "nome" => ["documento_especie_documental", "documento_especie_documental_codigo"],
            "label" => "Espécie documental", 
            "objeto" => "especie_documental",
            "atributos" => ["especie_documental_codigo", "especie_documental_dados_textuais_0_especie_documental_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "especie_documental_dados_textuais_0_especie_documental_nome", 
            "visualizacao" => "lista",
            "permitir_cadastro" => true, 
            "campo_salvar" => "especie_documental_dados_textuais_0_especie_documental_nome",
            "subcampos" => [
                "documento_tipo_documental_codigo" => 
                [
                    "html_autocomplete", 
                    "nome" => ["documento_tipo_documental", "documento_tipo_documental_codigo"],
                    "label" => "Tipo documental", 
                    "objeto" => "tipo_documental",
                    "atributos" => ["tipo_documental_codigo", "tipo_documental_nome"],
                    "procurar_por" => "tipo_documental_nome",
                    "multiplos_valores" => false,
                    "permitir_cadastro" => true, 
                    "campo_salvar" => "tipo_documental_nome",
                    "dependencia" => [
                            "campo" => "documento_especie_documental_codigo",
                            "atributo" => "especie_documental_codigo"
                    ],
                    "campo_pai" => "documento_especie_documental_codigo"
                ]
            ]
        ];

        $va_campos_edicao["documento_cromia_codigo"] = [
            "html_combo_input",
            "nome" => "documento_cromia_codigo",
            "label" => "Cromia",
            "objeto" => "cromia",
            "atributo" => "cromia_codigo",
            "atributos" => ["cromia_codigo", "cromia_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["item_acervo_entidade_codigo_com_funcao"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_entidade_com_funcao", "item_acervo_entidade_codigo_com_funcao"],
            "label" => "Agentes",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome",
            "subcampos" =>
                [
                    "item_acervo_entidade_funcao" =>
                        [
                            "html_text_input",
                            "nome" => "item_acervo_entidade_funcao",
                            "label" => "Função",
                            "campo_pai" => "item_acervo_entidade_codigo_com_funcao"
                        ]
                ]
        ];

        $va_campos_edicao["item_acervo_contexto_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_contexto", "item_acervo_contexto_codigo"],
            "label" => "Contexto",
            "objeto" => "contexto",
            "atributos" =>
                [
                    "contexto_codigo",
                    "contexto_dados_textuais_0_contexto_nome" => ["hierarquia" => "contexto_contexto_superior_codigo"]
                ],
            "multiplos_valores" => true,
            "procurar_por" => "contexto_dados_textuais_0_contexto_nome",
            "visualizacao" => "lista",
        ];

        $va_campos_edicao["documento_formato_codigo"] = [
            "html_autocomplete",
            "nome" => ["documento_formato", "documento_formato_codigo"],
            "label" => "Formato",
            "objeto" => "formato",
            "atributos" => ["formato_codigo", "formato_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "formato_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "formato_nome"
        ];
        
        $va_campos_edicao["item_acervo_estado_conservacao"] = [
            "html_multi_itens_input",
            "nome" => "item_acervo_estado_conservacao",
            "label" => "Estado de conservação",
            "numero_maximo_itens" => 1,
            "numero_itens_inicial" => 0,
            "subcampos" => [
                "item_acervo_estado_conservacao_codigo" => [
                    "html_combo_input",
                    "nome" => "item_acervo_estado_conservacao_codigo",
                    "label" => "Estado de conservação",
                    "formato" => "linha",
                    "objeto" => "estado_conservacao",
                    "atributo" => "estado_conservacao_codigo",
                    "atributos" => ["estado_conservacao_codigo", "estado_conservacao_nome"],
                    "sem_valor" => false,
                    "campo_pai" => "item_acervo_estado_conservacao"
                ],
                "item_acervo_estado_conservacao_descricao" => [
                    "html_text_input",
                    "nome" => "item_acervo_estado_conservacao_descricao",
                    "label" => "Detalhamento",
                    "formato" => "linha",
                    "numero_linhas" => 3,
                    "campo_pai" => "item_acervo_estado_conservacao"
                ]
            ]
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

        $va_campos_edicao["item_acervo_item_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_item_acervo", "item_acervo_item_acervo_codigo"],
            "label" => "Relacionamento com outros itens do acervo",
            "objeto" => "item_acervo",
            "atributos" => ["item_acervo_codigo", ["item_acervo_identificador", "item_acervo_dados_textuais_0_item_acervo_titulo"]],
            "multiplos_valores" => true,
            "procurar_por" => "item_acervo_identificador",
            "visualizacao" => "lista"
        ];

        $va_campos_edicao["item_acervo_assunto_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_assunto", "item_acervo_assunto_codigo"],
            "label" => "Relacionamento com assuntos",
            "objeto" => "assunto",
            "atributos" => ["assunto_codigo", "assunto_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "assunto_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "assunto_nome"
        ];

        $va_campos_edicao["item_acervo_entidade_assunto_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_entidade_assunto", "item_acervo_entidade_assunto_codigo"],
            "label" => "Relacionamento com autoridades",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
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
                    "obrigatoria" => true
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
            "objeto" => "conjunto_documental",
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

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo",
            "label" => "Documento",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["documento_especie_documental_codigo"] = [
            "html_combo_input",
            "nome" => "documento_especie_documental_codigo",
            "label" => "Espécie documental",
            "objeto" => "especie_documental",
            "atributo" => "especie_documental_codigo",
            "atributos" => ["especie_documental_codigo", "especie_documental_dados_textuais_0_especie_documental_nome"],
            "sem_valor" => true,
            "operador_filtro" => "=",
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome"] = [
            "html_text_input",
            "exists_busca" => "item_acervo_codigo_0_item_acervo_entidade_codigo",
            "nome" => "item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome",
            "label" => "Autoria ou agente",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_codigo_0_item_acervo_data",
            "label" => "Data",
            "operador_filtro" => "=",
            "formato" => 6,
            "exibir_presumido" => false
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_idioma_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_codigo_0_item_acervo_idioma_codigo",
            "label" => "Idioma",
            "objeto" => "idioma",
            "atributos" => ["idioma_codigo", "idioma_nome"],
            "atributo" => "idioma_codigo",
            "sem_valor" => true,
            "operador_filtro" => "=",
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_localidade_codigo_0_localidade_nome"] = [
            "html_text_input",
            "exists_busca" => "item_acervo_codigo_0_item_acervo_localidade_codigo",
            "nome" => "item_acervo_codigo_0_item_acervo_localidade_codigo_0_localidade_nome",
            "label" => "Local",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["documento_agrupamento_codigo"] = [
            "html_combo_input",
            "nome" => "documento_agrupamento_codigo",
            "label" => "Grupo e subgrupo",
            "objeto" => "agrupamento",
            "atributos" => [
                    "agrupamento_codigo",
                    "agrupamento_dados_textuais_0_agrupamento_nome" => ["hierarquia" => "agrupamento_agrupamento_superior_codigo"]
            ],
            "atributo" => "agrupamento_codigo",
            "sem_valor" => true,
            "operador_filtro" => "=",
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["item_acervo_unidade_armazenamento_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_unidade_armazenamento_codigo",
            "label" => "Unidade de armazenamento",
            "objeto" => "unidade_armazenamento",
            "atributos" => [
                "unidade_armazenamento_codigo",
                "unidade_armazenamento_nome" => [
                    "hierarquia" => "unidade_armazenamento_unidade_armazenamento_superior_codigo"
                ]
            ],
            "atributo" => "unidade_armazenamento_codigo",
            "sem_valor" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                "tipo" => "interface",
                "campo" => "item_acervo_acervo_codigo",
                "atributo" => "unidade_armazenamento_acervo_codigo",
                "obrigatoria" => true
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["representante_digital_codigo"] = [
            "html_combo_input",
            "nome" => "representante_digital_codigo",
            "label" => "Tem representante digital",
            "valores" => [
                "1" => "sim",
                "0" => "não"
            ],
            "sem_valor" => true,
            "operador_filtro" => "_EXISTS_",
            "css-class" => "form-select"
        ];


        return array_merge($va_filtros_navegacao, $this->filtros_navegacao);
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["documento_codigo"] = [
            "nome" => "documento_codigo",
            "exibir" => false,
            "id_field" => true
        ];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["item_acervo_codigo_0_item_acervo_identificador" => "Identificador"];

        $va_campos_visualizacao["documento_agrupamento_codigo"] = [
            "nome" => "documento_agrupamento_codigo",
            "formato" => [
                "campo" => "agrupamento_dados_textuais_0_agrupamento_nome",
                "hierarquia" => "agrupamento_agrupamento_superior_codigo",
                "separador" => " > "
            ],
            "label" => "Grupo/Subgrupo"
        ];

        $va_campos_visualizacao["documento_especie_documental_codigo"] = [
            "nome" => "documento_especie_documental_codigo",
            "formato" => [
                "expressao" => [
                    "especie_documental_dados_textuais_0_especie_documental_nome",
                    " > " => [
                        " > ",
                        "constante" => true,
                        "condicao" => ["tipo_documental_nome", "<>vazio"]
                    ],
                    "tipo_documental_nome"
                ]
            ],
            "label" => "Espécie/Tipo documental"
        ];

        $va_campos_visualizacao["documento_data"] = [
            "nome" => "documento_data",
            "formato" => ["data" => "completo"],
            "label" => "Data"
        ];

        $va_campos_visualizacao["documento_serie_codigo"] = [
            "nome" => "documento_serie_codigo",
            "formato" => [
                "campo" => "serie_nome",
            ],
            "label" => "Série"
        ];

        $va_campos_visualizacao["documento_formato_codigo"] = [
            "nome" => "documento_formato_codigo",
            "formato" => ["campo" => "formato_nome"],
            "label" => "Formato"
        ];

        $va_campos_visualizacao["item_acervo_unidade_armazenamento_codigo"] = [
            "nome" => "item_acervo_unidade_armazenamento_codigo",
            "formato" => [
                "campo" => "unidade_armazenamento_nome",
                "hierarquia" => "unidade_armazenamento_unidade_armazenamento_superior_codigo",
                "separador" => " > ",
            ]
        ];

        $va_campos_visualizacao["etapa_fluxo_codigo"] = [
            "nome" => "etapa_fluxo_codigo",
            "formato" => ["campo" => "etapa_fluxo_nome"]
        ];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = [
            "item_acervo_codigo_0_item_acervo_identificador" => "Identificador",
            "item_acervo_codigo_0_item_acervo_data" => "Data"
        ];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "item_acervo_instituicao_codigo" => "Instituição",
            "item_acervo_acervo_codigo" => "Fundo/Coleção",
            "documento_especie_documental_codigo" => "Espécie/Tipo documental",
            "documento_genero_documental_codigo" => "Gênero documental",
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Título", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "documento_agrupamento_codigo" => "Grupo/subgrupo",
            "item_acervo_data" => "Data",
            "item_acervo_autoria_codigo" => "Autoria",
            "item_acervo_entidade_codigo_com_funcao" => "Agentes"
        ];

        $va_campos_visualizacao["documento_atividade_geradora_codigo"] = [
            "nome" => "documento_atividade_geradora_codigo",
            "formato" => ["campo" => "atividade_geradora_nome"]
        ];

        $va_campos_visualizacao["documento_genero_documental_codigo"] = [
            "nome" => "documento_genero_documental_codigo",
            "formato" => ["campo" => "genero_documental_nome"]
        ];

        $va_campos_visualizacao["documento_tecnica_registro_codigo"] = [
            "nome" => "documento_tecnica_registro_codigo",
            "formato" => ["campo" => "tecnica_registro_nome"]
        ];

        $va_campos_visualizacao["documento_cromia_codigo"] = [
            "nome" => "documento_cromia_codigo",
            "formato" => ["campo" => "cromia_nome"]
        ];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "documento_especie_documental_codigo" => "Espécie/Tipo documental",
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Documento", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "documento_agrupamento_codigo" => "Grupo/subgrupo",
            "item_acervo_data" => "Data",
            "item_acervo_autoria_codigo" => "Autoria",
            "item_acervo_entidade_codigo_com_funcao" => "Agentes",
            "item_acervo_localidade_codigo" => "Local",
            "item_acervo_idioma_codigo" => "Idioma",
            "documento_atividade_geradora_codigo" => "Atividade geradora",
            "item_acervo_suporte_codigo" => "Suporte",
            "item_acervo_dados_textuais_0_item_acervo_observacoes" => "Notas"
        ];
    }


}

?>