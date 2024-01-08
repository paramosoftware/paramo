<?php

class visualizacao extends objeto_base
{

    function __construct()
    {
        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
        return "visualizacao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['visualizacao_codigo'] = [
            'visualizacao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['visualizacao_recurso_sistema_codigo'] = [
            'visualizacao_recurso_sistema_codigo',
            'coluna_tabela' => 'recurso_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => "recurso_sistema"
        ];

        $va_atributos['visualizacao_nome'] = [
            'visualizacao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['visualizacao_habilitado'] = [
            'visualizacao_habilitado',
            'coluna_tabela' => 'habilitado',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['visualizacao_campo_sistema_codigo'] = [
            [
                'visualizacao_campo_sistema_codigo',
                'visualizacao_campo_sistema_sequencia'

            ],
            'tabela_intermediaria' => 'visualizacao_campo_sistema',
            'chave_exportada' => 'visualizacao_codigo',
            'campos_relacionamento' =>
                [
                    'visualizacao_campo_sistema_codigo' => 'campo_sistema_codigo',
                    'visualizacao_campo_sistema_sequencia' => [
                        'sequencia',
                        'valor_sequencial' => true
                    ],
                ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'campo_sistema',
            'objeto' => 'campo_sistema',
            'alias' => 'campos do sistema'
        ];

        $va_relacionamentos['visualizacao_contexto_visualizacao_codigo'] = [
            ['visualizacao_contexto_visualizacao_codigo'],
            'tabela_intermediaria' => 'visualizacao_contexto_visualizacao',
            'chave_exportada' => 'visualizacao_codigo',
            'campos_relacionamento' =>
                [
                    'visualizacao_contexto_visualizacao_codigo' => 'contexto_visualizacao_codigo'
                ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'contexto_visualizacao',
            'objeto' => 'contexto_visualizacao',
            'alias' => 'contextos de visualização'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["visualizacao_recurso_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "visualizacao_recurso_sistema_codigo",
            "label" => "Recurso do sistema",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => false,
            "conectar" => [
                [
                    "campo" => "visualizacao_campos_sistema",
                    "atributo" => "campo_sistema_recurso_sistema_codigo"
                ]
            ]
        ];

        $va_campos_edicao["visualizacao_nome"] = [
            "html_text_input",
            "nome" => "visualizacao_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["visualizacao_campo_sistema_codigo"] = [
            "html_multi_itens_input",
            "nome" => "visualizacao_campo_sistema_codigo",
            "label" => "Campos",
            "dependencia_linha" => ["campo" => "visualizacao_recurso_sistema_codigo"],
            "subcampos" => [
                "visualizacao_campo_sistema_codigo" =>
                    [
                        "html_combo_input",
                        "nome" => "visualizacao_campo_sistema_codigo",
                        "label" => "Campo",
                        "objeto" => "campo_sistema",
                        "atributos" => ["campo_sistema_codigo", "campo_sistema_alias"],
                        "atributo" => "campo_sistema_codigo",
                        "sem_valor" => false,
                        "dependencia" => [
                            "campo" => "visualizacao_recurso_sistema_codigo",
                            "atributo" => "campo_sistema_recurso_sistema_codigo"
                        ],
                        "campo_pai" => "visualizacao_campo_sistema_codigo"
                    ]

            ],
            "draggable" => true,
        ];

        $va_campos_edicao["visualizacao_contexto_visualizacao_codigo"] = [
            "html_combo_input",
            "nome" => "visualizacao_contexto_visualizacao_codigo",
            "label" => "Contexto",
            "objeto" => "contexto_visualizacao",
            "atributos" => ["contexto_visualizacao_codigo", "contexto_visualizacao_nome"],
            "formato" => "multi_selecao"
        ];

        $va_campos_edicao["visualizacao_habilitado"] = [
            "html_checkbox_input",
            "nome" => "visualizacao_habilitado",
            "label" => "Habilitado"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["visualizacao_nome"] = [
            "html_text_input",
            "nome" => "visualizacao_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["visualizacao_codigo"] = ["nome" => "visualizacao_codigo", "exibir" => false];
        $va_campos_visualizacao["visualizacao_nome"] = ["nome" => "visualizacao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["visualizacao_nome" => "Nome"];

        $va_campos_visualizacao["visualizacao_recurso_sistema_codigo"] = [
            "nome" => "visualizacao_recurso_sistema_codigo",
            "formato" => ["campo" => "recurso_sistema_nome_singular"]
        ];

        $va_campos_visualizacao["visualizacao_campo_sistema_codigo"] = [
            "nome" => "visualizacao_campo_sistema_codigo",
            "formato" => ["campo" => "campo_sistema_nome"]
        ];

        $va_campos_visualizacao["visualizacao_habilitado"] = [
            "nome" => "visualizacao_habilitado"
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["visualizacao_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "visualizacao_nome" => ["label" => "Nome", "main_field" => true],
            "visualizacao_recurso_sistema_codigo" => "Recurso"
        ];

        $va_campos_visualizacao["visualizacao_contexto_visualizacao_codigo"] = [
            "nome" => "visualizacao_contexto_visualizacao_codigo",
            "formato" => ["campo" => "contexto_visualizacao_nome"]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>