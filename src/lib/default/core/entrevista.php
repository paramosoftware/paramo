<?php

class entrevista extends item_acervo
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

        $this->controlador_acesso = ["instituicao_codigo" => "item_acervo_codigo_0_item_acervo_instituicao_codigo"];
    }

    public function inicializar_tabela_banco()
    {
        return "entrevista";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['entrevista_codigo'] = [
            'entrevista_codigo',
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

        $va_atributos['entrevista_circulo'] = [
            'entrevista_circulo',
            'coluna_tabela' => 'circulo',
            'tipo_dado' => 's'
        ];

        $va_atributos['entrevista_tipo_entrevista_codigo'] = [
            'entrevista_tipo_entrevista_codigo',
            'coluna_tabela' => 'tipo_entrevista_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_entrevista'
        ];

        $va_atributos['entrevista_duracao'] = [
            'entrevista_duracao',
            'coluna_tabela' => 'duracao',
            'tipo_dado' => 'i'
        ];

        $va_atributos['entrevista_transcrito'] = [
            'transcrito',
            'coluna_tabela' => 'transcrito',
            'tipo_dado' => 'b'
        ];

        $va_atributos['entrevista_projeto_codigo'] = [
            'projeto_codigo',
            'coluna_tabela' => 'projeto_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'projeto'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['entrevista_formato_entrevista_codigo'] = [
            [
                'entrevista_formato_entrevista_codigo'
            ],
            'tabela_intermediaria' => 'entrevista_formato_entrevista',
            'chave_exportada' => 'entrevista_codigo',
            'campos_relacionamento' => [
                'entrevista_formato_entrevista_codigo' => 'formato_entrevista_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'formato_entrevista',
            'objeto' => 'formato_entrevista',
            'alias' => 'formatos de entrevista'
        ];

        return $va_relacionamentos;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return [
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "entrevista",
            "atributos" => [
                $ps_campo_codigo == '' ? "entrevista_codigo" : $ps_campo_codigo,
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
                ]
            ]
        ];

        $va_campos_edicao["item_acervo_identificador"] = [
            "html_text_input",
            "nome" => "item_acervo_identificador",
            "label" => "Identificador",
            "automatico" => true,
            "foco" => true,
            "readonly" => true
        ];

        $va_campos_edicao["item_acervo_codigo"] = [
            "html_text_input",
            "nome" => 'item_acervo_codigo',
            "label" => "Título",
            "nao_exibir" => true
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_titulo"] = [
            "html_text_input",
            "nome" => "item_acervo_dados_textuais_0_item_acervo_titulo",
            "label" => "Título"
        ];

        $va_campos_edicao["item_acervo_dados_textuais_0_item_acervo_descricao"] = [
            "html_text_input",
            "nome" => "item_acervo_dados_textuais_0_item_acervo_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["item_acervo_entidade_entrevistado_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_entidade_entrevistado", "item_acervo_entidade_entrevistado_codigo"],
            "label" => "Entrevistado",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome",
            "obrigatorio" => true
        ];

        $va_campos_edicao["item_acervo_entidade_entrevistador_codigo"] = [
            "html_autocomplete",
            "nome" => ["item_acervo_entidade_entrevistador", "item_acervo_entidade_entrevistador_codigo"],
            "label" => "Responsável pela entrevista",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["entrevista_projeto_codigo"] = [
            "html_combo_input",
            "nome" => "entrevista_projeto_codigo",
            "label" => "Projeto",
            "objeto" => "projeto",
            "atributo" => "projeto_codigo",
            "atributos" => ["projeto_codigo", "projeto_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["entrevista_circulo"] = [
            "html_text_input",
            "nome" => "entrevista_circulo",
            "label" => "Evento"
        ];

        $va_campos_edicao["item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_data",
            "label" => "Data",
            "ano_maximo" => date("Y"),
            "formato" => 1
        ];

        $va_campos_edicao["entrevista_formato_entrevista_codigo"] = [
            "html_combo_input",
            "nome" => "entrevista_formato_entrevista_codigo",
            "label" => "Formato da entrevista",
            "objeto" => "formato_entrevista",
            "atributos" => ["formato_entrevista_codigo", "formato_entrevista_nome"],
            // formato multi_selecao não pode ter o campo "atritubo"
            "formato" => "multi_selecao"
        ];

        $va_campos_edicao["entrevista_tipo_entrevista_codigo"] = [
            "html_combo_input",
            "nome" => "entrevista_tipo_entrevista_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_entrevista",
            "atributo" => "tipo_entrevista_codigo",
            "atributos" => ["tipo_entrevista_codigo", "tipo_entrevista_nome"],
        ];

        $va_campos_edicao["entrevista_duracao"] = [
            "html_number_input",
            "nome" => "entrevista_duracao",
            "label" => "Duração (minutos)"
        ];

        $va_campos_edicao["entrevista_transcrito"] = [
            "html_checkbox_input",
            "nome" => "entrevista_transcrito",
            "label" => "Transcrita"
        ];

        $va_campos_edicao["item_acervo_tipo_acesso_codigo"] = [
            "html_combo_input",
            "nome" => "item_acervo_tipo_acesso_codigo",
            "label" => "Acesso",
            "objeto" => "tipo_acesso",
            "atributos" => ["tipo_acesso_codigo", "tipo_acesso_nome"],
            "atributo" => "tipo_acesso_codigo",
            "sem_valor" => false,
            "formato" => "radio"
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
            "permitir_cadastro" => true,
            "campo_salvar" => "contexto_dados_textuais_0_contexto_nome"
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
                    "obrigatoria" => false
                ]
            ],
            "css-class" => "form-select"
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

        $va_filtros_navegacao["entrevista_projeto_codigo"] = [
            "html_combo_input",
            "nome" => "entrevista_projeto_codigo",
            "label" => "Projeto",
            "objeto" => "projeto",
            "atributos" => ["projeto_codigo", "projeto_nome"],
            "atributo" => "projeto_codigo",
            "sem_valor" => true,
            "operador_filtro" => "="
        ];

        $va_filtros_navegacao["entrevista_circulo"] = [
            "html_text_input",
            "nome" => "entrevista_circulo",
            "label" => "Evento",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["item_acervo_codigo_0_item_acervo_data"] = [
            "html_date_input",
            "nome" => "item_acervo_codigo_0_item_acervo_data",
            "label" => "Data",
            "operador_filtro" => "=",
            "formato" => 1,
            "exibir_presumido" => false
        ];

        return array_merge($va_filtros_navegacao, $this->filtros_navegacao);
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["entrevista_codigo"] = [
            "nome" => "entrevista_codigo",
            "exibir" => false,
            "id_field" => true
        ];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["item_acervo_identificador" => "Identificador"];

        $va_campos_visualizacao["entrevista_duracao"] = [
            "nome" => "entrevista_duracao",
            "label" => "Duração"
        ];

        $va_campos_visualizacao["entrevista_circulo"] = [
            "nome" => "entrevista_circulo",
            "label" => "Evento"
        ];

        $va_campos_visualizacao["entrevista_projeto_codigo"] = [
            "nome" => "entrevista_projeto_codigo",
            "label" => "Projeto",
            "formato" => [
                "expressao" => [
                    "projeto_nome"
                ]
            ]
        ];

        $va_campos_visualizacao["entrevista_transcrito"] = [
            "nome" => "entrevista_transcrito",
            "formato" => ["booleano" => true]
        ];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = [
            "item_acervo_identificador" => "Identificador",
            "item_acervo_codigo_0_item_acervo_data" => "Data"
        ];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Entrevistado", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "item_acervo_entidade_entrevistado_codigo" => "Entrevistado",
            "item_acervo_entidade_entrevistador_codigo" => "Responsável pela entrevista",
            "entrevista_projeto_codigo" => "Projeto",
            "entrevista_circulo" => "Evento",
            "item_acervo_data" => "Data",
            "entrevista_transcrito" => "Transcrito"
        ];

        $va_campos_visualizacao["entrevista_tipo_entrevista_codigo"] = [
            "nome" => "entrevista_tipo_entrevista_codigo",
            "label" => "Tipo",
            "formato" => [
                "expressao" => [
                    "tipo_entrevista_nome"
                ]
            ]
        ];


        $va_campos_visualizacao["entrevista_formato_entrevista_codigo"] = [
            "nome" => "entrevista_formato_entrevista_codigo",
            "label" => "Formato da entrevista",
            "formato" => [
                "expressao" => [
                    "formato_entrevista_nome"
                ]
            ]
        ];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_titulo" => ["label" => "Entrevistado", "main_field" => true],
            "item_acervo_dados_textuais_0_item_acervo_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "item_acervo_entidade_entrevistado_codigo" => "Entrevistado",
            "item_acervo_entidade_entrevistador_codigo" => "Responsável pela entrevista",
            "entrevista_projeto_codigo" => "Projeto",
            "entrevista_circulo" => "Evento",
            "item_acervo_data" => "Data",
            "entrevista_tipo_entrevista_codigo" => "Tipo",
            "entrevista_formato_entrevista_codigo" => "Formato da entrevista"
        ];

    }
}

?>