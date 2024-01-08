<?php

class tipo_acesso extends objeto_base
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
        return "tipo_acesso";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_acesso_codigo'] = [
            'tipo_acesso_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_acesso_nome'] = [
            'tipo_acesso_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_acesso_item_acervo_codigo'] = [
            ['tipo_acesso_item_acervo_codigo'],
            'tabela_intermediaria' => 'item_acervo',
            'chave_exportada' => 'tipo_acesso_codigo',
            'campos_relacionamento' => [
                'tipo_acesso_item_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "item_acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'tipo' => '1n',
            'alias' => "itens do acervo"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_acesso_nome"] = [
            "html_text_input",
            "nome" => "tipo_acesso_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_acesso_codigo"] = ["nome" => "tipo_acesso_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_acesso_nome"] = ["nome" => "tipo_acesso_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_acesso_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_acesso_nome" => "Nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>