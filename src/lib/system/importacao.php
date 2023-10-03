<?php

class importacao extends objeto_base
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
        return "importacao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['importacao_codigo'] = [
            'importacao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['importacao_recurso_sistema_codigo'] = [
            'importacao_recurso_sistema_codigo',
            'coluna_tabela' => 'recurso_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => "recurso_sistema"
        ];

        $va_atributos['importacao_nome'] = [
            'importacao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['importacao_habilitado'] = [
            'importacao_habilitado',
            'coluna_tabela' => 'habilitado',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['importacao_campo_sistema_codigo'] = [
            [
                'importacao_campo_sistema_codigo',
                'importacao_campo_sistema_sequencia'
            ],
            'tabela_intermediaria' => 'importacao_campo_sistema',
            'chave_exportada' => 'importacao_codigo',
            'campos_relacionamento' =>
                [
                    'importacao_campo_sistema_codigo' => 'campo_sistema_codigo',
                    'importacao_campo_sistema_sequencia' => ['sequencia', "valor_sequencial" => true],
                ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'campo_sistema',
            'objeto' => 'campo_sistema',
            'alias' => 'campos do sistema'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["importacao_recurso_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "importacao_recurso_sistema_codigo",
            "label" => "Recurso do sistema",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => false,
            "conectar" => [
                [
                    "campo" => "importacao_campos_sistema",
                    "atributo" => "campo_sistema_recurso_sistema_codigo"
                ]
            ]
        ];

        $va_campos_edicao["importacao_nome"] = [
            "html_text_input",
            "nome" => "importacao_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["importacao_campo_sistema_codigo"] = [
            "html_autocomplete", 
            "nome" => ["importacao_campo_sistema", "importacao_campo_sistema_codigo"],
            "label" => "Campos", 
            "objeto" => "campo_sistema",
            "atributos" => 
                [
                    "campo_sistema_codigo", 
                    "campo_sistema_alias"
                ],
            "multiplos_valores" => true,
            "procurar_por" => "campo_sistema_alias",
            "dependencia" => [
                [
                    "campo" => "importacao_recurso_sistema_codigo", 
                    "atributo" => "campo_sistema_recurso_sistema_codigo",
                ]
            ],
            "visualizacao" => "lista"
        ];

        $va_campos_edicao["importacao_habilitado"] = [
            "html_checkbox_input",
            "nome" => "importacao_habilitado",
            "label" => "Habilitada"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_importacao = array();
        $va_campos_importacao["importacao_codigo"] = ["nome" => "importacao_codigo", "exibir" => false];
        $va_campos_importacao["importacao_nome"] = ["nome" => "importacao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_importacao;
        $this->visualizacoes["lista"]["order_by"] = ["importacao_nome" => "Nome"];

        $va_campos_importacao["importacao_recurso_sistema_codigo"] = [
            "nome" => "importacao_recurso_sistema_codigo",
            "formato" => ["campo" => "recurso_sistema_nome_singular"]
        ];

        $va_campos_importacao["importacao_campo_sistema_codigo"] = [
            "nome" => "importacao_campo_sistema_codigo",
            "formato" => ["campo" => "campo_sistema_nome"]
        ];

        $va_campos_importacao["importacao_habilitado"] = ["nome" => "importacao_habilitado"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_importacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["importacao_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "importacao_nome" => ["label" => "Nome", "main_field" => true],
            "importacao_recurso_sistema_codigo" => "Recurso"
        ];

        $va_campos_importacao["artigo_paginas"] = [
            "nome" => "artigo_paginas",
            "formato" => ["campo" => ["campo_sistema_nome"]]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_importacao;
    }

}

?>