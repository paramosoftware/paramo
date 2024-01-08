<?php

class pagina_site extends objeto_base
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
        return "pagina_site";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['pagina_site_codigo'] = [
            'pagina_site_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['pagina_site_id'] = [
            'pagina_site_id',
            'coluna_tabela' => 'id',
            'tipo_dado' => 's',
            'processar' => [
                'slugfy',
                ['pagina_site_dados_textuais_0_pagina_site_titulo']
            ]
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['pagina_site_dados_textuais'] = [
            [
                'pagina_site_titulo',
                'pagina_site_conteudo',
                'pagina_site_subtitulo'
            ],
            'tabela_intermediaria' => 'pagina_site_dados_textuais',
            'chave_exportada' => 'pagina_site_codigo',
            'campos_relacionamento' => [
                'pagina_site_titulo' => 'titulo',
                'pagina_site_subtitulo' => 'subtitulo',
                'pagina_site_conteudo' => 'conteudo',
            ],
            'tipos_campos_relacionamento' => ['s', 's', 's'],
            'tem_idioma' => true,
            'tipo' => 'textual'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["pagina_site_dados_textuais_0_pagina_site_titulo"] = [
            "html_text_input",
            "nome" => "pagina_site_dados_textuais_0_pagina_site_titulo",
            "label" => "Título",
            "foco" => true
        ];

        $va_campos_edicao["pagina_site_dados_textuais_0_pagina_site_subtitulo"] = [
            "html_text_input",
            "nome" => "pagina_site_dados_textuais_0_pagina_site_subtitulo",
            "label" => "Subtítulo"
        ];

        $va_campos_edicao["pagina_site_dados_textuais_0_pagina_site_conteudo"] = [
            "html_text_input",
            "nome" => "pagina_site_dados_textuais_0_pagina_site_conteudo",
            "label" => "Texto",
            "numero_linhas" => 8,
            "formato" => "rich"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["pagina_site_dados_textuais_0_pagina_site_titulo"] = [
            "html_text_input",
            "nome" => "pagina_site_dados_textuais_0_pagina_site_titulo",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["pagina_site_codigo"] = ["nome" => "pagina_site_codigo", "exibir" => false];
        $va_campos_visualizacao["pagina_site_id"] = [
            "nome" => "pagina_site_id"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["pagina_site_dados_textuais_0_titulo" => "Título"];

        $va_campos_visualizacao["pagina_site_dados_textuais"] = [
            "nome" => "pagina_site_dados_textuais"
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["pagina_site_dados_textuais_0_titulo" => "Título"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "pagina_site_dados_textuais_0_pagina_site_titulo" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>