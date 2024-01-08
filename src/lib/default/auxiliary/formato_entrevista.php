<?php

class formato_entrevista extends objeto_base
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
        return "formato_entrevista";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'formato_entrevista_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['formato_entrevista_nome'] = [
            'formato_entrevista_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's',
            "foco" => true
        ];

        $va_atributos['formato_entrevista_descricao'] = [
            'formato_entrevista_descricao',
            'coluna_tabela' => 'Descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['formato_entrevista_documento_codigo'] = [
            'formato_entrevista_entrevista_codigo',
            'tabela_intermediaria' => 'entrevista_formato_entrevista',
            'chave_exportada' => 'formato_entrevista_codigo',
            'campos_relacionamento' => [
                'formato_entrevista_entrevista_codigo' => 'entrevista_codigo'
            ],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'entrevista',
            'objeto' => 'entrevista',
            'alias' => 'entrevistas'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["formato_entrevista_nome"] = [
            "html_text_input",
            "nome" => "formato_entrevista_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["formato_entrevista_descricao"] = [
            "html_text_input",
            "nome" => "formato_entrevista_descricao",
            "label" => "Definição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["formato_entrevista_nome"] = [
            "html_text_input",
            "nome" => "formato_entrevista_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["formato_entrevista_descricao"] = [
            "html_text_input",
            "nome" => "formato_entrevista_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];


        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["formato_entrevista_codigo"] = [
            "nome" => "formato_entrevista_codigo",
            "exibir" => false
        ];
        $va_campos_visualizacao["formato_entrevista_nome"] = ["nome" => "formato_entrevista_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["formato_entrevista_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["formato_entrevista_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "formato_entrevista_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["formato_entrevista_descricao"] = ["nome" => "formato_entrevista_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "formato_entrevista_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>
