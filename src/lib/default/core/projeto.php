<?php

class projeto extends objeto_base
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
        return "projeto";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'projeto_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['projeto_nome'] = [
            'projeto_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['projeto_descricao'] = [
            'projeto_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['projeto_entrevista_codigo'] = [
            'projeto_entrevista_codigo',
            'tabela_intermediaria' => 'entrevista',
            'chave_exportada' => 'projeto_codigo',
            'campos_relacionamento' => [
                'projeto_entrevista_codigo' => [
                    ['codigo'],
                    "atributo" => "entrevista_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entrevista',
            'objeto' => 'entrevista',
            'tipo' => '1n',
            'alias' => 'entrevistas'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["projeto_nome"] = [
            "html_text_input",
            "nome" => "projeto_nome",
            "label" => "Nome", "foco" => true
        ];

        $va_campos_edicao["projeto_descricao"] = [
            "html_text_input",
            "nome" => "projeto_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["projeto_nome"] = [
            "html_text_input",
            "nome" => "projeto_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["projeto_descricao"] = [
            "html_text_input",
            "nome" => "projeto_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["projeto_codigo"] = ["nome" => "projeto_codigo"];
        $va_campos_visualizacao["projeto_nome"] = ["nome" => "projeto_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["projeto_nome" => "nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["projeto_nome" => "nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "projeto_nome" => ["label" => "Nome", "main_field" => true]
        ];

        $va_campos_visualizacao["projeto_descricao"] = ["nome" => "projeto_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "projeto_nome" => ["label" => "Nome", "main_field" => true],
            "projeto_descricao" => ["label" => "Descrição", "descriptive_field" => true],
        ];
    }
}

?>