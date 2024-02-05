<?php

class objeto extends item_acervo
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
    }

    public function inicializar_tabela_banco()
    {
        return "objeto";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'objeto_codigo',
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

        $va_atributos['objeto_numero_registro'] = [
            'objeto_numero_registro',
            'coluna_tabela' => 'numero_registro',
            'tipo_dado' => 's'
        ];

        $va_atributos['objeto_tipo_objeto_codigo'] = [
            'objeto_tipo_objeto_codigo',
            'coluna_tabela' => 'tipo_objeto_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_objeto'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['objeto_material_codigo'] = [
            'objeto_material_codigo',
            'tabela_intermediaria' => 'objeto_material',
            'chave_exportada' => 'objeto_codigo',
            'campos_relacionamento' => [
                'objeto_material_codigo' => 'material_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'material',
            'objeto' => 'material',
            'alias' => 'materiais'
        ];

        $va_relacionamentos['objeto_tecnica_codigo'] = [
            'objeto_tecnica_codigo',
            'tabela_intermediaria' => 'objeto_tecnica',
            'chave_exportada' => 'objeto_codigo',
            'campos_relacionamento' => [
                'objeto_tecnica_codigo' => 'tecnica_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'tecnica',
            'objeto' => 'tecnica',
            'alias' => 'técnicas'
        ];

        return $va_relacionamentos;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return [
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "objeto",
            "atributos" => [
                $ps_campo_codigo == '' ? "objeto_codigo" : $ps_campo_codigo,
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
                    "obrigatoria" => false
                ]
            ],
            "sem_valor" => false,
        ];

        $va_campos_edicao["item_acervo_identificador"] = [
            "html_text_input",
            "nome" => "item_acervo_identificador",
            "label" => "Identificador",
            "automatico" => true
        ];

        $va_campos_edicao["objeto_numero_registro"] = [
            "html_text_input",
            "nome" => "objeto_numero_registro",
            "label" => "Número de tombo/registro",
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_titulo"] = [
            "html_text_input",
            "nome" => "item_acervo_dados_textuais_0_item_acervo_titulo",
            "label" => "Título atribuído",
            "obrigatorio" => true,
            "foco" => true
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
            "ano_maximo" => date("Y")
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
            "campo_salvar" => "localidade_nome"
        ];

        $va_campos_edicao["objeto_tipo_objeto_codigo"] = [
            "html_combo_input",
            "nome" => "objeto_tipo_objeto_codigo",
            "label" => "Tipo de objeto",
            "objeto" => "tipo_objeto",
            "atributo" => "tipo_objeto_codigo",
            "atributos" => ["tipo_objeto_codigo", "tipo_objeto_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["objeto_material_codigo"] = [
            "html_autocomplete",
            "nome" => ["objeto_material", "objeto_material_codigo"],
            "label" => "Material",
            "objeto" => "material",
            "atributos" => ["material_codigo", "material_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "material_nome",
            "campo_salvar" => "material_nome",
            "sugerir_valores" => true,
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "obrigatorio" => false
        ];

        $va_campos_edicao["objeto_tecnica_codigo"] = [
            "html_autocomplete",
            "nome" => ["objeto_tecnica", "objeto_tecnica_codigo"],
            "label" => "Técnica",
            "objeto" => "tecnica",
            "atributos" => ["tecnica_codigo", "tecnica_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "tecnica_nome",
            "campo_salvar" => "tecnica_nome",
            "sugerir_valores" => true,
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "obrigatorio" => false
        ];

        $va_campos_edicao["item_acervo_dimensoes"] = [
            "html_multi_itens_input",
            "nome" => "item_acervo_dimensoes",
            "label" => "Dimensões",
            "subcampos" => [
                "item_acervo_dimensoes_tipo" => [
                    "html_combo_input",
                    "nome" => "item_acervo_dimensoes_tipo",
                    "label" => "Tipo",
                    "objeto" => "tipo_dimensao",
                    "sem_valor" => false,
                    "formato" => "linha",
                    "conectar" => [
                        [
                            "campo" => "item_acervo_dimensoes_unidade_medida",
                            "atributo" => "item_acervo_dimensoes_tipo"
                        ]
                    ],
                    "campo_pai" => "item_acervo_dimensoes"
                ],
                "item_acervo_dimensoes_valor" =>
                    [
                        "html_text_input",
                        "nome" => "item_acervo_dimensoes_valor",
                        "label" => "Valor",
                        "formato" => "linha",
                        "campo_pai" => "item_acervo_dimensoes"
                    ],
                "item_acervo_dimensoes_unidade_medida" =>
                    [
                        "html_combo_input",
                        "nome" => "item_acervo_dimensoes_unidade_medida",
                        "label" => "Unidade de medida",
                        "objeto" => "unidade_medida",
                        "sem_valor" => false,
                        "formato" => "linha",
                        "dependencia" => [
                            "campo" => "item_acervo_dimensoes_tipo",
                            "atributo" => "unidade_medida_tipo_dimensao_codigo_0_tipo_dimensao_codigo"
                        ],
                        "campo_pai" => "item_acervo_dimensoes"
                    ]
            ]
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
            "campo_salvar" => "entidade_nome"
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

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_identificador"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_identificador",
            "label" => "Identificador",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo",
            "label" => "Título",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao"] = [
            "html_text_input",
            "nome" => "item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome"] = [
            "html_text_input",
            "exists_busca" => "item_acervo_codigo_0_item_acervo_entidade_codigo",
            "nome" => "item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome",
            "label" => "Autoria",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_codigo_0_item_acervo_data",
            "label" => "Data",
            "operador_filtro" => "=",
            "exibir_presumido" => false,
        ];

        $va_filtros_navegacao["objeto_tipo_objeto_codigo"] = [
            "html_combo_input",
            "nome" => "objeto_tipo_objeto_codigo",
            "label" => "Tipo de objeto",
            "objeto" => "tipo_objeto",
            "atributo" => "tipo_objeto_codigo",
            "atributos" => ["tipo_objeto_codigo", "tipo_objeto_nome"],
            "sem_valor" => true
        ];

        $va_filtros_navegacao["objeto_material_codigo_0_material_nome"] = [
            "html_text_input",
            "exists_busca" => "objeto_material_codigo",
            "nome" => "objeto_material_codigo_0_material_nome",
            "label" => "Material",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["objeto_tecnica_codigo_0_tecnica_nome"] = [
            "html_text_input",
            "exists_busca" => "objeto_tecnica_codigo",
            "nome" => "objeto_tecnica_codigo_0_tecnica_nome",
            "label" => "Técnica",
            "operador_filtro" => "LIKE"
        ];

        return array_merge($va_filtros_navegacao, $this->filtros_navegacao);
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["objeto_codigo"] = [
            "nome" => "objeto_codigo",
            "exibir" => false,
            "id_field" => true
        ];

        $va_campos_visualizacao["objeto_numero_registro"] = [
            "nome" => "objeto_numero_registro",
            "exibir" => false
        ];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["item_acervo_identificador" => "Identificador"];

        $va_campos_visualizacao["objeto_material_codigo"] = [
            "nome" => "objeto_material_codigo",
            "formato" => [
                "campo" => "material_nome",
            ],
            "label" => "Material"
        ];

        $va_campos_visualizacao["objeto_tecnica_codigo"] = [
            "nome" => "objeto_tecnica_codigo",
            "formato" => [
                "campo" => "tecnica_nome",
            ],
            "label" => "Técnica"
        ];


        $va_campos_visualizacao["objeto_tipo_objeto_codigo"] = [
            "nome" => "objeto_tipo_objeto_codigo",
            "formato" => [
                "campo" => "tipo_objeto_nome",
            ],
            "label" => "Tipo de objeto"
        ];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = ["item_acervo_identificador" => "Identificador", "item_acervo_codigo_0_item_acervo_data" => "Data"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Objeto", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "objeto_material_codigo" => ["label" => "Material"],
            "objeto_tecnica_codigo" => ["label" => "Técnica"],
            "objeto_tipo_objeto_codigo" => ["label" => "Tipo de objeto"],
            "item_acervo_data" => "Data",
            "item_acervo_entidade_codigo_com_tipo" => "Autoria",
        ];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Objeto", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "item_acervo_data" => "Data",
            "item_acervo_entidade_codigo_com_tipo" => "Autoria",
            "objeto_material_codigo" => ["label" => "Material"],
            "objeto_tecnica_codigo" => ["label" => "Técnica"],
            "objeto_tipo_objeto_codigo" => ["label" => "Tipo de objeto"],
            "item_acervo_localidade_codigo" => "Local"
        ];
    }

}

?>