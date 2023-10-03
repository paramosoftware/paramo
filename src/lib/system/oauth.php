<?php

class oauth extends objeto_base
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
        return "oauth";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['oauth_codigo'] = [
            'oauth_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['oauth_servico'] = [
            'oauth_servico',
            'coluna_tabela' => 'servico',
            'tipo_dado' => 's'
        ];

        $va_atributos['oauth_token'] = [
            'oauth_token',
            'coluna_tabela' => 'token',
            'tipo_dado' => 's'
        ];

        $va_atributos['oauth_usuario_codigo'] = [
            'oauth_usuario_codigo',
            'coluna_tabela' => 'usuario_codigo',
            'tipo_dado' => 'i'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
    {
        return array();
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["oauth_token"] = [
            "html_text_input",
            "nome" => "oauth_token",
            "label" => "Token",
            "foco" => true
        ];

        $va_campos_edicao["oauth_itens"] = [
            "html_text_input",
            "nome" => "oauth_itens",
            "label" => "String de itens selecionados separados por |",
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["oauth_token"] = [
            "html_text_input",
            "nome" => "oauth_token",
            "label" => "token",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["oauth_codigo"] = ["nome" => "oauth_codigo", "exibir" => false];
        $va_campos_visualizacao["oauth_token"] = ["nome" => "oauth_token", "label" => "Token"];
        $va_campos_visualizacao["oauth_servico"] = ["nome" => "oauth_servico", "label" => "Serviço"];
        $va_campos_visualizacao["oauth_usuario_codigo"] = ["nome" => "oauth_usuario_codigo", "exibir" => false];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["oauth_token" => "Token"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["oauth_token" => "Token"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "oauth_token" => ["label" => "Token", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "oauth_token" => ["label" => "Token", "main_field" => true],
        ];
    }

}