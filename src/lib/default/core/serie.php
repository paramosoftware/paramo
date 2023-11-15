<?php

class serie extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
        return "serie";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['serie_codigo'] = [
            'serie_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['serie_identificador'] = [
            'serie_identificador',
            'coluna_tabela' => 'identificador',
            'tipo_dado' => 's'
        ];

        $va_atributos['serie_acervo_codigo'] = [
            'serie_acervo_codigo',
            'coluna_tabela' => 'acervo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'conjunto_documental'
        ];

        $va_atributos['serie_codigo_referencia'] = [
            'serie_codigo_referencia',
            'coluna_tabela' => 'codigo_referencia',
            'tipo_dado' => 's'
        ];

        $va_atributos['serie_nome'] = [
            'serie_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['serie_agrupamento_codigo'] = [
            'serie_agrupamento_codigo',
            'coluna_tabela' => 'agrupamento_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'agrupamento'
        ];

        $va_atributos['serie_especie_documental_codigo'] = [
            'serie_especie_documental_codigo',
            'coluna_tabela' => 'especie_documental_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'especie_documental'
        ];

        $va_atributos['serie_tipo_documental_codigo'] = [
            'serie_tipo_documental_codigo',
            'coluna_tabela' => 'tipo_documental_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_documental'
        ];

        $va_atributos['serie_atividade_geradora_codigo'] = [
            'serie_atividade_geradora_codigo',
            'coluna_tabela' => 'atividade_geradora_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'atividade_geradora'
        ];

        $va_atributos['serie_data'] = [
            'serie_data',
            'coluna_tabela' => ['data_inicial' => 'data_inicial', 'data_final' => 'data_final'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['serie_serie_superior_codigo'] = [
            'serie_serie_superior_codigo',
            'coluna_tabela' => 'serie_superior_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'serie'
        ];

        $va_atributos['serie_quantidade_itens'] = [
            'serie_quantidade_itens',
            'coluna_tabela' => 'quantidade_itens',
            'tipo_dado' => 'i'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['serie_assunto_codigo'] = [
            [
                'serie_assunto_codigo'
            ],
            'tabela_intermediaria' => 'serie_assunto',
            'chave_exportada' => 'serie_codigo',
            'campos_relacionamento' => [
                'serie_assunto_codigo' => 'assunto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'assunto',
            'objeto' => 'assunto',
            'alias' => 'assuntos relacionados'
        ];

        $va_relacionamentos['serie_entidade_codigo'] = [
            [
                'serie_entidade_codigo'
            ],
            'tabela_intermediaria' => 'serie_entidade',
            'chave_exportada' => 'serie_codigo',
            'campos_relacionamento' => [
                'serie_entidade_codigo' => 'entidade_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'autoridades relacionadas'
        ];

        $va_relacionamentos['serie_serie_inferior_codigo'] = [
            ['serie_serie_inferior_codigo'],
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'serie_serie_superior_codigo',
            'campos_relacionamento' => [
                'serie_serie_inferior_codigo' => [
                    ['codigo'],
                    "atributo" => "serie_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'serie',
            'objeto' => 'serie',
            'tipo' => '1n',
            'alias' => 'séries inferiores'
        ];

        $va_relacionamentos['serie_documento_codigo'] = [
            ['serie_documento_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'serie_codigo',
            'campos_relacionamento' => [
                'serie_documento_codigo' => [
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

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '')
    {
        $va_campos_edicao = array();

        $va_campos_edicao["serie_identificador"] = [
            "html_text_input",
            "nome" => "serie_identificador",
            "label" => "ID",
            "foco" => true
        ];

        $va_campos_edicao["serie_codigo_referencia"] = [
            "html_text_input",
            "nome" => "serie_codigo_referencia",
            "label" => "Código de referência"
        ];

        $va_campos_edicao["serie_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "serie_acervo_codigo",
            "label" => "Arquivo",
            "objeto" => "conjunto_documental",
            "atributo" => "acervo_codigo",
            "atributos" => ["acervo_codigo", "entidade_nome"],
            "sem_valor" => false,
            "dependencia" => [
                [
                    "campo" => "acervo_codigo",
                    "atributo" => "acervo_codigo",
                    "obrigatorio" => true
                ]
            ]
        ];

        $va_campos_edicao["serie_agrupamento_codigo"] = [
            "html_combo_input",
            "nome" => "serie_agrupamento_codigo",
            "label" => "Grupo/Subgrupo",
            "objeto" => "agrupamento",
            "atributo" => "agrupamento_codigo",
            "atributos" => ["agrupamento_codigo", "agrupamento_dados_textuais_0_agrupamento_nome"],
            "sem_valor" => true,
            "dependencia" => [
                [
                    "campo" => "serie_acervo_codigo",
                    "atributo" => "agrupamento_acervo_codigo"
                ]
            ]
        ];

        $va_campos_edicao["serie_serie_superior_codigo"] = [
            "html_combo_input",
            "nome" => "serie_serie_superior_codigo",
            "label" => "Série superior",
            "objeto" => "serie",
            "atributos" => [
                "serie_codigo",
                "serie_nome" => ["hierarquia" => "serie_serie_superior_codigo"]
            ],
            "atributo" => "serie_codigo",
            "sem_valor" => true,
            "filtro" => [
                [
                    "valor" => $pn_objeto_codigo,
                    "atributo" => "serie_codigo",
                    "operador" => "!="
                ]
            ]
        ];

        $va_campos_edicao["serie_nome"] = [
            "html_text_input",
            "nome" => "serie_nome",
            "label" => "Série",
        ];

        $va_campos_edicao["serie_especie_documental_codigo"] = [
            "html_combo_input",
            "nome" => "serie_especie_documental_codigo",
            "label" => "Espécie",
            "objeto" => "especie_documental",
            "atributo" => "especie_documental_codigo",
            "atributos" => ["especie_documental_codigo", "especie_documental_dados_textuais_0_especie_documental_nome"],
            "sem_valor" => true,
        ];

        $va_campos_edicao["serie_tipo_documental_codigo"] = [
            "html_combo_input",
            "nome" => "serie_tipo_documental_codigo",
            "label" => "Tipo documental",
            "objeto" => "tipo_documental",
            "atributo" => "tipo_documental_codigo",
            "atributos" => ["tipo_documental_codigo", "tipo_documental_nome"],
            "sem_valor" => true,
            "dependencia" => [
                [
                    "campo" => "serie_especie_documental_codigo",
                    "atributo" => "especie_documental_codigo"
                ]
            ]
        ];

        /*
        $va_campos_edicao["serie_quantidade_itens"] = [
            "html_number_input",
            "nome" => "serie_quantidade_itens",
            "label" => "Quantidade de itens"
        ];
        */

        $va_campos_edicao["serie_atividade_geradora_codigo"] = [
            "html_combo_input",
            "nome" => "serie_atividade_geradora_codigo",
            "label" => "Atividade geradora",
            "objeto" => "atividade_geradora",
            "atributo" => "atividade_geradora_codigo",
            "atributos" => ["atividade_geradora_codigo", "atividade_geradora_nome"],
            "sem_valor" => true,
        ];

        $va_campos_edicao["serie_data"] = [
            "html_date_input",
            "nome" => "serie_data",
            "label" => "Datas inicial e final"
        ];

        $va_campos_edicao["serie_descricao"] = [
            "html_text_input",
            "nome" => "serie_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["serie_entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["serie_entidade", "serie_entidade_codigo"],
            "label" => "Relacionamentos com autoridades",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["serie_assunto_codigo"] = [
            "html_autocomplete",
            "nome" => ["serie_assunto", "serie_assunto_codigo"],
            "label" => "Relacionamentos com assuntos",
            "objeto" => "assunto",
            "atributos" => ["assunto_codigo", "assunto_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "assunto_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "assunto_nome"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["serie_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "serie_acervo_codigo",
            "label" => "Fundo/coleção",
            "objeto" => "conjunto_documental",
            "atributos" => ["acervo_codigo", "entidade_nome"],
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

        $va_filtros_navegacao["serie_nome"] = [
            "html_text_input",
            "nome" => "serie_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["serie_codigo"] = [
            "nome" => "serie_codigo",
            "exibir" => false
        ];

        $va_campos_visualizacao["serie_nome"] = [
            "nome" => "serie_nome"
        ];

        $va_campos_visualizacao["serie_acervo_codigo"] = [
            "nome" => "serie_acervo_codigo",
            "formato" => ["campo" => "entidade_nome"]
        ];

        $va_campos_visualizacao["serie_codigo_referencia"] = [
            "nome" => "serie_codigo_referencia"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["serie_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["serie_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "serie_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["serie_documento_codigo"] = [
            "nome" => "serie_documento_codigo",
            "formato" => ["campo" => "item_acervo_identificador"]
        ];

        $va_campos_visualizacao["serie_identificador"] = ["nome" => "serie_identificador"];
        $va_campos_visualizacao["serie_agrupamento_codigo"] = ["nome" => "serie_agrupamento_codigo"];
        $va_campos_visualizacao["serie_serie_superior_codigo"] = ["nome" => "serie_serie_superior_codigo"];
        $va_campos_visualizacao["serie_especie_documental_codigo"] = ["nome" => "serie_especie_documental_codigo"];
        $va_campos_visualizacao["serie_tipo_documental_codigo"] = ["nome" => "serie_tipo_documental_codigo"];
        $va_campos_visualizacao["serie_quantidade_itens"] = ["nome" => "serie_quantidade_itens"];
        $va_campos_visualizacao["serie_atividade_geradora_codigo"] = ["nome" => "serie_atividade_geradora_codigo"];
        $va_campos_visualizacao["serie_data"] = ["nome" => "serie_data"];
        $va_campos_visualizacao["serie_descricao"] = ["nome" => "serie_descricao"];
        $va_campos_visualizacao["serie_entidade_codigo"] = ["nome" => "serie_entidade_codigo"];
        $va_campos_visualizacao["serie_assunto_codigo"] = ["nome" => "serie_assunto_codigo"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "serie_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>