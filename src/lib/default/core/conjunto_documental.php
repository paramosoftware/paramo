<?php

class conjunto_documental extends acervo
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->objeto_pai = "acervo";
        $this->campo_relacionamento_pai = "acervo_codigo";
        $this->excluir_objeto_pai = true;

        $this->inicializar_visualizacoes();

        $this->controlador_acesso = ["instituicao_codigo" => "acervo_codigo_0_acervo_instituicao_codigo"];
    }

    public function inicializar_tabela_banco()
    {
        return "acervo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['acervo_codigo'] = [
            'acervo_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['conjunto_documental_tipo_acervo_codigo'] = [
            'conjunto_documental_tipo_acervo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_acervo',
            'valor_padrao' => 1
        ];

        $va_atributos['acervo_setor_sistema_codigo'] = [
            'acervo_setor_sistema_codigo',
            'coluna_tabela' => 'setor_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'setor_sistema',
            'valor_padrao' => 1
        ];

        $va_atributos['conjunto_documental_historico'] = [
            'conjunto_documental_historico',
            'coluna_tabela' => 'historico',
            'tipo_dado' => 's'
        ];

        $va_atributos['conjunto_documental_procedencia'] = [
            'conjunto_documental_procedencia',
            'coluna_tabela' => 'procedencia',
            'tipo_dado' => 's'
        ];

        $va_atributos['conjunto_documental_natureza_codigo'] = [
            'conjunto_documental_natureza_codigo',
            'coluna_tabela' => 'natureza_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'natureza_acervo'
        ];

        $va_atributos['conjunto_documental_tipo_arquivo_codigo'] = [
            'conjunto_documental_tipo_arquivo_codigo',
            'coluna_tabela' => 'tipo_arquivo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_arquivo'
        ];

        $va_atributos['conjunto_documental_periodo'] = [
            'conjunto_documental_periodo',
            'coluna_tabela' => ['data_inicial' => 'data_inicial', 'data_final' => 'data_final'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['conjunto_documental_situacao_codigo'] = [
            'conjunto_documental_situacao_codigo',
            'coluna_tabela' => 'situacao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'situacao_acervo'
        ];

        $va_atributos['conjunto_documental_localidade_codigo'] = [
            'conjunto_documental_localidade_codigo',
            'coluna_tabela' => 'localidade_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'localidade'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['conjunto_documental_agrupamento_codigo'] = [
            ['conjunto_documental_agrupamento_codigo'],
            'tabela_intermediaria' => 'agrupamento',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'conjunto_documental_agrupamento_codigo' => [
                    ['codigo'],
                    "atributo" => "agrupamento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'agrupamento',
            'objeto' => 'agrupamento',
            'tipo' => '1n',
            'alias' => "agrupamentos"
        ];

        $va_relacionamentos['conjunto_documental_serie_codigo'] = [
            ['conjunto_documental_serie_codigo'],
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'conjunto_documental_serie_codigo' => [
                    ['codigo'],
                    "atributo" => "serie_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'serie',
            'objeto' => 'serie',
            'tipo' => '1n',
            'alias' => "séries"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_instituicao_codigo",
            "label" => "Entidade custodiadora",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "dependencia" => [
                [
                    "campo" => "instituicao_codigo",
                    "atributo" => "instituicao_codigo",
                    "obrigatoria" => false
                ]
            ],
            "sem_valor" => false,
        ];

        $va_campos_edicao["acervo_nome"] = [
            "html_text_input",
            "nome" => "acervo_nome",
            "label" => "Nome"
        ];

        $va_campos_edicao["acervo_identificador"] = [
            "html_text_input",
            "nome" => "acervo_identificador",
            "label" => "ID",
            "foco" => true
        ];

        /*
        $va_campos_edicao["entidade_variacoes_nome_codigo"] = [
            "html_autocomplete",
            "nome" => ["entidade_variacoes_nome", "entidade_variacoes_nome_codigo"],
            "label" => "Nomes paralelos",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];
        */
        
        $va_campos_edicao["acervo_sigla"] = [
            "html_text_input",
            "nome" => "acervo_sigla",
            "label" => "Sigla"
        ];

        $va_campos_edicao["acervo_cor"] = [
            "html_text_input",
            "nome" => "acervo_cor",
            "label" => "Cor",
            "formato" => "color"
        ];

        $va_campos_edicao["conjunto_documental_natureza_codigo"] = [
            "html_combo_input",
            "nome" => "conjunto_documental_natureza_codigo",
            "label" => "Natureza",
            "objeto" => "natureza_acervo",
            "atributos" => ["natureza_acervo_codigo", "natureza_acervo_nome"],
            "atributo" => "natureza_acervo_codigo"
        ];

        $va_campos_edicao["conjunto_documental_tipo_arquivo_codigo"] = [
            "html_combo_input",
            "nome" => "conjunto_documental_tipo_arquivo_codigo",
            "label" => "Tipo de Arquivo",
            "objeto" => "tipo_arquivo",
            "atributos" => ["tipo_arquivo_codigo", "tipo_arquivo_nome"],
            "atributo" => "tipo_arquivo_codigo"
        ];

        $va_campos_edicao["conjunto_documental_periodo"] = [
            "html_date_input",
            "nome" => "conjunto_documental_periodo",
            "label" => "Periodo"
        ];

        $va_campos_edicao["conjunto_documental_situacao_codigo"] = [
            "html_combo_input",
            "nome" => "conjunto_documental_situacao_codigo",
            "label" => "Situação",
            "objeto" => "situacao_acervo",
            "atributos" => ["situacao_acervo_codigo", "situacao_acervo_nome"],
            "atributo" => "situacao_acervo_codigo"
        ];

        $va_campos_edicao["conjunto_documental_localidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["conjunto_documental_localidade", "conjunto_documental_localidade_codigo"],
            "label" => "Localização geográfica",
            "objeto" => "localidade",
            "atributos" => ["localidade_codigo", "localidade_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "localidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "localidade_nome"
        ];

        $va_campos_edicao["conjunto_documental_historico"] = [
            "html_text_input",
            "nome" => "conjunto_documental_historico",
            "label" => "Histórico institucional",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["conjunto_documental_procedencia"] = [
            "html_text_input",
            "nome" => "conjunto_documental_procedencia",
            "label" => "Procedência",
            "numero_linhas" => 5
        ];

        $va_campos_edicao["acervo_descricao"] = [
            "html_text_input",
            "nome" => "acervo_descricao",
            "label" => "Descrição do acervo",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["acervo_estado_organizacao_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_estado_organizacao_codigo",
            "label" => "Estado do conjunto_documental",
            "objeto" => "Estado_Organizacao",
            "atributo" => "estado_organizacao_codigo"
        ];

        /*
        $va_campos_edicao["acervo_quantidade_itens"] = [
            "html_number_input",
            "nome" => "acervo_quantidade_itens",
            "label" => "Quantidade de itens"
        ];
        */

        $va_campos_edicao["acervo_contexto_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_contexto", "acervo_contexto_codigo"],
            "label" => "Contexto",
            "objeto" => "contexto",
            "atributos" => ["contexto_codigo", "contexto_dados_textuais_0_contexto_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "contexto_dados_textuais_0_contexto_nome",
        ];

        $va_campos_edicao["acervo_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_acervo", "acervo_acervo_codigo"],
            "label" => "Relacionamentos com outros acervos",
            "objeto" => "acervo",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "acervo_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => false,
            "dependencia" => [
                [
                    "campo" => "acervo_instituicao_codigo",
                    "atributo" => "acervo_instituicao_codigo"
                ]
            ]
        ];

        $va_campos_edicao["acervo_entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_entidade", "acervo_entidade_codigo"],
            "label" => "Relacionamentos com autoridades",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["acervo_assunto_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_assunto", "acervo_assunto_codigo"],
            "label" => "Relacionamentos com assuntos",
            "objeto" => "assunto",
            "atributos" => ["assunto_codigo", "assunto_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "assunto_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "assunto_nome"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["acervo_codigo_0_acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_codigo_0_acervo_instituicao_codigo",
            "label" => "Instituição",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "sem_valor" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                [
                    "tipo" => "interface",
                    "campo" => "instituicao_codigo",
                    "atributo" => "instituicao_codigo",
                    "obrigatoria" => false
                ]
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["acervo_nome"] = [
            "html_text_input",
            "nome" => "acervo_nome",
            "label" => "Nome do fundo/coleção",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["acervo_codigo"] = [
            "nome" => "acervo_codigo",
            "exibir" => false,
            "id_field" => true
        ];

        $va_campos_visualizacao["conjunto_documental_tipo_acervo_codigo"] = ["nome" => "conjunto_documental_tipo_acervo_codigo"];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["acervo_nome" => "Nome"];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = ["acervo_nome" => "Nome do arquivo"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "acervo_nome" => ["label" => "Nome", "main_field" => true],
            "acervo_instituicao_codigo" => "Instituição"
        ];

        $va_campos_visualizacao["conjunto_documental_natureza_codigo"] = ["nome" => "conjunto_documental_natureza_codigo"];
        $va_campos_visualizacao["conjunto_documental_tipo_arquivo_codigo"] = ["nome" => "conjunto_documental_tipo_arquivo_codigo"];
        $va_campos_visualizacao["conjunto_documental_periodo"] = ["nome" => "conjunto_documental_periodo"];
        $va_campos_visualizacao["conjunto_documental_situacao_codigo"] = ["nome" => "conjunto_documental_situacao_codigo"];
        $va_campos_visualizacao["conjunto_documental_localidade_codigo"] = ["nome" => "conjunto_documental_localidade_codigo"];
        $va_campos_visualizacao["conjunto_documental_historico"] = ["nome" => "conjunto_documental_historico"];
        $va_campos_visualizacao["conjunto_documental_procedencia"] = ["nome" => "conjunto_documental_procedencia"];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "acervo_nome" => ["label" => "Nome do arquivo", "main_field" => true],
            "acervo_instituicao_codigo" => "Instituição",
            "acervo_setor_sistema_codigo" => "Setor",
            "acervo_sigla" => "Sigla",
        ];
    }

    public function ler_numero_registros($pa_filtros_busca = null, $pa_log_info = null, $pb_retornar_ramos_inferiores = true)
    {
        if (isset($pa_filtros_busca["instituicao_codigo"]))
            $pa_filtros_busca["acervo_codigo_0_acervo_instituicao_codigo"] = $pa_filtros_busca["instituicao_codigo"];

        return parent::ler_numero_registros($pa_filtros_busca, $pa_log_info);
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        if (isset($pa_filtros_busca["instituicao_codigo"]))
            $pa_filtros_busca["acervo_codigo_0_acervo_instituicao_codigo"] = $pa_filtros_busca["instituicao_codigo"];

        $va_resultados = parent::ler_lista($pa_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info, $pn_idioma_codigo);

        foreach ($va_resultados as &$va_resultado) {
            $va_resultado["conjunto_documental_codigo"] = $va_resultado["acervo_codigo"];
        }

        return $va_resultados;
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = parent::ler($pn_codigo, $ps_visualizacao, $pn_idioma_codigo);

        $va_resultado["conjunto_documental_codigo"] = $va_resultado["acervo_codigo"];

        return $va_resultado;
    }

    public function salvar_representantes_digitais($ps_campo_nome, $pa_valores, $pa_arquivos, $pb_logar_operacao = false)
    {
        $pa_valores["acervo_codigo"] = $pa_valores["conjunto_documental_codigo"];
        parent::salvar_representantes_digitais($ps_campo_nome, $pa_valores, $pa_arquivos, $pb_logar_operacao);
    }

}

?>