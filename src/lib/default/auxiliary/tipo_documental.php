<?php

class tipo_documental extends objeto_base
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
        return "tipo_documental";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'tipo_documental_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_documental_nome'] = [
            'tipo_documental_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['tipo_documental_descricao'] = [
            'tipo_documental_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['especie_documental_codigo'] = [
            'especie_documental_codigo',
            'coluna_tabela' => 'especie_documental_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'especie_documental'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_documental_documento_codigo'] = [
            'tipo_documental_documento_codigo',
            'tabela_intermediaria' => 'documento_especie_documental',
            'chave_exportada' => 'tipo_documental_codigo',
            'campos_relacionamento' => ['tipo_documental_documento_codigo' => 'documento_codigo'],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'alias' => 'documentos'
        ];

        $va_relacionamentos['tipo_documental_serie_codigo'] = [
            ['tipo_documental_serie_codigo'],
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'tipo_documental_codigo',
            'campos_relacionamento' => [
                'tipo_documental_serie_codigo' => [
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

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return array(
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "tipo_documental",
            "atributos" =>
                [
                    $ps_campo_codigo == '' ? "tipo_documental_codigo" : $ps_campo_codigo,
                    "tipo_documental_nome"
                ],
            "dependencia" => [
                [
                    "campo" => "tipo_documental_nome",
                    "atributo" => "tipo_documental_nome"
                ],
                [
                    "campo" => "especie_documental_codigo",
                    "atributo" => "especie_documental_codigo"
                ]
            ]
        );
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["especie_documental_codigo"] = [
            "html_combo_input",
            "nome" => "especie_documental_codigo",
            "label" => "Espécie documental",
            "objeto" => "especie_documental",
            "atributos" => ["especie_documental_codigo", "especie_documental_dados_textuais_0_especie_documental_nome"],
            "atributo" => "especie_documental_codigo",
            "sem_valor" => false,
            "foco" => true
        ];

        $va_campos_edicao["tipo_documental_nome"] = [
            "html_text_input",
            "nome" => "tipo_documental_nome",
            "label" => "Nome"
        ];

        $va_campos_edicao["tipo_documental_descricao"] = [
            "html_text_input",
            "nome" => "tipo_documental_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_documental_nome"] = [
            "html_text_input",
            "nome" => "tipo_documental_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["tipo_documental_codigo"] = ["nome" => "tipo_documental_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_documental_nome"] = ["nome" => "tipo_documental_nome"];

        $va_campos_visualizacao["especie_documental_codigo"] = ["nome" => "especie_documental_codigo",
            "formato" => ["campo" => "especie_documental_dados_textuais_0_especie_documental_nome"]];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_documental_nome" => "Nome"];

        $va_campos_visualizacao["tipo_documental_descricao"] = ["nome" => "tipo_documental_descricao"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_documental_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_documental_nome" => ["label" => "Nome", "main_field" => true],
            "especie_documental_codigo" => "Espécie documental",
            "tipo_documental_descricao" => ["label" => "Descrição", "descriptive_field" => true]
        ];

        $va_campos_visualizacao["tipo_documental_documento_codigo"] = [
            "nome" => "tipo_documental_documento_codigo",
            "formato" => ["campo" => "item_acervo_identificador"]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_documental_nome" => ["label" => "Nome", "main_field" => true],
            "especie_documental_codigo" => "Espécie documental",
            "tipo_documental_documento_codigo" => "Documentos",
            "tipo_documental_descricao" => ["label" => "Descrição", "descriptive_field" => true]
        ];
    }


}

?>