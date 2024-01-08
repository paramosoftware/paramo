<?php

class genero extends objeto_base
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
        return "genero";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['genero_codigo'] = [
            'genero_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['genero_nome'] = [
            'genero_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['genero_descricao'] = [
            'genero_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['genero_entidade_codigo'] = [
            'genero_entidade_codigo',
            'tabela_intermediaria' => 'entidade',
            'chave_exportada' => 'genero_codigo',
            'campos_relacionamento' => [
                'genero_entidade_codigo' => [
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

        $va_campos_edicao["genero_nome"] = [
            "html_text_input",
            "nome" => "genero_nome",
            "label" => "Nome", "foco" => true
        ];

        $va_campos_edicao["genero_descricao"] = [
            "html_text_area",
            "nome" => "genero_descricao",
            "label" => "Descrição"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["genero_nome"] = [
            "html_text_input",
            "nome" => "genero_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["genero_descricao"] = [
            "html_text_input",
            "nome" => "genero_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];


        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["genero_codigo"] = ["nome" => "genero_codigo", "exibir" => false];
        $va_campos_visualizacao["genero_nome"] = ["nome" => "genero_nome"];
        $va_campos_visualizacao["genero_descricao"] = ["nome" => "genero_descricao"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["genero_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["genero_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "genero_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "genero_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>