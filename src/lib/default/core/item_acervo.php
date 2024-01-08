<?php

class item_acervo extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tem_representante_digital = false;
        $this->pode_ser_incluido_selecao = false;

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->registros_filhos["documento"] = [
            "atributo_relacionamento" => "item_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["entrevista"] = [
            "atributo_relacionamento" => "item_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["livro"] = [
            "atributo_relacionamento" => "item_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["objeto"] = [
            "atributo_relacionamento" => "item_acervo_codigo",
            "pode_excluir_pai" => true
        ];

    }

    public function inicializar_tabela_banco()
    {
        return "item_acervo";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'item_acervo_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function get_atributo_identificador()
    {
        return "item_acervo_identificador";
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['item_acervo_instituicao_codigo'] = [
            'item_acervo_instituicao_codigo',
            'coluna_tabela' => 'instituicao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'instituicao',
        ];

        $va_atributos['item_acervo_acervo_codigo'] = [
            'item_acervo_acervo_codigo',
            'coluna_tabela' => 'acervo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'acervo',
            'atributo' => 'acervo_codigo'
        ];

        $va_atributos['item_acervo_identificador'] = [
            'item_acervo_identificador',
            'coluna_tabela' => 'identificador',
            'tipo_dado' => 's',
            "valor_nao_repete" => "item_acervo_identificador",
            "serial" => [
                "documento" => [
                    [
                        "prefixo" => [
                            [
                                "tipo" => "atributo",
                                "objeto_prefixo" => "acervo",
                                "atributo_input_filtro" => "item_acervo_acervo_codigo",
                                "atributo_filtro_objeto_prefixo" => "acervo_codigo",
                                "atributo_prefixo" => "acervo_sigla"
                            ]
                        ],
                        "separador" => "-",
                        "agrupador_registros" =>
                            [
                                "atributo" => "item_acervo_acervo_codigo",
                                "filtro" => "item_acervo_acervo_codigo",
                                "ordenador" => "item_acervo_identificador"
                            ],
                        "tamanho" => 5
                    ]
                ],
                "livro" => [
                    [
                        "prefixo" => [
                            [
                                "tipo" => "constante",
                                "valor" => "LIV"
                            ]
                        ],
                        "separador" => "-",
                        "agrupador_registros" =>
                            [
                                "atributo" => "item_acervo_instituicao_codigo",
                                "filtro" => "item_acervo_instituicao_codigo",
                                "ordenador" => "item_acervo_identificador"
                            ],
                        "tamanho" => 5
                    ]
                ],
                "entrevista" => [
                    [
                        "prefixo" => [
                            [
                                "tipo" => "constante",
                                "valor" => "ENT"
                            ]
                        ],
                        "separador" => "-",
                        "agrupador_registros" =>
                            [
                                "atributo" => "item_acervo_instituicao_codigo",
                                "filtro" => "item_acervo_instituicao_codigo",
                                "ordenador" => "item_acervo_identificador"
                            ],
                        "tamanho" => 5
                    ]
                ],
                "objeto" => [
                    [
                        "prefixo" => [
                            [
                                "tipo" => "constante",
                                "valor" => "OBJ"
                            ]
                        ],
                        "separador" => "-",
                        "agrupador_registros" =>
                            [
                                "atributo" => "item_acervo_instituicao_codigo",
                                "filtro" => "item_acervo_instituicao_codigo",
                                "ordenador" => "item_acervo_identificador"
                            ],
                        "tamanho" => 5
                    ]
                ]
            ]
        ];

        $va_atributos['item_acervo_data'] = [
            'item_acervo_data',
            'coluna_tabela' => [
                'data_inicial' => 'data_inicial',
                'data_final' => 'data_final',
                'presumido' => 'data_presumida',
                'sem_data' => 'sem_data'
            ],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['item_acervo_genero_textual_codigo'] = [
            'item_acervo_genero_textual_codigo',
            'coluna_tabela' => 'genero_textual_codigo',
            'tipo_dado' => 'i',
            'objeto' => "genero_textual"
        ];

        $va_atributos['item_acervo_status_codigo'] = [
            'item_acervo_status_codigo',
            'coluna_tabela' => 'status_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'status_item_acervo'
        ];

        $va_atributos['item_acervo_publicado_online'] = [
            'item_acervo_publicado_online',
            'coluna_tabela' => 'publicado_online',
            'tipo_dado' => 'b'
        ];

        $va_atributos['item_acervo_unidade_armazenamento_codigo'] = [
            'item_acervo_unidade_armazenamento_codigo',
            'coluna_tabela' => 'unidade_armazenamento_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'unidade_armazenamento'
        ];

        $va_atributos['item_acervo_tipo_acesso_codigo'] = [
            'item_acervo_tipo_acesso_codigo',
            'coluna_tabela' => 'tipo_acesso_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_acesso'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['item_acervo_dados_textuais'] = [
            [
                'item_acervo_titulo',
                'item_acervo_subtitulo',
                'item_acervo_titulo_original',
                'item_acervo_titulo_transliterado',
                'item_acervo_descricao',
                'item_acervo_notas_conteudo',
                'item_acervo_observacoes',
            ],
            'tabela_intermediaria' => 'item_acervo_dados_textuais',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_titulo' => 'titulo',
                'item_acervo_subtitulo' => 'subtitulo',
                'item_acervo_titulo_original' => 'titulo_original',
                'item_acervo_titulo_transliterado' => 'titulo_transliterado',
                'item_acervo_descricao' => 'descricao',
                'item_acervo_notas_conteudo' => 'notas_conteudo',
                'item_acervo_observacoes' => 'observacoes',
            ],
            'tipos_campos_relacionamento' => ['s', 's', 's', 's', 's', 's', 's'],
            'tem_idioma' => true,
            'tipo' => 'textual'
        ];

        $va_relacionamentos['item_acervo_autoria_codigo'] = [
            [
                'item_acervo_autoria_codigo',
                'autor_tipo_autor_codigo',
                'item_acervo_entidade_funcao'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_autoria_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 1, 'objeto' => 'tipo_autor'],
                'item_acervo_entidade_funcao' => 'funcao_entidade'

            ],
            'tipos_campos_relacionamento' => ['i', 'i', 's'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => 1],
            'alias' => 'autorias'
        ];

        $va_relacionamentos['item_acervo_entidade_propriedade_codigo'] = [
            [
                'item_acervo_entidade_propriedade_codigo',
                'autor_tipo_autor_codigo',
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_propriedade_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 32, 'objeto' => 'tipo_autor'],
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => 32],
            "alias" => "propriedade"
        ];

        $va_relacionamentos['item_acervo_entidade_custodia_codigo'] = [
            [
                'item_acervo_entidade_custodia_codigo',
                'autor_tipo_autor_codigo',
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_custodia_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 33, 'objeto' => 'tipo_autor'],
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => 33],
            "alias" => "custódia"
        ];

        $va_relacionamentos['item_acervo_entidade_codigo'] = [
            [
                'item_acervo_entidade_codigo',
                'autor_tipo_autor_codigo',
                'item_acervo_entidade_funcao'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', [27, "<>"], 'objeto' => 'tipo_autor'],
                'item_acervo_entidade_funcao' => 'funcao_entidade'
            ],
            'tipos_campos_relacionamento' => ['i', 'i', 's'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'entidades'
        ];

        $va_relacionamentos['item_acervo_entidade_codigo_com_funcao'] = [
            [
                'item_acervo_entidade_codigo_com_funcao',
                'autor_tipo_autor_codigo',
                'item_acervo_entidade_funcao'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_codigo_com_funcao' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 'objeto' => 'tipo_autor'],
                'item_acervo_entidade_funcao' => 'funcao_entidade'
            ],
            'tipos_campos_relacionamento' => ['i', 'i', 's'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => [null, "<=>"]],
            'alias' => 'agentes'
        ];

        $va_relacionamentos['item_acervo_entidade_codigo_com_tipo'] = [
            [
                'item_acervo_entidade_codigo_com_tipo',
                'autor_tipo_autor_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_codigo_com_tipo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 'objeto' => 'tipo_autor']
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => [
                "autor_tipo_autor_codigo" => [null, "NOT"],
                "autor_tipo_autor_codigo" => [27, "<>"]
            ],
            'alias' => 'entidades'
        ];

        $va_relacionamentos['item_acervo_idioma_codigo'] = [
            [
                'item_acervo_idioma_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_idioma',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_idioma_codigo' => 'idioma_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'idioma',
            'objeto' => 'idioma'
        ];

        $va_relacionamentos['item_acervo_localidade_codigo'] = [
            ['item_acervo_localidade_codigo', 'item_acervo_localidade_presumida'],
            'tabela_intermediaria' => 'item_acervo_localidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_localidade_codigo' => 'localidade_codigo',
                'item_acervo_localidade_presumida' => 'localidade_presumida'
            ],
            'tipos_campos_relacionamento' => ['i', 'b'],
            'tabela_relacionamento' => 'localidade',
            'objeto' => 'localidade'
        ];

        $va_relacionamentos['item_acervo_palavra_chave_codigo'] = [
            [
                'item_acervo_palavra_chave_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_palavra_chave',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_palavra_chave_codigo' => 'palavra_chave_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'palavra_chave',
            'objeto' => 'palavra_chave'
        ];

        $va_relacionamentos['item_acervo_item_acervo_codigo'] = [
            'item_acervo_item_acervo_codigo',
            'tabela_intermediaria' => 'item_acervo_item_acervo',
            'chave_exportada' => ['item_acervo_1_codigo', 'item_acervo_2_codigo'],
            'campos_relacionamento' => [
                'item_acervo_item_acervo_codigo' => [
                    [
                        'item_acervo_2_codigo',
                        'item_acervo_1_codigo'
                    ],
                    "atributo" => "item_acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens do acervo relacionados'
        ];

        $va_relacionamentos['item_acervo_contexto_codigo'] = [
            [
                'item_acervo_contexto_codigo',
                'item_acervo_contexto_sequencia'
            ],
            'tabela_intermediaria' => 'contexto_item_acervo',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' =>
                [
                    'item_acervo_contexto_codigo' => 'contexto_codigo',
                    'item_acervo_contexto_sequencia' => 'sequencia',
                ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'objeto' => 'contexto',
            'alias' => 'contextos'
        ];

        $va_relacionamentos['item_acervo_incorporacao_codigo'] = [
            [
                'item_acervo_incorporacao_codigo'
            ],
            'tabela_intermediaria' => 'incorporacao_item_acervo',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_incorporacao_codigo' => 'incorporacao_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'incorporacao',
            'objeto' => 'incorporacao',
            'alias' => 'incorporações'
        ];

        $va_relacionamentos['item_acervo_assunto_codigo'] = [
            [
                'item_acervo_assunto_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_assunto',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_assunto_codigo' => 'assunto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'assunto',
            'objeto' => 'assunto'
        ];

        $va_relacionamentos['item_acervo_entidade_assunto_codigo'] = [
            [
                'item_acervo_entidade_assunto_codigo',
                'autor_tipo_autor_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_assunto_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 27, 'objeto' => 'tipo_autor']
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => 27],
            "alias" => "autoridades"
        ];

        $va_relacionamentos['item_acervo_entidade_entrevistado_codigo'] = [
            [
                'item_acervo_entidade_entrevistado_codigo',
                'autor_tipo_autor_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_entrevistado_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 5, 'objeto' => 'tipo_autor']
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => 5],
            'alias' => 'entrevistado'
        ];

        $va_relacionamentos['item_acervo_entidade_entrevistador_codigo'] = [
            [
                'item_acervo_entidade_entrevistador_codigo',
                'autor_tipo_autor_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entidade_entrevistador_codigo' => 'entidade_codigo',
                'autor_tipo_autor_codigo' => ['tipo_autor_codigo', 11, 'objeto' => 'tipo_autor']
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            "filtros" => ["autor_tipo_autor_codigo" => 11],
            'alias' => 'entrevistador'
        ];

        $va_relacionamentos['item_acervo_estado_conservacao'] = [
            [
                'item_acervo_estado_conservacao_codigo',
                'item_acervo_estado_conservacao_descricao'
            ],
            'tabela_intermediaria' => 'item_acervo_estado_conservacao',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_estado_conservacao_codigo' => ['estado_conservacao_codigo', 'objeto' => 'estado_conservacao'],
                'item_acervo_estado_conservacao_descricao' => 'descricao'
            ],
            'tipos_campos_relacionamento' => ['i', 's']
        ];

        $va_relacionamentos['item_acervo_suporte_codigo'] = [
            'item_acervo_suporte_codigo',
            'tabela_intermediaria' => 'item_acervo_suporte',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => ['item_acervo_suporte_codigo' => 'suporte_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'suporte',
            'objeto' => 'suporte',
            'alias' => 'suportes'
        ];

        $va_relacionamentos['item_acervo_dimensoes'] = [
            [
                'item_acervo_dimensoes_tipo',
                'item_acervo_dimensoes_valor',
                'item_acervo_dimensoes_unidade_medida'
            ],
            'tabela_intermediaria' => 'item_acervo_dimensao',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_dimensoes_tipo' => 'tipo_dimensao_codigo',
                'item_acervo_dimensoes_valor' => 'valor',
                'item_acervo_dimensoes_unidade_medida' => 'unidade_medida_codigo'
            ],
            'tipos_campos_relacionamento' => ['i', 's', 'i']
        ];

        $va_relacionamentos['item_acervo_documento_codigo'] = [
            ['item_acervo_documento_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_documento_codigo' => [
                    ['codigo'],
                    "atributo" => "documento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'tipo' => '1n',
            'alias' => "documento"
        ];

        $va_relacionamentos['item_acervo_entrevista_codigo'] = [
            ['item_acervo_entrevista_codigo'],
            'tabela_intermediaria' => 'entrevista',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_entrevista_codigo' => [
                    ['codigo'],
                    "atributo" => "entrevista_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entrevista',
            'objeto' => 'entrevista',
            'tipo' => '1n',
            'alias' => "entrevista"
        ];

        $va_relacionamentos['item_acervo_livro_codigo'] = [
            ['item_acervo_livro_codigo'],
            'tabela_intermediaria' => 'livro',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_livro_codigo' => [
                    ['codigo'],
                    "atributo" => "livro_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'livro',
            'objeto' => 'livro',
            'tipo' => '1n',
            'alias' => "livro"
        ];

        $va_relacionamentos['item_acervo_objeto_codigo'] = [
            ['item_acervo_objeto_codigo'],
            'tabela_intermediaria' => 'objeto',
            'chave_exportada' => 'item_acervo_codigo',
            'campos_relacionamento' => [
                'item_acervo_objeto_codigo' => [
                    ['codigo'],
                    "atributo" => "objeto_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'objeto',
            'objeto' => 'objeto',
            'tipo' => '1n',
            'alias' => "objeto"
        ];

        return $va_relacionamentos;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return array(
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "item_acervo",
            "atributos" => [
                $ps_campo_codigo == '' ? "item_acervo_codigo" : $ps_campo_codigo,
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
                    "campo" => "item_acervo_identificador",
                    "atributo" => "item_acervo_identificador,item_acervo_dados_textuais_0_item_acervo_titulo"
                ],
                [
                    "campo" => "item_acervo_instituicao_codigo",
                    "atributo" => "item_acervo_instituicao_codigo"
                ]
            ]
        );
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["item_acervo_codigo"] = [
            "nome" => "item_acervo_codigo",
            "exibir" => false
        ];

        $va_campos_visualizacao["item_acervo_identificador"] = [
            "nome" => "item_acervo_identificador",
            "label" => "Identificador",
            "label_field" => true
        ];

        $va_campos_visualizacao["item_acervo_instituicao_codigo"] = [
            "nome" => "item_acervo_instituicao_codigo",
            "formato" => ["campo" => "instituicao_nome"],
            "label" => "Acervo"
        ];

        $va_campos_visualizacao["item_acervo_acervo_codigo"] = [
            "nome" => "item_acervo_acervo_codigo",
            "formato" => ["campo" => "acervo_nome"],
            "label" => "Acervo"
        ];

        $va_campos_visualizacao["item_acervo_dados_textuais"] = [
            "nome" => "item_acervo_dados_textuais",
        ];

        $va_campos_visualizacao["item_acervo_autor_titulo"] = ["nome" => "item_acervo_autor_titulo"];

        $va_campos_visualizacao["item_acervo_data"] = ["nome" => "item_acervo_data", "formato" => ["data" => "completo"]];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;

        $va_campos_visualizacao["item_acervo_idioma_codigo"] = [
            "nome" => "item_acervo_idioma_codigo",
            "formato" => ["campo" => "idioma_nome"],
            "label" => "Idioma"
        ];

        $va_campos_visualizacao["item_acervo_assunto_codigo"] = [
            "nome" => "item_acervo_assunto_codigo",
            "formato" => ["campo" => "assunto_nome"],
            "label" => "Assunto"
        ];

        $va_campos_visualizacao["item_acervo_genero_textual_codigo"] = [
            "nome" => "item_acervo_genero_textual_codigo",
            "formato" => ["campo" => "genero_textual_nome"]
        ];

        $va_campos_visualizacao["item_acervo_autoria_codigo"] = [
            "nome" => "item_acervo_autoria_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_entidade_assunto_codigo"] = [
            "nome" => "item_acervo_entidade_assunto_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_entidade_entrevistado_codigo"] = [
            "nome" => "item_acervo_entidade_entrevistado_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_entidade_entrevistador_codigo"] = [
            "nome" => "item_acervo_entidade_entrevistador_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_entidade_codigo"] = [
            "nome" => "item_acervo_entidade_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome",
                    " (" => [
                        " (",
                        "constante" => true,
                        "condicao" => ["item_acervo_entidade_funcao", "<>vazio"]
                    ],
                    "item_acervo_entidade_funcao",
                    ")" => [
                        ")",
                        "constante" => true,
                        "condicao" => ["item_acervo_entidade_funcao", "<>vazio"]
                    ]
                ],
                //"link" => [
                //    "objeto" => "entidade",
                //    "codigo" => "item_acervo_entidade_codigo"
                //]
            ],
        ];

        $va_campos_visualizacao["item_acervo_entidade_codigo_com_funcao"] = [
            "nome" => "item_acervo_entidade_codigo_com_funcao",
            "formato" => [
                "expressao" => [
                    "entidade_nome",
                    " (" => [
                        " (",
                        "constante" => true,
                        "condicao" => ["item_acervo_entidade_funcao", "<>vazio"]
                    ],
                    "tipo_autor_nome",
                    "item_acervo_entidade_funcao",
                    ")" => [
                        ")",
                        "constante" => true,
                        "condicao" => ["item_acervo_entidade_funcao", "<>vazio"]
                    ]
                ],
                //"link" => [
                //    "objeto" => "entidade",
                //    "codigo" => "item_acervo_entidade_codigo"
                //]
            ],
        ];

        $va_campos_visualizacao["item_acervo_entidade_codigo_com_tipo"] = [
            "nome" => "item_acervo_entidade_codigo_com_tipo",
            "formato" => [
                "expressao" => [
                    "entidade_nome",
                    " (" => [
                        " (",
                        "constante" => true,
                        "condicao" => ["tipo_autor_nome", "<>vazio"]
                    ],
                    "tipo_autor_nome",
                    ")" => [
                        ")",
                        "constante" => true,
                        "condicao" => ["tipo_autor_nome", "<>vazio"]
                    ]
                ],
                "numero_maximo_itens" => 3,
                "termo_complementar" => " et al."
                //"link" => [
                //    "objeto" => "entidade",
                //    "codigo" => "item_acervo_entidade_codigo"
                //]
            ],
        ];

        $va_campos_visualizacao["item_acervo_localidade_codigo"] = [
            "nome" => "item_acervo_localidade_codigo",
            "formato" => [
                "expressao" => [
                    "[" => ["[", "condicao" => ["item_acervo_localidade_presumida", "1"]],
                    "localidade_nome",
                    "]" => ["]", "condicao" => ["item_acervo_localidade_presumida", "1"]]
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_status_codigo"] = [
            "nome" => "item_acervo_status_codigo",
            "formato" => ["campo" => "status_item_acervo_nome"]
        ];

        $va_campos_visualizacao["item_acervo_status_registro_codigo"] = [
            "nome" => "item_acervo_status_registro_codigo",
            "formato" => ["campo" => "status_registro_nome"]
        ];

        $va_campos_visualizacao["item_acervo_bibliografia_codigo"] = [
            "nome" => "item_acervo_bibliografia_codigo",
            "formato" => ["campo" => "bibliografia_nome"]
        ];

        //$va_campos_visualizacao["representante_digital_codigo"] = ["nome" => "representante_digital_codigo"];

        $this->visualizacoes["navegacao"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));
        //$this->visualizacoes["navegacao"]["order_by"] = ["item_acervo_titulo" => "Título"];

        $va_campos_visualizacao["item_acervo_palavra_chave_codigo"] = [
            "nome" => "item_acervo_palavra_chave_codigo",
            "formato" => ["campo" => "palavra_chave_nome"]
        ];

        $va_campos_visualizacao["item_acervo_nome_relacionado_codigo"] = [
            "nome" => "item_acervo_nome_relacionado_codigo",
            "formato" => ["campo" => "entidade_nome"]
        ];

        $va_campos_visualizacao["item_acervo_item_acervo_relacionado_codigo"] = [
            "nome" => "item_acervo_item_acervo_relacionado_codigo",
            "formato" => ["campo" => "item_acervo_autor_titulo"]
        ];

        $va_campos_visualizacao["item_acervo_tema_codigo"] = [
            "nome" => "item_acervo_tema_codigo",
            "formato" => ["campo" => "tema_nome"]
        ];

        $va_campos_visualizacao["item_acervo_suporte_codigo"] = [
            "nome" => "item_acervo_suporte_codigo",
            "formato" => ["campo" => "suporte_nome"]
        ];

        $va_campos_visualizacao["item_acervo_dimensoes"] = [
            "nome" => "item_acervo_dimensoes",
            "formato" => ["expressao" => ["item_acervo_dimensoes_valor"]]
        ];

        $va_campos_visualizacao["item_acervo_item_acervo_codigo"] = [
            "nome" => "item_acervo_item_acervo_codigo",
            "formato" => [
                "campo" => "item_acervo_identificador",
            ],
            "label" => "Itens do acervo relacionados"
        ];

        $va_campos_visualizacao["item_acervo_contexto_codigo"] = [
            "nome" => "item_acervo_contexto_codigo",
            "formato" => [
                "campo" => "contexto_dados_textuais_0_contexto_nome",
                "hierarquia" => "contexto_contexto_superior_codigo",
                "separador" => " > ",
            ],
            "label" => "Contexto"
        ];

        $va_campos_visualizacao["item_acervo_incorporacao_codigo"] = [
            "nome" => "item_acervo_incorporacao_codigo",
            "formato" => [
                "expressao" => [
                    "incorporacao_tipo_codigo_0_tipo_incorporacao_nome",
                    ",1" => [", ",
                        "constante" => true],
                    "incorporacao_entidade_codigo_0_entidade_nome",
                    ",2" => [", ",
                        "constante" => true],
                    "incorporacao_data_data_inicial" => ["incorporacao_data_data_inicial", "_data_"],
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_estado_conservacao"] = [
            "nome" => "item_acervo_estado_conservacao",
            "label" => "Estado de conservação"
        ];

        $va_campos_visualizacao["item_acervo_unidade_armazenamento_codigo"] = [
            "nome" => "item_acervo_unidade_armazenamento_codigo",
            "formato" => [
                "campo" => "unidade_armazenamento_nome",
                "hierarquia" => "unidade_armazenamento_unidade_armazenamento_superior_codigo",
                "separador" => " > ",
            ]
        ];

        $va_campos_visualizacao["item_acervo_tipo_acesso_codigo"] = [
            "nome" => "item_acervo_tipo_acesso_codigo",
            "formato" => [
                "campo" => "tipo_acesso_nome",
            ],
            "label" => "Acesso"
        ];

        $va_campos_visualizacao["item_acervo_entidade_propriedade_codigo"] = [
            "nome" => "item_acervo_entidade_propriedade_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_entidade_custodia_codigo"] = [
            "nome" => "item_acervo_entidade_custodia_codigo",
            "formato" => [
                "expressao" => [
                    "entidade_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["item_acervo_publicado_online"] = ["nome" => "item_acervo_publicado_online"];

        $this->visualizacoes["ficha"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));
    }

}

?>