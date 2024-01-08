<?php

class tipo_entrevista extends objeto_base
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
        return "tipo_entrevista";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'tipo_entrevista_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_entrevista_nome'] = [
            'tipo_entrevista_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['tipo_entrevista_descricao'] = [
            'tipo_entrevista_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_entrevista_entrevista_codigo'] = [
            ['tipo_entrevista_entrevista_codigo'],
            'tabela_intermediaria' => 'entrevista',
            'chave_exportada' => 'tipo_entrevista_codigo',
            'campos_relacionamento' => [
                'tipo_entrevista_entrevista_codigo' => [
                    ['codigo'],
                    "atributo" => "entrevista_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entrevista',
            'objeto' => 'entrevista',
            'tipo' => '1n',
            'alias' => "entrevistas"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_entrevista_nome"] = [
            "html_text_input",
            "nome" => "tipo_entrevista_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["tipo_entrevista_descricao"] = [
            "html_text_input",
            "nome" => "tipo_entrevista_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_entrevista_nome"] = [
            "html_text_input",
            "nome" => "tipo_entrevista_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["tipo_entrevista_codigo"] = ["nome" => "tipo_entrevista_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_entrevista_nome"] = ["nome" => "tipo_entrevista_nome"];
        $va_campos_visualizacao["tipo_entrevista_descricao"] = ["nome" => "tipo_entrevista_descricao"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_entrevista_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_entrevista_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_entrevista_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_entrevista_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>