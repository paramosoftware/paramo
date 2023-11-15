<?php

class agrupamento extends objeto_base
{
    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->hierarquico = true;
        $this->campo_hierarquico = "agrupamento_agrupamento_superior_codigo";

        $this->registros_filhos["agrupamento"] = [
            "atributo_relacionamento" => "agrupamento_agrupamento_superior_codigo",
            "pode_excluir_pai" => true,
            "exibir_ficha_pai" => true
        ];

        $this->controlador_acesso = [
            "acervo_codigo" => "agrupamento_acervo_codigo"
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "agrupamento";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'agrupamento_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['agrupamento_id'] = [
            'agrupamento_id',
            'coluna_tabela' => 'id',
            'tipo_dado' => 's',
            'processar' => [
                'slugfy',
                ['agrupamento_dados_textuais_0_agrupamento_nome']
            ]
        ];

        $va_atributos['agrupamento_identificador'] = [
            'agrupamento_identificador',
            'coluna_tabela' => 'identificador',
            'tipo_dado' => 's'
        ];

        $va_atributos['agrupamento_acervo_codigo'] = [
            'agrupamento_acervo_codigo',
            'coluna_tabela' => 'acervo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'acervo'
        ];

        $va_atributos['agrupamento_codigo_referencia'] = [
            'agrupamento_codigo_referencia',
            'coluna_tabela' => 'codigo_referencia',
            'tipo_dado' => 's'
        ];

        $va_atributos['agrupamento_data'] = [
            'agrupamento_data',
            'coluna_tabela' => [
                'data_inicial' => 'data_inicial',
                'data_final' => 'data_final'
            ],
            'tipo_dado' => 'dt'
        ];

    $va_atributos['agrupamento_agrupamento_superior_codigo'] = [
        'agrupamento_agrupamento_superior_codigo',
        'coluna_tabela' => 'agrupamento_superior_codigo',
        'tipo_dado' => 'i',
        'objeto' => 'agrupamento'
    ];

