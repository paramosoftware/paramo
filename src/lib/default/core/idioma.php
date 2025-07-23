<?php
class idioma extends objeto_base
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
        return "idioma";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['idioma_codigo'] = [
            'idioma_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['idioma_nome'] = [
            'idioma_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['idioma_sigla'] = [
            'idioma_sigla',
            'coluna_tabela' => 'Sigla',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['idioma_item_acervo_codigo'] = [
            [
                'idioma_item_acervo_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_idioma',
            'chave_exportada' => 'idioma_codigo',
            'campos_relacionamento' => [
                'idioma_item_acervo_codigo' => 'item_acervo_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens de acervos'
        ];

        $va_relacionamentos['idioma_item_acervo_dados_textuais'] = [
            [
                'idioma_item_acervo_dados_textuais'
            ],
            'tabela_intermediaria' => 'item_acervo_dados_textuais',
            'chave_exportada' => 'idioma_codigo',
            'campos_relacionamento' => [
                'idioma_item_acervo_dados_textuais' => 'item_acervo_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => "descritores de itens de acervo",
            'impede_exclusao' => true
        ];

        $va_relacionamentos['idioma_agrupamento_dados_textuais'] = [
            [
                'idioma_agrupamento_dados_textuais'
            ],
            'tabela_intermediaria' => 'agrupamento_dados_textuais',
            'chave_exportada' => 'idioma_codigo',
            'campos_relacionamento' => [
                'idioma_agrupamento_dados_textuais' => 'agrupamento_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'agrupamento',
            'objeto' => 'agrupamento',
            'alias' => "descritores de agrupamentos",
            'impede_exclusao' => true
        ];

        $va_relacionamentos['idioma_contexto_dados_textuais'] = [
            [
                'idioma_contexto_dados_textuais'
            ],
            'tabela_intermediaria' => 'contexto_dados_textuais',
            'chave_exportada' => 'idioma_codigo',
            'campos_relacionamento' => [
                'idioma_contexto_dados_textuais' => 'contexto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'contexto',
            'objeto' => 'contexto',
            'alias' => "descritores de contextos",
            'impede_exclusao' => true
        ];

        $va_relacionamentos['idioma_especie_documental_dados_textuais'] = [
            [
                'idioma_especie_documental_dados_textuais'
            ],
            'tabela_intermediaria' => 'especie_documental_dados_textuais',
            'chave_exportada' => 'idioma_codigo',
            'campos_relacionamento' => [
                'idioma_especie_documental_dados_textuais' => 'especie_documental_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'especie_documental',
            'objeto' => 'especie_documental',
            'alias' => "descritores de espécies documentais",
            'impede_exclusao' => true
        ];

        $va_relacionamentos['idioma_pagina_site_dados_textuais'] = [
            [
                'idioma_pagina_site_dados_textuais'
            ],
            'tabela_intermediaria' => 'pagina_site_dados_textuais',
            'chave_exportada' => 'idioma_codigo',
            'campos_relacionamento' => [
                'idioma_pagina_site_dados_textuais' => 'pagina_site_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'pagina_site',
            'objeto' => 'pagina_site',
            'alias' => "descritores de páginas de sites",
            'impede_exclusao' => true
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["idioma_nome"] = ["html_text_input", "nome" => "idioma_nome", "label" => "Nome", "foco" => true];
        $va_campos_edicao["idioma_sigla"] = ["html_text_input", "nome" => "idioma_sigla", "label" => "Sigla"];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["idioma_nome"] = [
            "html_text_input",
            "nome" => "idioma_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["idioma_codigo"] = ["nome" => "idioma_codigo", "exibir" => false];
        $va_campos_visualizacao["idioma_nome"] = ["nome" => "idioma_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["idioma_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["idioma_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "idioma_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["idioma_sigla"] = ["nome" => "idioma_sigla"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "idioma_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>