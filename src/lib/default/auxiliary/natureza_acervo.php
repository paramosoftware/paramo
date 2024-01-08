<?php

class natureza_acervo extends objeto_base
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
        return "tipo_acervo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['natureza_acervo_codigo'] = [
            'natureza_acervo_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['natureza_acervo_nome'] = [
            'natureza_acervo_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['natureza_acervo_acervo_codigo'] = [
            ['natureza_acervo_acervo_codigo'],
            'tabela_intermediaria' => 'acervo',
            'chave_exportada' => 'natureza_acervo_codigo',
            'campos_relacionamento' => [
                'natureza_acervo_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'tipo' => '1n',
            'alias' => "acervos"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["natureza_acervo_nome"] = [
            "html_text_input",
            "nome" => "natureza_acervo_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["natureza_acervo_codigo"] = ["nome" => "natureza_acervo_codigo", "exibir" => false];
        $va_campos_visualizacao["natureza_acervo_nome"] = ["nome" => "natureza_acervo_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["natureza_acervo_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["natureza_acervo_nome" => "Nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>