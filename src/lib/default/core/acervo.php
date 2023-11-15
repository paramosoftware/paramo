<?php

class acervo extends entidade
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->registros_filhos["item_acervo"] = [
            "atributo_relacionamento" => "item_acervo_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["agrupamento"] = [
            "atributo_relacionamento" => "agrupamento_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["serie"] = [
            "atributo_relacionamento" => "serie_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["unidade_armazenamento"] = [
            "atributo_relacionamento" => "unidade_armazenamento_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["colecao"] = [
            "atributo_relacionamento" => "colecao_acervo_codigo",
            "pode_excluir_pai" => true
        ];

        $this->inicializar_visualizacoes();

        $this->controlador_acesso = ["instituicao_codigo" => "acervo_instituicao_codigo"];
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

        $va_atributos['acervo_nome'] = [
            'acervo_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['acervo_instituicao_codigo'] = [
            'acervo_instituicao_codigo',
            'coluna_tabela' => 'instituicao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'instituicao'
        ];

        $va_atributos['acervo_setor_sistema_codigo'] = [
            'acervo_setor_sistema_codigo',
            'coluna_tabela' => 'setor_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'setor_sistema'
        ];

        $va_atributos['acervo_identificador'] = [
            'acervo_identificador',
            'coluna_tabela' => 'identificador',
            'tipo_dado' => 's'
        ];

        $va_atributos['acervo_sigla'] = [
            'acervo_sigla',
            'coluna_tabela' => 'Sigla',
            'tipo_dado' => 's'
        ];

        $va_atributos['acervo_cor'] = [
            'acervo_cor',
            'coluna_tabela' => 'cor',
            'tipo_dado' => 's'
        ];

        $va_atributos['acervo_descricao'] = [
            'acervo_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['acervo_estado_organizacao_codigo'] = [
            'acervo_estado_organizacao_codigo',
            'coluna_tabela' => 'Estado_Organizacao_Codigo',
            'tipo_dado' => 'i',
            'objeto' => 'estado_organizacao'
        ];

        $va_atributos['acervo_quantidade_itens'] = [
            'acervo_quantidade_itens',
            'coluna_tabela' => 'quantidade_itens',
            'tipo_dado' => 'i'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['acervo_agrupamento_codigo'] = [
            ['acervo_agrupamento_codigo'],
            'tabela_intermediaria' => 'agrupamento',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_agrupamento_codigo' => [
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

        $va_relacionamentos['acervo_contexto_relacionado_codigo'] = [
            [
                'acervo_contexto_relacionado_codigo'
            ],
            'tabela_intermediaria' => 'acervo_contexto',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_contexto_relacionado_codigo' => 'contexto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'contexto',
            'objeto' => 'contexto',
            'alias' => 'contextos relacionados'
        ];

        $va_relacionamentos['acervo_acervo_codigo'] = [
            ['acervo_acervo_codigo'],
            'tabela_intermediaria' => 'acervo_acervo',
            'chave_exportada' =>
                [
                    'acervo_1_codigo',
                    'acervo_2_codigo'
                ],
            'campos_relacionamento' =>
                [
                    'acervo_acervo_codigo' => [
                        [
                            'acervo_2_codigo',
                            'acervo_1_codigo'
                        ],
                        "atributo" => "acervo_codigo"
                    ]
                ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'alias' => 'acervos relacionados'
        ];

        $va_relacionamentos['acervo_entidade_codigo'] = [
            [
                'acervo_entidade_codigo'
            ],
            'tabela_intermediaria' => 'acervo_entidade',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_entidade_codigo' => 'entidade_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'autoridades'
        ];

        $va_relacionamentos['acervo_assunto_codigo'] = [
            [
                'acervo_assunto_codigo'
            ],
            'tabela_intermediaria' => 'acervo_assunto',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_assunto_codigo' => 'assunto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'assunto',
            'objeto' => 'assunto',
            'alias' => 'assuntos'
        ];

        $va_relacionamentos['acervo_contexto_codigo'] = [
            ['acervo_contexto_codigo'],
            'tabela_intermediaria' => 'contexto',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_contexto_codigo' => [
                    ['codigo'],
                    "atributo" => "contexto_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'contexto',
            'objeto' => 'contexto',
            'tipo' => '1n',
            'alias' => "contextos inferiores"
        ];

        $va_relacionamentos['acervo_serie_codigo'] = [
            ['acervo_serie_codigo'],
            'tabela_intermediaria' => 'serie',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_serie_codigo' => [
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

        $va_relacionamentos['acervo_item_acervo_codigo'] = [
            ['acervo_item_acervo_codigo'],
            'tabela_intermediaria' => 'item_acervo',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_item_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "item_acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'tipo' => '1n',
            'alias' => "itens de acervo"
        ];

        $va_relacionamentos['acervo_unidade_armazenamento_codigo'] = [
            ['acervo_unidade_armazenamento_codigo'],
            'tabela_intermediaria' => 'unidade_armazenamento',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => [
                'acervo_unidade_armazenamento_codigo' => [
                    ['codigo'],
                    "atributo" => "unidade_armazenamento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'unidade_armazenamento',
            'objeto' => 'unidade_armazenamento',
            'tipo' => '1n',
            'alias' => "unidades de armazenamento"
        ];

        $va_relacionamentos['acervo_usuario_codigo'] = [
            'acervo_usuario_codigo',
            'tabela_intermediaria' => 'usuario_acervo',
            'chave_exportada' => 'acervo_codigo',
            'campos_relacionamento' => ['acervo_usuario_codigo' => 'usuario_codigo'],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'usuario',
            'objeto' => 'usuario',
            'alias' => 'usuários'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["acervo_identificador"] = [
            "html_text_input",
            "nome" => "acervo_identificador",
            "label" => "ID",
            "foco" => true
        ];

        $va_campos_edicao["acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_instituicao_codigo",
            "label" => "Entidade custodiadora",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "atributo_obrigatorio" => true
        ];

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

        $va_campos_edicao["acervo_sigla"] = [
            "html_text_input",
            "nome" => "acervo_sigla",
            "label" => "Sigla"
        ];

        $va_campos_edicao["acervo_natureza_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_natureza_acervo_codigo",
            "label" => "Natureza",
            "objeto" => "natureza_acervo",
            "atributos" => ["natureza_acervo_codigo", "natureza_acervo_nome"],
            "atributo" => "natureza_acervo_codigo"
        ];

        

        $va_campos_edicao["acervo_tipo_arquivo_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_tipo_arquivo_codigo",
            "label" => "Tipo de Arquivo",
            "objeto" => "tipo_arquivo",
            "atributos" => ["tipo_arquivo_codigo", "tipo_arquivo_nome"],
            "atributo" => "tipo_arquivo_codigo"
        ];

        $va_campos_edicao["acervo_periodo"] = [
            "html_date_input",
            "nome" => "acervo_periodo",
            "label" => "Periodo"
        ];

        $va_campos_edicao["acervo_situacao_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_situacao_codigo",
            "label" => "Situação",
            "objeto" => "situacao_acervo",
            "atributos" => ["situacao_acervo_codigo", "situacao_acervo_nome"],
            "atributo" => "situacao_acervo_codigo"
        ];

        $va_campos_edicao["acervo_localidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_localidade", "acervo_localidade_codigo"],
            "label" => "Localização geográfica",
            "objeto" => "localidade",
            "atributos" => ["localidade_codigo", "localidade_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "localidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "localidade_nome"
        ];

        $va_campos_edicao["acervo_historico"] = [
            "html_text_input",
            "nome" => "acervo_historico",
            "label" => "Histórico institucional",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["acervo_procedencia"] = [
            "html_text_input",
            "nome" => "acervo_procedencia",
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
            "label" => "Estado do acervo",
            "objeto" => "Estado_Organizacao",
            "atributo" => "estado_organizacao_codigo"
        ];

        $va_campos_edicao["acervo_quantidade_itens"] = [
            "html_number_input",
            "nome" => "acervo_quantidade_itens",
            "label" => "Quantidade de itens"
        ];

        $va_campos_edicao["acervo_contexto_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_contexto", "acervo_contexto_codigo"],
            "label" => "Contexto",
            "objeto" => "contexto",
            "atributos" => ["contexto_codigo", "contexto_dados_textuais_0_contexto_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "contexto_dados_textuais_0_contexto_nome"
        ];

        $va_campos_edicao["acervo_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["acervo_acervo", "acervo_acervo_codigo"],
            "label" => "Relacionamentos com outros acervos",
            "objeto" => "acervo",
            "atributos" => ["acervo_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_codigo_0_entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
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

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["acervo_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "acervo_instituicao_codigo",
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
            "label" => "Nome do acervo",
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

        $va_campos_visualizacao["acervo_setor_sistema_codigo"] = [
            "nome" => "acervo_setor_sistema_codigo",
            "formato" => ["campo" => "setor_sistema_nome"]
        ];

        $va_campos_visualizacao["acervo_nome"] = ["nome" => "acervo_nome"];
        $va_campos_visualizacao["acervo_sigla"] = ["nome" => "acervo_sigla"];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["acervo_nome" => "Nome"];

        $va_campos_visualizacao["acervo_instituicao_codigo"] = [
            "nome" => "acervo_instituicao_codigo",
            "formato" => ["campo" => "instituicao_nome"]
        ];

        $va_campos_visualizacao["acervo_identificador"] = ["nome" => "acervo_identificador"];

        $va_campos_visualizacao["acervo_cor"] = ["nome" => "acervo_cor"];
        $va_campos_visualizacao["representante_digital_codigo"] = ["nome" => "representante_digital_codigo"];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = ["acervo_nome" => "Nome do acervo"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "acervo_nome" => "Nome do acervo",
            "acervo_setor_sistema_codigo" => "Setor",
            "acervo_sigla" => "Sigla",
        ];

        $va_campos_visualizacao["acervo_contexto_codigo"] = ["nome" => "acervo_contexto_codigo"];
        $va_campos_visualizacao["acervo_acervo_codigo"] = ["nome" => "acervo_acervo_codigo"];
        $va_campos_visualizacao["acervo_entidade_codigo"] = ["nome" => "acervo_entidade_codigo"];
        $va_campos_visualizacao["acervo_assunto_codigo"] = ["nome" => "acervo_assunto_codigo"];
        $va_campos_visualizacao["acervo_descricao"] = ["nome" => "acervo_descricao"];

        $va_campos_visualizacao["acervo_estado_organizacao_codigo"] = [
            "nome" => "acervo_estado_organizacao_codigo",
            "formato" => ["campo" => "estado_organizacao_nome"]
        ];

        $va_campos_visualizacao["acervo_quantidade_itens"] = ["nome" => "acervo_quantidade_itens"];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "acervo_nome" => ["label" => "Nome do acervo", "main_field" => true],
            "acervo_setor_sistema_codigo" => "Setor",
            "acervo_sigla" => "Sigla",
        ];
    }

}

?>