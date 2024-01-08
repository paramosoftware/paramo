<?php

class tipo_contexto extends objeto_base
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
        return "tipo_contexto";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_contexto_codigo'] = [
            'tipo_contexto_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_contexto_nome'] = [
            'tipo_contexto_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_contexto_contexto_codigo'] = [
            [
                'tipo_contexto_contexto_codigo'
            ],
            'tabela_intermediaria' => 'contexto_tipo_contexto',
            'chave_exportada' => 'tipo_contexto_codigo',
            'campos_relacionamento' => [
                'tipo_contexto_contexto_codigo' => 'contexto_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'contexto',
            'objeto' => 'contexto',
            'alias' => 'contextos'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_contexto_nome"] = ["html_text_input", "nome" => "tipo_contexto_nome", "label" => "Nome", "foco" => true];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_contexto_nome"] = [
            "html_text_input",
            "nome" => "tipo_contexto_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_contexto_codigo"] = ["nome" => "tipo_contexto_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_contexto_nome"] = ["nome" => "tipo_contexto_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_contexto_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_contexto_nome" => "Nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>