    return $va_atributos;
}

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['agrupamento_dados_textuais'] = [
            [
                'agrupamento_nome',
                'agrupamento_descricao'
            ],
            'tabela_intermediaria' => 'agrupamento_dados_textuais',
            'chave_exportada' => 'agrupamento_codigo',
            'campos_relacionamento' => [
                'agrupamento_nome' => 'nome',
                'agrupamento_descricao' => 'descricao',
            ],
            'tipos_campos_relacionamento' => ['s', 's'],
            'tem_idioma' => true,
            'tipo' => 'textual',
            'alias' => 'descritores textuais'
        ];

        $va_relacionamentos['agrupamento_entidade_codigo'] = [
            [
                'agrupamento_entidade_codigo'
            ],
            'tabela_intermediaria' => 'agrupamento_entidade',
            'chave_exportada' => 'agrupamento_codigo',
            'campos_relacionamento' => [
                'agrupamento_entidade_codigo' => 'entidade_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'autoridades'
        ];

        $va_relacionamentos['agrupamento_assunto_codigo'] = [
            [
                'agrupamento_assunto_codigo'
            ],
            'tabela_intermediaria' => 'agrupamento_assunto',
            'chave_exportada' => 'agrupamento_codigo',
            'campos_relacionamento' => [
                'agrupamento_assunto_codigo' => 'assunto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'assunto',
            'objeto' => 'assunto',
            'alias' => 'assuntos'
        ];


        $va_relacionamentos['agrupamento_agrupamento_inferior_codigo'] = [
            ['agrupamento_agrupamento_inferior_codigo'],
            'tabela_intermediaria' => 'agrupamento',
            'chave_exportada' => 'agrupamento_superior_codigo',
            'campos_relacionamento' => [
                'agrupamento_agrupamento_inferior_codigo' => [
                    ['codigo'],
                    "atributo" => "agrupamento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'agrupamento',
            'objeto' => 'agrupamento',
            'tipo' => '1n',
            'alias' => 'agrupamentos inferiores'
        ];

        $va_relacionamentos['agrupamento_documento_codigo'] = [
            'agrupamento_documento_codigo',
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'agrupamento_codigo',
            'campos_relacionamento' => [
                'agrupamento_documento_codigo' => [
                    ['codigo'],
                    "atributo" => "documento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'tipo' => '1n',
            'alias' => 'documentos'
        ];

        $va_relacionamentos['agrupamento_serie_codigo'] = [
            'agrupamento_serie_codigo',
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'agrupamento_codigo',
            'campos_relacionamento' => [
                'agrupamento_serie_codigo' => [
                    ['codigo'],
                    "atributo" => "serie_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'serie',
            'objeto' => 'serie',
            'tipo' => '1n',
            'alias' => 'séries'
        ];

        return $va_relacionamentos;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo = '')
    {
        return [
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Agrupamento",
            "objeto" => "agrupamento",
            "atributos" =>
                [
                    $ps_campo_codigo == '' ? "agrupamento_codigo" : $ps_campo_codigo,
                    "agrupamento_dados_textuais_0_agrupamento_nome" => [
                        "hierarquia" => "agrupamento_agrupamento_superior_codigo"
                    ]
                ],
            "dependencia" => [
                [
                    "campo" => "agrupamento_dados_textuais_0_agrupamento_nome",
                    "atributo" => "agrupamento_dados_textuais_0_agrupamento_nome"
                ]
            ]
        ];
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '')
    {
        $va_campos_edicao = array();

        $va_campos_edicao["agrupamento_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "agrupamento_instituicao_codigo",
            "label" =>"Entidade custodiadora",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "atributo_obrigatorio" => true,
            "dependencia" => [
                [
                    "campo" => "instituicao_codigo",
                    "atributo" => "instituicao_codigo",
                    "obrigatoria" => false
                ]
            ],
            "conectar" => [
                [
                    "campo" => "agrupamento_acervo_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo"
                ]
            ],
            "sem_valor" => false,
            "filtro" => [
                [
                    "atributo" => "instituicao_acervo_codigo",
                    "valor" => 1,
                    "operador" => "_EXISTS_"
                ]
            ]
        ];

        $va_campos_edicao["agrupamento_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "agrupamento_acervo_codigo",
            "label" => "Acervo",
            "objeto" => "conjunto_documental",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => false,
            "atributo_obrigatorio" => true,
            "dependencia" => [
                [
                    "campo" => "agrupamento_instituicao_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo",
                    "obrigatoria" => true
                ]
            ]
        ];

        $va_campos_edicao["agrupamento_dados_textuais_0_agrupamento_nome"] = [
            "html_text_input",
            "nome" => "agrupamento_dados_textuais_0_agrupamento_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["agrupamento_dados_textuais_0_agrupamento_descricao"] = [
            "html_text_input",
            "nome" => "agrupamento_dados_textuais_0_agrupamento_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["agrupamento_agrupamento_superior_codigo"] = [
            "html_combo_input",
            "nome" => "agrupamento_agrupamento_superior_codigo",
            "label" => "Agrupamento superior",
            "objeto" => "agrupamento",
            "atributos" => [
                "agrupamento_codigo",
                "agrupamento_dados_textuais_0_agrupamento_nome" => ["hierarquia" => "agrupamento_agrupamento_superior_codigo"]
            ],
            "atributo" => "agrupamento_codigo",
            "sem_valor" => true,
            "dependencia" => ["campo" => "agrupamento_acervo_codigo", "atributo" => "agrupamento_acervo_codigo"],
            "prevenir_circularidade" => $pn_objeto_codigo
        ];


        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["agrupamento_acervo_codigo_0_acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "agrupamento_acervo_codigo_0_acervo_instituicao_codigo",
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
                    "campo" => "agrupamento_acervo_codigo",
                    "atributo" => "acervo_codigo_0_acervo_instituicao_codigo"
                ]
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["agrupamento_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "agrupamento_acervo_codigo",
            "label" => "Fundo/coleção",
            "objeto" => "conjunto_documental",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => true,
            "atributo_obrigatorio" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                [
                    "tipo" => "interface",
                    "campo" => "agrupamento_acervo_codigo_0_acervo_instituicao_codigo",
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

        $va_filtros_navegacao["agrupamento_dados_textuais_0_agrupamento_nome"] = [
            "html_text_input",
            "nome" => "agrupamento_dados_textuais_0_agrupamento_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["agrupamento_codigo"] = [
            "nome" => "agrupamento_codigo",
            "exibir" => false
        ];

        $va_campos_visualizacao["agrupamento_id"] = [
            "nome" => "agrupamento_id",
            "exibir" => false
        ];

        $va_campos_visualizacao["agrupamento_identificador"] = [
            "nome" => "agrupamento_identificador"
        ];

        $va_campos_visualizacao["agrupamento_acervo_codigo"] = [
            "nome" => "agrupamento_acervo_codigo",
            "formato" => ["campo" => "acervo_nome"]
        ];

        $va_campos_visualizacao["agrupamento_codigo_referencia"] = [
            "nome" => "agrupamento_codigo_referencia"
        ];

        $va_campos_visualizacao["agrupamento_data"] = [
            "nome" => "agrupamento_data"
        ];

        $va_campos_visualizacao["agrupamento_agrupamento_superior_codigo"] = [
            "nome" => "agrupamento_agrupamento_superior_codigo",
            "formato" => [
                "campo" => "agrupamento_dados_textuais_0_agrupamento_nome",
                "hierarquia" => "agrupamento_agrupamento_superior_codigo",
                "separador" => " > "
            ],
            "label" => "Agrupamento superior"
        ];

        $va_campos_visualizacao["agrupamento_dados_textuais"] = [
            "nome" => "agrupamento_dados_textuais",
            "formato" => [
                "campo" => "agrupamento_nome",
                "hierarquia" => $this->campo_hierarquico
            ]
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["agrupamento_dados_textuais_0_agrupamento_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["agrupamento_dados_textuais_0_agrupamento_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "agrupamento_dados_textuais_0_agrupamento_nome" => ["label" => "Nome", "main_field" => true],
            "agrupamento_acervo_codigo" => "Fundo/Coleção",
            "agrupamento_agrupamento_superior_codigo" => "Grupo"
        ];

        $va_campos_visualizacao["agrupamento_agrupamento_inferior_codigo"] = [
            "nome" => "agrupamento_agrupamento_inferior_codigo",
            "formato" => [
                "campo" => "agrupamento_dados_textuais_0_agrupamento_nome"
            ]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "agrupamento_dados_textuais_0_agrupamento_nome" => ["label" => "Nome", "main_field" => true],
            "agrupamento_agrupamento_superior_codigo" => "Grupo",
            "agrupamento_agrupamento_inferior_codigo" => "Subgrupos"
        ];
    }

}

?>