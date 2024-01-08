<?php

class raca extends objeto_base
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
        return "raca";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['raca_codigo'] = [
            'raca_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['raca_nome'] = [
            'raca_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['raca_entidade_codigo'] = [
            'raca_entidade_codigo',
            'tabela_intermediaria' => 'entidade',
            'chave_exportada' => 'raca_codigo',
            'campos_relacionamento' => [
                'raca_entidade_codigo' => [
                    ['codigo'],
                    "atributo" => "entidade_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'tipo' => '1n',
            'alias' => 'entidades'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["raca_nome"] = [
            "html_text_input",
            "nome" => "raca_nome",
            "label" => "Nome", "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["raca_nome"] = [
            "html_text_input",
            "nome" => "raca_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["raca_codigo"] = ["nome" => "raca_codigo", "exibir" => false];
        $va_campos_visualizacao["raca_nome"] = ["nome" => "raca_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["raca_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["raca_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "raca_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "raca_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>