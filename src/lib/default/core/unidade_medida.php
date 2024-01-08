<?php
class unidade_medida extends objeto_base
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
        return "unidade_medida";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['unidade_medida_codigo'] = [
            'unidade_medida_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['unidade_medida_nome'] = [
            'unidade_medida_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['unidade_medida_item_acervo_codigo'] = [
            [
                'unidade_medida_item_acervo_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_dimensao',
            'chave_exportada' => 'unidade_medida_codigo',
            'campos_relacionamento' => [
                'unidade_medida_item_acervo_codigo' => 'item_acervo_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens do acervo'
        ];

        $va_relacionamentos['unidade_medida_tipo_dimensao_codigo'] = [
            [
                'unidade_medida_tipo_dimensao_codigo'
            ],
            'tabela_intermediaria' => 'tipo_dimensao_unidade_medida',
            'chave_exportada' => 'unidade_medida_codigo',
            'campos_relacionamento' => [
                'unidade_medida_tipo_dimensao_codigo' => 'tipo_dimensao_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'tipo_dimensao',
            'objeto' => 'tipo_dimensao',
            'alias' => 'tipos de dimensão'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["unidade_medida_nome"] = [
            "html_text_input",
            "nome" => "unidade_medida_nome",
            "label" => "Nome",
            "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["unidade_medida_nome"] = [
            "html_text_input",
            "nome" => "unidade_medida_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["unidade_medida_codigo"] = ["nome" => "unidade_medida_codigo", "exibir" => false];
        $va_campos_visualizacao["unidade_medida_nome"] = ["nome" => "unidade_medida_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["unidade_medida_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["unidade_medida_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "unidade_medida_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "unidade_medida_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>