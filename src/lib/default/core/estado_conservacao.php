<?php

class estado_conservacao extends objeto_base
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
        return "estado_conservacao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['estado_conservacao_codigo'] = [
            'estado_conservacao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['estado_conservacao_nome'] = [
            'estado_conservacao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['estado_conservacao_descricao'] = [
            'estado_conservacao_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['estado_conservacao_item_acervo_codigo'] = [
            [
                'estado_conservacao_item_acervo_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_estado_conservacao',
            'chave_exportada' => 'estado_conservacao_codigo',
            'campos_relacionamento' => [
                'estado_conservacao_item_acervo_codigo' => 'item_acervo_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens do acervo'
        ];

        $va_relacionamentos['estado_conservacao_acervo_codigo'] = [
            ['estado_conservacao_acervo_codigo'],
            'tabela_intermediaria' => 'acervo',
            'chave_exportada' => 'estado_conservacao_codigo',
            'campos_relacionamento' => [
                'estado_conservacao_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'tipo' => '1n',
            'alias' => "acervos"
        ];

        $va_relacionamentos['estado_conservacao_documento_codigo'] = [
            ['estado_conservacao_documento_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'estado_conservacao_codigo',
            'campos_relacionamento' => [
                'estado_conservacao_documento_codigo' => [
                    ['codigo'],
                    "atributo" => "documento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'tipo' => '1n',
            'alias' => "documentos"
        ];


        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["estado_conservacao_nome"] = [
            "html_text_input",
            "nome" => "estado_conservacao_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["estado_conservacao_descricao"] = [
            "html_text_input",
            "nome" => "estado_conservacao_descricao",
            "label" => "Definição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["estado_conservacao_nome"] = [
            "html_text_input",
            "nome" => "estado_conservacao_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["estado_conservacao_descricao"] = [
            "html_text_input",
            "nome" => "estado_conservacao_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["estado_conservacao_codigo"] = ["nome" => "estado_conservacao_codigo", "exibir" => false];
        $va_campos_visualizacao["estado_conservacao_nome"] = ["nome" => "estado_conservacao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["estado_conservacao_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["estado_conservacao_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "estado_conservacao_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["estado_conservacao_descricao"] = ["nome" => "estado_conservacao_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "estado_conservacao_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>