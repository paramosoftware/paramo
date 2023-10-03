<?php

class especie_documental extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->registros_filhos["tipo_documental"] = [
            "atributo_relacionamento" => "especie_documental_codigo",
            "pode_excluir_pai" => true
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "especie_documental";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['especie_documental_codigo'] = [
            'especie_documental_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();
        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['especie_documental_dados_textuais'] = [
            [
                'especie_documental_nome',
                'especie_documental_descricao'
            ],
            'tabela_intermediaria' => 'especie_documental_dados_textuais',
            'chave_exportada' => 'especie_documental_codigo',
            'campos_relacionamento' => [
                'especie_documental_nome' => 'nome',
                'especie_documental_descricao' => 'descricao',
            ],
            'tipos_campos_relacionamento' => ['s', 's'],
            'tem_idioma' => true,
            'tipo' => 'textual',
            'alias' => 'descritores textuais'
        ];

        $va_relacionamentos['especie_documental_documento_codigo'] = [
            'especie_documental_documento_codigo',
            'tabela_intermediaria' => 'documento_especie_documental',
            'chave_exportada' => 'especie_documental_codigo',
            'campos_relacionamento' => ['especie_documental_documento_codigo' => 'documento_codigo'],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'alias' => 'documentos'
        ];

        $va_relacionamentos['especie_documental_tipo_documental_codigo'] = [
            ['especie_documental_tipo_documental_codigo'],
            'tabela_intermediaria' => 'tipo_documental',
            'chave_exportada' => 'especie_documental_codigo',
            'campos_relacionamento' => [
                'especie_documental_tipo_documental_codigo' => [
                    ['codigo'],
                    "atributo" => "tipo_documental_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'tipo_documental',
            'objeto' => 'tipo_documental',
            'tipo' => '1n',
            'alias' => 'tipos documentais'
        ];

        $va_relacionamentos['especie_documental_serie_codigo'] = [
            ['especie_documental_serie_codigo'],
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'especie_documental_codigo',
            'campos_relacionamento' => [
                'especie_documental_serie_codigo' => [
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

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["especie_documental_dados_textuais_0_especie_documental_nome"] = [
            "html_text_input",
            "nome" => "especie_documental_dados_textuais_0_especie_documental_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["especie_documental_dados_textuais_0_especie_documental_descricao"] = [
            "html_text_input",
            "nome" => "especie_documental_dados_textuais_0_especie_documental_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["especie_documental_tipo_documental_codigo"] = [
            "html_autocomplete",
            "nome" => ['especie_documental_tipo_documental', 'especie_documental_tipo_documental_codigo'],
            "label" => "Tipos documentais",
            "objeto" => "tipo_documental",
            "atributos" => ["tipo_documental_codigo", "tipo_documental_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "tipo_documental_nome",
            "visualizacao" => "lista",
            "dependencia" => [
                [
                    "campo" => "especie_documental_codigo",
                    "atributo" => "especie_documental_codigo"
                ]

            ]
        ];

        /*
        $va_campos_edicao["especie_documental_documento_codigo"] = [
            "html_autocomplete",
            "nome" => ['especie_documental_documento', 'especie_documental_documento_codigo'],
            "label" => "Documentos",
            "objeto" => "Documento",
            "atributos" => ["documento_codigo", "item_acervo_identificador"],
            "multiplos_valores" => true,
            "procurar_por" => "item_acervo_codigo_0_item_acervo_identificador",
            "visualizacao" => "lista"
        ];
        */

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["especie_documental_dados_textuais_0_especie_documental_nome"] = [
            "html_text_input",
            "nome" => "especie_documental_dados_textuais_0_especie_documental_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["especie_documental_codigo"] = [
            "nome" => "especie_documental_codigo",
            "exibir" => false,
            "id_field" => true
        ];

        $va_campos_visualizacao["especie_documental_dados_textuais"] = [
            "nome" => "especie_documental_dados_textuais",
            "formato" => ["campo" => "especie_documental_nome"],
            "label_field" => true
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = [
            "especie_documental_dados_textuais_0_especie_documental_nome" => "Nome"
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = [
            "especie_documental_dados_textuais_0_especie_documental_nome" => "Nome"
        ];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "especie_documental_dados_textuais_0_especie_documental_nome" => [
                "label" => "Nome", "main_field" => true
            ],
        ];

        $va_campos_visualizacao["especie_documental_documento_codigo"] = [
            "nome" => "especie_documental_documento_codigo",
            "formato" => ["campo" => "item_acervo_identificador"]
        ];

        $va_campos_visualizacao["especie_documental_tipo_documental_codigo"] = [
            "nome" => "especie_documental_tipo_documental_codigo",
            "formato" => ["campo" => "tipo_documental_nome"]
        ];

        $va_campos_visualizacao["representante_digital_codigo"] = [
            "nome" => "representante_digital_codigo"
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "especie_documental_dados_textuais_0_especie_documental_nome" => [
                "label" => "Nome", "main_field" => true
            ],
        ];
    }

}

?>