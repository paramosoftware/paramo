<?php

class tipo_arquivo extends objeto_base
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
        return "tipo_arquivo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_arquivo_codigo'] = [
            'tipo_arquivo_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_arquivo_nome'] = [
            'tipo_arquivo_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_arquivo_acervo_codigo'] = [
            ['tipo_arquivo_acervo_codigo'],
            'tabela_intermediaria' => 'acervo',
            'chave_exportada' => 'tipo_arquivo_codigo',
            'campos_relacionamento' => [
                'tipo_arquivo_acervo_codigo' => [
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

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_arquivo_nome"] = [
            "html_text_input",
            "nome" => "tipo_arquivo_nome",
            "label" => "Nome",
            "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_arquivo_nome"] = [
            "html_text_input",
            "nome" => "tipo_arquivo_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_arquivo_codigo"] = ["nome" => "tipo_arquivo_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_arquivo_nome"] = ["nome" => "tipo_arquivo_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_arquivo_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_arquivo_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_arquivo_nome" => ["label" => "Nome", "main_field" => true]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_arquivo_nome" => ["label" => "Nome", "main_field" => true]
        ];
    }

}

?>