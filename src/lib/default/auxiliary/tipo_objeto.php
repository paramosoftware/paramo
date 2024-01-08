<?php

class tipo_objeto extends objeto_base
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
        return "tipo_objeto";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_objeto_codigo'] = [
            'tipo_objeto_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_objeto_nome'] = [
            'tipo_objeto_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['tipo_objeto_descricao'] = [
            'tipo_objeto_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_objeto_objeto_codigo'] = [ 
            ['tipo_objeto_objeto_codigo'], 
            'tabela_intermediaria' => 'objeto', 
            'chave_exportada' => 'tipo_objeto_codigo', 
            'campos_relacionamento' => [
                'tipo_objeto_objeto_codigo' => [
                    ['codigo'], 
                    "atributo" => "objeto_codigo"
                ]
            ], 
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'objeto',
            'objeto' => 'objeto',
            'tipo' => '1n',
            'alias' => "objetos"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_objeto_nome"] = [
            "html_text_input",
            "nome" => "tipo_objeto_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["tipo_objeto_descricao"] = [
            "html_text_input",
            "nome" => "tipo_objeto_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_objeto_nome"] = [
            "html_text_input",
            "nome" => "tipo_objeto_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["tipo_objeto_descricao"] = [
            "html_text_input",
            "nome" => "tipo_objeto_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_objeto_codigo"] = ["nome" => "tipo_objeto_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_objeto_nome"] = ["nome" => "tipo_objeto_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_objeto_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_objeto_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_objeto_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["tipo_objeto_descricao"] = ["nome" => "tipo_objeto_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_objeto_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>