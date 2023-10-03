<?php

class status_item_acervo extends objeto_base
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
        return "status_item_acervo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['status_item_acervo_codigo'] = [
            'status_item_acervo_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['status_item_acervo_nome'] = [
            'status_item_acervo_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["status_item_acervo_nome"] = ["html_text_input", "nome" => "status_item_acervo_nome", "label" => "Nome", "foco" => true];

        return $va_campos_edicao;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['status_item_acervo_item_acervo_codigo'] = [
            ['status_item_acervo_item_acervo_codigo'],
            'tabela_intermediaria' => 'item_acervo',
            'chave_exportada' => 'status_item_acervo_codigo',
            'campos_relacionamento' => [
                'status_item_acervo_item_acervo_codigo' => [
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

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["status_item_acervo_codigo"] = ["nome" => "status_item_acervo_codigo", "exibir" => false];
        $va_campos_visualizacao["status_item_acervo_nome"] = ["nome" => "status_item_acervo_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["status_item_acervo_codigo" => "Natural"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["status_item_acervo_codigo" => "Natural"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>