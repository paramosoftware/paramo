<?php

class biblioteca extends acervo
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

        $va_atributos['biblioteca_tipo_acervo_codigo'] = [
            'biblioteca_tipo_acervo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_acervo',
            'valor_padrao' => 2
        ];

        $va_atributos['acervo_setor_sistema_codigo'] = [
            'acervo_setor_sistema_codigo',
            'coluna_tabela' => 'setor_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'setor_sistema',
            'valor_padrao' => 2
        ];

        $va_atributos['biblioteca_tipos_materiais'] = [
            'biblioteca_tipos_materiais',
            'coluna_tabela' => 'tipos_materiais',
            'tipo_dado' => 's'
        ];

        return $va_atributos;

    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['biblioteca_tipo_material_codigo'] = [
            [
                'biblioteca_tipo_material_codigo'
            ],
            'tabela_intermediaria' => 'acervo_tipo_material',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'biblioteca_tipo_material_codigo' => 'tipo_material_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'tipo_material',
            'objeto' => 'tipo_material',
            'alias' => 'tipos de materiais'
        ];

        $va_relacionamentos['biblioteca_colecao_codigo'] = [
            ['biblioteca_colecao_codigo'],
            'tabela_intermediaria' => 'colecao',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'biblioteca_colecao_codigo' => [
                    ['codigo'],
                    "atributo" => "colecao_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'colecao',
            'objeto' => 'colecao',
            'tipo' => '1n',
            'alias' => "coleções"
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

        $va_campos_edicao["biblioteca_tipo_material_codigo"] = [
            "html_autocomplete",
            "nome" => ["biblioteca_tipo_material", "biblioteca_tipo_material_codigo"],
            "label" => "Tipos de materiais",
            "objeto" => "tipo_material",
            "atributos" => ["tipo_material_codigo", "tipo_material_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "tipo_material_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "tipo_material_nome"
        ];

        $va_campos_edicao["acervo_descricao"] = [
            "html_text_input",
            "nome" => "acervo_descricao",
            "label" => "Descrição",
            "numero_linhas" => 5
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
            "label" => "Nome da biblioteca",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }


    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["acervo_codigo"] = ["nome" => "acervo_codigo", "exibir" => false];
        $va_campos_visualizacao["biblioteca_tipo_acervo_codigo"] = ["nome" => "biblioteca_tipo_acervo_codigo"];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["acervo_nome"];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = ["acervo_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "acervo_nome" => ["label" => "Nome", "main_field" => true]
        ];

        $va_campos_visualizacao["biblioteca_tipo_material_codigo"] = ["nome" => "biblioteca_tipo_material_codigo"];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "acervo_nome" => ["label" => "Nome", "main_field" => true]
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
            $va_resultado["biblioteca_codigo"] = $va_resultado["acervo_codigo"];
        }

        return $va_resultados;
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = parent::ler($pn_codigo, $ps_visualizacao, $pn_idioma_codigo);

        $va_resultado["biblioteca_codigo"] = $va_resultado["acervo_codigo"];

        return $va_resultado;
    }

    public function salvar_representantes_digitais($ps_campo_nome, $pa_valores, $pa_arquivos, $pb_logar_operacao = false)
    {
        $pa_valores["acervo_codigo"] = $pa_valores["biblioteca_codigo"];
        parent::salvar_representantes_digitais($ps_campo_nome, $pa_valores, $pa_arquivos, $pb_logar_operacao);
    }

}