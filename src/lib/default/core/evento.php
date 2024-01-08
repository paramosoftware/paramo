<?php

class evento extends objeto_base
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
        return "evento";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['evento_codigo'] = [
            'evento_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['evento_nome'] = [
            'evento_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['evento_descricao'] = [
            'evento_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['evento_tipo_evento_codigo'] = [
            'evento_tipo_evento_codigo',
            'coluna_tabela' => 'tipo_evento_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_evento'
        ];

        $va_atributos['evento_data'] = [
            'evento_data',
            'coluna_tabela' => [
                'data_inicial' => 'data_inicial',
                'data_final' => 'data_final',
                'presumido' => 'data_presumida',
                'sem_data' => 'sem_data'
            ],
            'tipo_dado' => 'dt'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["evento_nome"] = [
            "html_text_input",
            "nome" => "evento_nome",
            "label" => "Nome",
        ];

        $va_campos_edicao["evento_tipo_evento_codigo"] = [
            "html_combo_input",
            "nome" => "evento_tipo_evento_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_evento",
            "atributos" => ["tipo_evento_codigo", "tipo_evento_nome"],
            "atributo" => "tipo_evento_codigo"
        ];

        $va_campos_edicao["evento_data"] = [
            "html_date_input",
            "nome" => "evento_data",
            "label" => "Data"
        ];

        $va_campos_edicao["evento_descricao"] = [
            "html_text_input",
            "nome" => "evento_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["evento_nome"] = [
            "html_text_input",
            "nome" => "evento_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["evento_descricao"] = [
            "html_text_input",
            "nome" => "evento_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["evento_codigo"] = ["nome" => "evento_codigo", "exibir" => false];
        $va_campos_visualizacao["evento_nome"] = ["nome" => "evento_nome", "exibir" => true];

        $va_campos_visualizacao["evento_tipo_evento_codigo"] = [
            "nome" => "evento_tipo_evento_codigo",
            "formato" => ["campo" => "tipo_evento_nome"]
        ];

        $va_campos_visualizacao["evento_data"] = ["nome" => "evento_data", "formato" => ["data" => "ano"]];
        $va_campos_visualizacao["evento_descricao"] = ["nome" => "evento_descricao"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = [
            "evento_nome" => "Nome",
            "evento_data_data_inicial" => "Data"
        ];

        $va_campos_visualizacao["evento_nome_relacionado_codigo"] = [
            "nome" => "evento_nome_relacionado_codigo",
            "formato" => ["campo" => "nome_relacionado_nome"]
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["evento_data_data_inicial" => "Data"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "evento_nome" => ["label" => "Nome", "main_field" => true],
            "evento_tipo_evento_codigo" => "Tipo",
            "evento_data_data_inicial" => "Data"
        ];



        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>