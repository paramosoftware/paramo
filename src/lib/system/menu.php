<?php

class menu extends objeto_base
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
        return "menu";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['menu_codigo'] = [
            'menu_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['menu_url'] = [
            'menu_url',
            'coluna_tabela' => 'url',
            'tipo_dado' => 's'
        ];

        $va_atributos['menu_pagina_site_codigo'] = [
            'menu_pagina_site_codigo',
            'coluna_tabela' => 'pagina_site_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'pagina_site'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['menu_dados_textuais'] = [
            [
                'menu_nome'
            ],
            'tabela_intermediaria' => 'menu_dados_textuais',
            'chave_exportada' => 'menu_codigo',
            'campos_relacionamento' => [
                'menu_nome' => 'nome'
            ],
            'tipos_campos_relacionamento' => ['s'],
            'tem_idioma' => true,
            'tipo' => 'textual'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["menu_dados_textuais_0_menu_nome"] = [
            "html_text_input",
            "nome" => "menu_dados_textuais_0_menu_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["menu_url"] = [
            "html_text_input",
            "nome" => "menu_url",
            "label" => "URL",
            "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["menu_codigo"] = ["nome" => "menu_codigo", "exibir" => false];
        
        $va_campos_visualizacao["menu_dados_textuais"] = [
            "nome" => "menu_dados_textuais"
        ];

        $va_campos_visualizacao["menu_url"] = [
            "nome" => "menu_url"
        ];

        $va_campos_visualizacao["menu_pagina_site_codigo"] = [
            "nome" => "menu_pagina_site_codigo"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["menu_dados_textuais_0_menu_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["menu_dados_textuais_0_menu_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "menu_dados_textuais_0_menu_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>