<?php

class atividade_geradora extends objeto_base
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
        return "atividade_geradora";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['atividade_geradora_codigo'] = [
            'atividade_geradora_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['atividade_geradora_nome'] = [
            'atividade_geradora_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['atividade_geradora_descricao'] = [
            'atividade_geradora_descricao',
            'coluna_tabela' => 'Descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['atividade_geradora_documento_codigo'] = [
            ['atividade_geradora_documento_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'atividade_geradora_codigo',
            'campos_relacionamento' => [
                'atividade_geradora_documento_codigo' => [
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

        $va_relacionamentos['atividade_geradora_serie_codigo'] = [
            ['atividade_geradora_serie_codigo'],
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'atividade_geradora_codigo',
            'campos_relacionamento' => [
                'atividade_geradora_serie_codigo' => [
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

        $va_campos_edicao["atividade_geradora_nome"] = ["html_text_input", "nome" => "atividade_geradora_nome", "label" => "Nome", "foco" => true];
        $va_campos_edicao["atividade_geradora_descricao"] = ["html_text_input", "nome" => "atividade_geradora_descricao", "label" => "Definição", "numero_linhas" => 8];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["atividade_geradora_nome"] = [
            "html_text_input",
            "nome" => "atividade_geradora_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["atividade_geradora_codigo"] = ["nome" => "atividade_geradora_codigo", "exibir" => false];
        $va_campos_visualizacao["atividade_geradora_nome"] = ["nome" => "atividade_geradora_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["atividade_geradora_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["atividade_geradora_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "atividade_geradora_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "atividade_geradora_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>