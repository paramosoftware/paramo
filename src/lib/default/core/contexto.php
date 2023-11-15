<?php

class contexto extends objeto_base
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
        $this->campo_hierarquico = "contexto_contexto_superior_codigo";

        $this->registros_filhos["contexto"] = [
            "atributo_relacionamento" => "contexto_contexto_superior_codigo",
            "pode_excluir_pai" => true
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "contexto";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['contexto_codigo'] = [
            'contexto_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['contexto_id'] = [
            'contexto_id',
            'coluna_tabela' => 'id',
            'tipo_dado' => 's',
            'processar' => [
                'slugfy',
                ['contexto_dados_textuais_0_contexto_nome']
            ]
        ];

        $va_atributos['contexto_acervo_codigo'] = [
            'contexto_acervo_codigo',
            'coluna_tabela' => 'Acervo_Codigo',
            'tipo_dado' => 'i',
            'objeto' => 'acervo'
        ];

        $va_atributos['contexto_data'] = [
            'contexto_data',
            'coluna_tabela' => ['data_inicial' => 'data_inicial', 'data_final' => 'data_final', 'presumido' => 'data_presumida', 'sem_data' => 'sem_data'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['contexto_contexto_superior_codigo'] = [
            'contexto_contexto_superior_codigo',
            'coluna_tabela' => 'contexto_superior_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'contexto'
        ];

        $va_atributos['contexto_publicado_online'] = [
            'contexto_publicado_online',
            'coluna_tabela' => 'publicado_online',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['contexto_dados_textuais'] = [
            [
                'contexto_nome',
                'contexto_sinopse',
                'contexto_descricao'
            ],
            'tabela_intermediaria' => 'contexto_dados_textuais',
            'chave_exportada' => 'contexto_codigo',
            'campos_relacionamento' => [
                'contexto_nome' => 'nome',
                'contexto_sinopse' => 'sinopse',
                'contexto_descricao' => 'descricao',
            ],
            'tipos_campos_relacionamento' => ['s', 's', 's'],
            'tem_idioma' => true,
            'tipo' => 'textual'
        ];

        $va_relacionamentos['contexto_tipo_contexto_codigo'] = [
            [
                'contexto_tipo_contexto_codigo'
            ],
            'tabela_intermediaria' => 'contexto_tipo_contexto',
            'chave_exportada' => 'contexto_codigo',
            'campos_relacionamento' => [
                'contexto_tipo_contexto_codigo' => 'tipo_contexto_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'tipo_contexto',
            'objeto' => 'tipo_contexto',
            'alias' => 'tipos de contextos'
        ];

        $va_relacionamentos['contexto_item_acervo_codigo'] = [
            [
                'contexto_item_acervo_codigo',
                'contexto_item_acervo_sequencia'
            ],
            'tabela_intermediaria' => 'contexto_item_acervo',
            'chave_exportada' => 'contexto_codigo',
            'campos_relacionamento' => [
                'contexto_item_acervo_codigo' => 'item_acervo_codigo',
                'contexto_item_acervo_sequencia' => ['sequencia', "valor_sequencial" => true],
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens de acervo'
        ];

        $va_relacionamentos['contexto_contexto_inferior_codigo'] = [
            ['contexto_contexto_inferior_codigo'],
            'tabela_intermediaria' => 'contexto',
            'chave_exportada' => 'contexto_superior_codigo',
            'campos_relacionamento' => [
                'contexto_contexto_inferior_codigo' => [
                    ['codigo'],
                    "atributo" => "contexto_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'contexto',
            'objeto' => 'contexto',
            'tipo' => '1n',
            'alias' => 'contextos inferiores'
        ];

        $va_relacionamentos['contexto_acervo_relacionado_codigo'] = [
            [
                'contexto_acervo_relacionado_codigo'
            ],
            'tabela_intermediaria' => 'acervo_contexto',
            'chave_exportada' => 'contexto_codigo',
            'campos_relacionamento' => [
                'contexto_acervo_relacionado_codigo' => 'acervo_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'alias' => 'acervos relacionados'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo='')
    {
        $va_campos_edicao = array();

        $va_campos_edicao["contexto_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "contexto_acervo_codigo",
            "label" =>"Acervo",
            "objeto" => "acervo",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => false,
            "dependencia" => [
                "campo" => "acervo_codigo",
                "atributo" => "acervo_codigo",
                "obrigatoria" => true
            ]
        ];

        $va_campos_edicao["contexto_dados_textuais_0_contexto_nome"] = [
            "html_text_input",
            "nome" => "contexto_dados_textuais_0_contexto_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["contexto_tipo_contexto_codigo"] = [
            "html_combo_input",
            "nome" => "contexto_tipo_contexto_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_contexto",
            "atributos" => ["tipo_contexto_codigo", "tipo_contexto_nome"],
            "formato" => "multi_selecao",
            "controlar_exibicao" => ["contexto_dados_textuais_0_contexto_sinopse"]
        ];

        $va_campos_edicao["contexto_data"] = [
            "html_date_input",
            "nome" => "contexto_data",
            "label" => "Período"
        ];

        $va_campos_edicao["contexto_dados_textuais_0_contexto_sinopse"] = [
            "html_text_input",
            "nome" => "contexto_dados_textuais_0_contexto_sinopse",
            "label" => "Sinopse",
            "foco" => true,
            "numero_linhas" => 4,
            "regra_exibicao" => ["contexto_tipo_contexto_codigo" => 1]
        ];

        $va_campos_edicao["contexto_dados_textuais_0_contexto_descricao"] = [
            "html_text_input",
            "nome" => "contexto_dados_textuais_0_contexto_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8,
        ];

        $va_campos_edicao["contexto_contexto_superior_codigo"] = [
            "html_combo_input",
            "nome" => "contexto_contexto_superior_codigo",
            "label" => "Contexto de nível superior",
            "objeto" => "contexto",
            "atributos" => ["contexto_codigo", "contexto_dados_textuais_0_contexto_nome"],
            "atributo" => "contexto_codigo",
            "sem_valor" => true,
            "dependencia" => [
                "campo" => "contexto_acervo_codigo",
                "atributo" => "contexto_acervo_codigo"
            ],
            "filtro" => [
                [
                    "valor" => $pn_objeto_codigo,
                    "atributo" => "contexto_codigo",
                    "operador" => "!="
                ]
            ]
        ];

        $va_campos_edicao["contexto_item_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["contexto_item_acervo", "contexto_item_acervo_codigo"],
            "label" => "Itens do acervo selecionados",
            "objeto" => "item_acervo",
            "atributos" => [
                "item_acervo_codigo",
                "item_acervo_identificador" => ["item_acervo_identificador", "item_acervo_dados_textuais_0_item_acervo_titulo"]
            ],
            "multiplos_valores" => true,
            "procurar_por" => "item_acervo_identificador",
            "visualizacao" => "lista",
            "draggable" => true
        ];

        $va_campos_edicao["contexto_publicado_online"] = [
            "html_checkbox_input",
            "nome" => "contexto_publicado_online",
            "label" => "Publicar online"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["contexto_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "contexto_acervo_codigo",
            "label" => "Acervo",
            "objeto" => "acervo",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                [
                    "tipo" => "interface",
                    "campo" => "acervo_codigo",
                    "atributo" => "acervo_codigo",
                    "obrigatoria" => true
                ]
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["contexto_dados_textuais_0_contexto_nome"] = [
            "html_text_input",
            "nome" => "contexto_dados_textuais_0_contexto_nome",
            "label" => "Nome",
            "foco" => true,
            "operador_filtro" => "LIKE",
        ];

        $va_filtros_navegacao["contexto_tipo_contexto_codigo"] = [
            "html_combo_input",
            "nome" => "contexto_tipo_contexto_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_contexto",
            "atributos" => ["tipo_contexto_codigo", "tipo_contexto_nome"]
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["contexto_codigo"] = [
            "nome" => "contexto_codigo",
            "exibir" => false
        ];

        $va_campos_visualizacao["contexto_id"] = [
            "nome" => "contexto_id"
        ];

        $va_campos_visualizacao["contexto_acervo_codigo"] = [
            "nome" => "contexto_acervo_codigo",
            "formato" => ["campo" => "entidade_nome"]
        ];

        $va_campos_visualizacao["contexto_data"] = [
            "nome" => "contexto_data",
            "formato" => ["data" => "completo"]
        ];

        $va_campos_visualizacao["contexto_contexto_superior_codigo"] = [
            "nome" => "contexto_contexto_superior_codigo",
            "formato" => [
                "campo" => "contexto_dados_textuais_0_contexto_nome",
                "hierarquia" => "contexto_contexto_superior_codigo",
                "separador" => " > "
                //"link" => [
                //    "objeto" => "contexto",
                //    "codigo" => "contexto_codigo",
                //    "destino" => "navegar.php"
                //]
            ],
            "label" => "Contexto de nível superior"
        ];

        $va_campos_visualizacao["contexto_dados_textuais"] = [
            "nome" => "contexto_dados_textuais",
            "formato" => [
                "campo" => "contexto_nome",
                "hierarquia" => $this->campo_hierarquico
            ]
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["contexto_dados_textuais_0_contexto_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["contexto_dados_textuais_0_contexto_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "contexto_dados_textuais_0_contexto_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["contexto_tipo_contexto_codigo"] = [
            "nome" => "contexto_tipo_contexto_codigo",
            "formato" => ["campo" => "tipo_contexto_nome"]
        ];

        $va_campos_visualizacao["contexto_item_acervo_codigo"] = [
            "nome" => "contexto_item_acervo_codigo",
            "formato" => ["campo" => "item_acervo_identificador"]
        ];

        $va_campos_visualizacao["contexto_publicado_online"] = ["nome" => "contexto_publicado_online"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "contexto_dados_textuais_0_contexto_nome" => ["label" => "Nome", "main_field" => true],
            "contexto_item_acervo_codigo" => "Itens do acervo selecionados"
        ];
    }

}

?>