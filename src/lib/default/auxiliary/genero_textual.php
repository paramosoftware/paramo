<?php

class genero_textual extends objeto_base
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
        return "genero_textual";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['genero_textual_codigo'] = [
            'genero_textual_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['genero_textual_nome'] = [
            'genero_textual_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['genero_textual_descricao'] = [
            'genero_textual_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['genero_textual_item_acervo_codigo'] = [
            ['genero_textual_item_acervo_codigo'],
            'tabela_intermediaria' => 'item_acervo',
            'chave_exportada' => 'genero_textual_codigo',
            'campos_relacionamento' => [
                'genero_textual_item_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "item_acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'tipo' => '1n',
            'alias' => "itens de acervo"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["genero_textual_nome"] = [
            "html_text_input",
            "nome" => "genero_textual_nome",
            "label" => "Nome", "foco" => true
        ];

        $va_campos_edicao["genero_textual_descricao"] = [
            "html_text_area",
            "nome" => "genero_textual_descricao",
            "label" => "Descrição"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["genero_textual_nome"] = [
            "html_text_input",
            "nome" => "genero_textual_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["genero_textual_descricao"] = [
            "html_text_input",
            "nome" => "genero_textual_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }


    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["genero_textual_codigo"] = ["nome" => "genero_textual_codigo"];
        $va_campos_visualizacao["genero_textual_nome"] = ["nome" => "genero_textual_nome"];
        $va_campos_visualizacao["genero_textual_descricao"] = ["nome" => "genero_textual_descricao"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["genero_textual_nome" => "nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["genero_textual_nome" => "nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>