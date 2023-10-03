<?php

class palavra_chave extends objeto_base
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
        return "palavra_chave";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['palavra_chave_codigo'] = [
            'palavra_chave_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['palavra_chave_nome'] = [
            'palavra_chave_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['palavra_chave_item_acervo_codigo'] = [
            [
                'palavra_chave_item_acervo_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_palavra_chave',
            'chave_exportada' => 'palavra_chave_codigo',
            'campos_relacionamento' => [
                'palavra_chave_item_acervo_codigo' => 'item_acervo_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens de acervos'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["palavra_chave_nome"] = ["html_text_input", "nome" => "palavra_chave_nome", "label" => "Nome", "foco" => true];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["palavra_chave_nome"] = [
            "html_text_input",
            "nome" => "palavra_chave_nome",
            "label" => "Palavra-chave",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["palavra_chave_codigo"] = ["nome" => "palavra_chave_codigo", "exibir" => false];
        $va_campos_visualizacao["palavra_chave_nome"] = ["nome" => "palavra_chave_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["palavra_chave_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["palavra_chave_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "palavra_chave_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "palavra_chave_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>