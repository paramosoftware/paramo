<?php

class entidade extends objeto_base
{
    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->hierarquico = true;
        $this->campo_hierarquico = "entidade_principal_codigo";

        $this->registros_filhos["editora"] = [
            "atributo_relacionamento" => "entidade_codigo",
            "pode_excluir_pai" => false
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "entidade";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['entidade_codigo'] = [
            'entidade_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['entidade_nome'] = [
            'entidade_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['entidade_data_nascimento'] = [
            'entidade_data_nascimento',
            'coluna_tabela' => ['data_inicial' => 'data_nascimento_inicial', 'data_final' => 'Data_Nascimento_Final', 'presumido' => 'Data_Nascimento_Presumida', 'sem_data' => 'data_nascimento_sem_data'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['entidade_local_nascimento_codigo'] = [
            'entidade_local_nascimento_codigo',
            'coluna_tabela' => 'local_nascimento_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'localidade'
        ];

        $va_atributos['entidade_data_morte'] = [
            'entidade_data_morte',
            'coluna_tabela' => ['data_inicial' => 'data_morte_inicial', 'data_final' => 'Data_Morte_Final', 'presumido' => 'Data_Morte_Presumida', 'sem_data' => 'data_morte_sem_data'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['entidade_local_morte_codigo'] = [
            'entidade_local_morte_codigo',
            'coluna_tabela' => 'local_morte_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'localidade'
        ];

        $va_atributos['entidade_biografia'] = [
            'entidade_biografia',
            'coluna_tabela' => 'biografia',
            'tipo_dado' => 's'
        ];

        $va_atributos['entidade_principal_codigo'] = [
            'entidade_principal_codigo',
            'coluna_tabela' => 'entidade_principal_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'entidade'
        ];

        $va_atributos['entidade_raca_codigo'] = [
            'entidade_raca_codigo',
            'coluna_tabela' => 'raca_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'raca'
        ];

        $va_atributos['entidade_genero_codigo'] = [
            'entidade_genero_codigo',
            'coluna_tabela' => 'genero_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'genero'
        ];

        $va_atributos['entidade_tipo_codigo'] = [
            'entidade_tipo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_entidade'
        ];

        $va_atributos['entidade_telefone'] = [
            'entidade_telefone',
            'coluna_tabela' => 'telefone',
            'tipo_dado' => 's'
        ];

        $va_atributos['entidade_email'] = [
            'entidade_email',
            'coluna_tabela' => 'email',
            'tipo_dado' => 's'
        ];

        $va_atributos['entidade_site'] = [
            'entidade_site',
            'coluna_tabela' => 'site',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['entidade_item_acervo_codigo'] = [
            ['entidade_item_acervo_codigo', 'entidade_item_acervo_funcao'],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => [
                'entidade_item_acervo_codigo' => 'item_acervo_codigo',
                'entidade_item_acervo_funcao' => 'funcao_entidade'
            ],
            'tipos_campos_relacionamento' => ['i', 's'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'autoridades'
        ];

        $va_relacionamentos['entidade_variacoes_nome_codigo'] = [
            ['entidade_variacoes_nome_codigo'],
            'tabela_intermediaria' => 'entidade',
            'chave_exportada' => 'entidade_principal_codigo',
            'campos_relacionamento' => [
                'entidade_variacoes_nome_codigo' => [
                    ['codigo'],
                    "atributo" => "entidade_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'tipo' => '1n',
            'alias' => 'variações do nome'
        ];

        $va_relacionamentos['entidade_agrupamento_codigo'] = [
            ['entidade_agrupamento_codigo'],
            'tabela_intermediaria' => 'agrupamento_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => ['entidade_agrupamento_codigo' => 'agrupamento_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'agrupamento',
            'objeto' => 'agrupamento',
            'alias' => "agrupamentos relacionados"
        ];

        $va_relacionamentos['entidade_localidade_codigo'] = [
            ['entidade_localidade_codigo'],
            'tabela_intermediaria' => 'entidade_localidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => ['entidade_localidade_codigo' => 'localidade_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'localidade',
            'objeto' => 'localidade',
            'alias' => 'localidades'
        ];

        $va_relacionamentos['entidade_entidade_codigo'] = [
            [
                'entidade_entidade_codigo',
                'entidade_entidade_tipo_relacao_codigo'
            ],
            'tabela_intermediaria' => 'entidade_entidade',
            'chave_exportada' =>
                [
                    'entidade_1_codigo',
                    'entidade_2_codigo'
                ],
            'campos_relacionamento' =>
                [
                    'entidade_entidade_codigo' => [
                        [
                            'entidade_2_codigo',
                            'entidade_1_codigo'
                        ],
                        "atributo" => "entidade_codigo"
                    ],
                    'entidade_entidade_tipo_relacao_codigo' => ['tipo_relacao_codigo', 1]
                ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'entidades relacionadas'
        ];

        $va_relacionamentos['entidade_entidade_membro_codigo'] = [
            [
                'entidade_entidade_membro_codigo',
                'entidade_entidade_tipo_relacao_codigo'
            ],
            'tabela_intermediaria' => 'entidade_entidade',
            'chave_exportada' => 'entidade_1_codigo',
            'campos_relacionamento' => [
                'entidade_entidade_membro_codigo' => [
                    ['entidade_2_codigo'],
                    "atributo" => "entidade_codigo"
                ],
                'entidade_entidade_tipo_relacao_codigo' => ['tipo_relacao_codigo', 2]
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => "membros"
        ];

        $va_relacionamentos['entidade_editora_codigo'] = [
            ['entidade_editora_codigo'],
            'tabela_intermediaria' => 'editora',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => [
                'entidade_editora_codigo' => [
                    ['codigo'],
                    "atributo" => "editora_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'editora',
            'objeto' => 'editora',
            'tipo' => '1n',
            'alias' => "editoras"
        ];


        $va_relacionamentos['entidade_evento_codigo'] = [
            ['entidade_evento_codigo'],
            'tabela_intermediaria' => 'evento_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => ['entidade_evento_codigo' => 'evento_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'evento',
            'objeto' => 'evento',
            'alias' => "Eventos"
        ];

        $va_relacionamentos['entidade_endereco'] = [
            [
                'endereco_logradouro',
                'endereco_bairro',
                'endereco_localidade_codigo',
            ],
            'tabela_intermediaria' => 'entidade_endereco',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => [
                'endereco_logradouro' => 'logradouro',
                'endereco_bairro' => 'bairro',
                'endereco_localidade_codigo' => 'localidade_codigo'
            ],
            'tipos_campos_relacionamento' => ['s', 's', 'i'],
            'alias' => 'endereços'
        ];

        $va_relacionamentos['entidade_colecao_codigo'] = [
            ['entidade_colecao_codigo'],
            'tabela_intermediaria' => 'colecao',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => [
                'entidade_colecao_codigo' => [
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

        $va_relacionamentos['entidade_acervo_relacionado_codigo'] = [
            ['entidade_acervo_relacionado_codigo'],
            'tabela_intermediaria' => 'acervo_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => ['entidade_acervo_relacionado_codigo' => 'acervo_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'alias' => "acervos relacionados"
        ];

        $va_relacionamentos['entidade_colecao_relacionada_codigo'] = [
            ['entidade_colecao_relacionada_codigo'],
            'tabela_intermediaria' => 'colecao_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => ['entidade_colecao_relacionada_codigo' => 'colecao_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'colecao',
            'objeto' => 'colecao',
            'alias' => "coleções relacionadas"
        ];

        $va_relacionamentos['entidade_incorporacao_codigo'] = [
            [
                'entidade_incorporacao_codigo'
            ],
            'tabela_intermediaria' => 'incorporacao_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => [
                'entidade_incorporacao_codigo' => 'incorporacao_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'incorporacao',
            'objeto' => 'incorporacao',
            'alias' => 'incorporações de itens de acervo'
        ];

        $va_relacionamentos['entidade_serie_codigo'] = [
            ['entidade_serie_codigo'],
            'tabela_intermediaria' => 'serie_entidade',
            'chave_exportada' => 'entidade_codigo',
            'campos_relacionamento' => ['entidade_serie_codigo' => 'serie_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'serie',
            'objeto' => 'serie',
            'alias' => "séries relacionadas"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["entidade_nome"] = [
            "html_text_input",
            "nome" => "entidade_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["entidade_variacoes_nome_codigo"] = [
            "html_autocomplete",
            "nome" => ["entidade_variacoes_nome", "entidade_variacoes_nome_codigo"],
            "label" => "Formas paralelas de nome",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["entidade_tipo_codigo"] = [
            "html_combo_input",
            "nome" => "entidade_tipo_codigo",
            "label" => "Tipo de personalidade",
            "objeto" => "tipo_entidade",
            "atributo" => "tipo_entidade_codigo",
            "atributos" => ["tipo_entidade_codigo", "tipo_entidade_nome"],
            "sem_valor" => true,
            "controlar_exibicao" => ["entidade_entidade_membro_codigo"]
        ];

        $va_campos_edicao["entidade_data_nascimento"] = [
            "html_date_input",
            "nome" => "entidade_data_nascimento",
            "label" => "Data de nascimento / fundação"
        ];

        $va_campos_edicao["entidade_data_morte"] = [
            "html_date_input",
            "nome" => "entidade_data_morte",
            "label" => "Data de falecimento / extinção"
        ];

        $va_campos_edicao["entidade_local_nascimento_codigo"] = [
            "html_autocomplete",
            "nome" => ['entidade_local_nascimento', 'entidade_local_nascimento_codigo'],
            "label" => "Local de nascimento / sede",
            "objeto" => "Localidade",
            "atributos" => ["localidade_codigo", "localidade_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "localidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "localidade_nome"
        ];

        $va_campos_edicao["entidade_local_morte_codigo"] = [
            "html_autocomplete",
            "nome" => ['entidade_local_morte', 'entidade_local_morte_codigo'],
            "label" => "Local de falecimento",
            "objeto" => "Localidade",
            "atributos" => ["localidade_codigo", "localidade_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "localidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "localidade_nome"
        ];

        $va_campos_edicao["entidade_genero_codigo"] = [
            "html_combo_input",
            "nome" => "entidade_genero_codigo",
            "label" => "Gênero",
            "objeto" => "genero",
            "atributo" => "genero_codigo",
            "atributos" => ["genero_codigo", "genero_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["entidade_raca_codigo"] = [
            "html_combo_input",
            "nome" => "entidade_raca_codigo",
            "label" => "Raça",
            "objeto" => "raca",
            "atributo" => "raca_codigo",
            "atributos" => ["raca_codigo", "raca_nome"],
            "sem_valor" => true
        ];

        $va_campos_edicao["entidade_endereco"] = [
            "html_multi_itens_input",
            "nome" => "entidade_endereco",
            "label" => "Endereço",
            "numero_maximo_itens" => 1,
            "numero_itens_inicial" => 1,
            "subcampos" => [
                "endereco_logradouro" => [
                    "html_text_input",
                    "nome" => "endereco_logradouro",
                    "label" => "Endereço",
                    "campo_pai" => "entidade_endereco",
                    "numero_linhas" => 2
                ],
                "endereco_localidade_codigo" => [
                    "html_autocomplete",
                    "nome" => [
                        "endereco_localidade",
                        "endereco_localidade_codigo"
                    ],
                    "label" => "Cidade",
                    "objeto" => "localidade",
                    "atributos" => ["localidade_codigo", "localidade_nome"],
                    "multiplos_valores" => false,
                    "procurar_por" => "localidade_nome",
                    "visualizacao" => "lista",
                    "permitir_cadastro" => true,
                    "campo_salvar" => "localidade_nome",
                    "campo_pai" => "entidade_endereco"
                ],
                "endereco_bairro" => [
                    "html_text_input",
                    "nome" => "endereco_bairro",
                    "label" => "Bairro",
                    "campo_pai" => "entidade_endereco"
                ]
            ]
        ];

        $va_campos_edicao["entidade_email"] = [
            "html_text_input",
            "nome" => "entidade_email",
            "label" => "E-mail"
        ];

        $va_campos_edicao["entidade_telefone"] = [
            "html_text_input",
            "nome" => "entidade_telefone",
            "label" => "Telefone"
        ];

        $va_campos_edicao["entidade_site"] = [
            "html_text_input",
            "nome" => "entidade_site",
            "label" => "Site"
        ];

        $va_campos_edicao["entidade_entidade_membro_codigo"] = [
            "html_autocomplete",
            "nome" => ['entidade_entidade_membro', 'entidade_entidade_membro_codigo'],
            "label" => "Membros",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome",
            "regra_exibicao" => ["entidade_tipo_codigo" => 3]
        ];

        $va_campos_edicao["entidade_biografia"] = [
            "html_text_input",
            "nome" => "entidade_biografia",
            "label" => "Mini-Biografia",
            "numero_linhas" => 10
        ];

        $va_campos_edicao["entidade_entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ['entidade_entidade', 'entidade_entidade_codigo'],
            "label" => "Relacionamento com autoridades",
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

        $va_filtros_navegacao["entidade_nome,entidade_principal_codigo_0_entidade_nome,entidade_variacoes_nome_codigo_0_entidade_nome"] = [
            "html_text_input",
            "nome" => "entidade_nome,entidade_principal_codigo_0_entidade_nome,entidade_variacoes_nome_codigo_0_entidade_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "hierarquia" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["entidade_codigo"] = ["nome" => "entidade_codigo", "exibir" => false];

        $va_campos_visualizacao["entidade_nome"] = [
            "nome" => "entidade_nome",
            "label" => "Nome",
            "label_field" => true
        ];

        $va_campos_visualizacao["entidade_principal_codigo"] = [
            "nome" => "entidade_principal_codigo",
            "formato" => [
                "campo" => "entidade_nome",
                "hierarquia" => "entidade_principal_codigo",
                "separador" => " > "
            ],
            "label" => "Variações do nome"
        ];

        $va_campos_visualizacao["entidade_data_nascimento"] = ["nome" => "entidade_data_nascimento", "formato" => ["data" => "ano"],
            "label" => "Data de nascimento"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["entidade_nome" => "Nome"];

        $va_campos_visualizacao["entidade_local_nascimento_codigo"] = [
            "nome" => "entidade_local_nascimento_codigo",
            "formato" => ["campo" => "localidade_nome"],
            "label" => "Local de nascimento"
        ];

        $va_campos_visualizacao["entidade_data_morte"] = ["nome" => "entidade_data_morte", "formato" => ["data" => "ano"],
            "label" => "Data do falecimento"
        ];

        $va_campos_visualizacao["entidade_local_morte_codigo"] = [
            "nome" => "entidade_local_morte_codigo",
            "formato" => ["campo" => "localidade_nome"],
            "label" => "Local do falecimento"
        ];

        $this->visualizacoes["navegacao"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));
        $this->visualizacoes["navegacao"]["order_by"] = ["entidade_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "entidade_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["entidade_variacoes_nome_codigo"] = [
            "nome" => "entidade_variacoes_nome_codigo",
            "formato" => [
                "campo" => "entidade_nome",
            ],
            "label" => "Variações do nome"
        ];

        $va_campos_visualizacao["entidade_entidade_codigo"] = [
            "nome" => "entidade_entidade_codigo",
            "formato" => ["campo" => "entidade_nome"],
        ];

        $va_campos_visualizacao["entidade_genero_codigo"] = [
            "nome" => "entidade_genero_codigo",
            "formato" => [
                "campo" => "genero_nome"
            ],
            "label" => "Gênero"
        ];

        $va_campos_visualizacao["entidade_raca_codigo"] = [
            "nome" => "entidade_raca_codigo",
            "formato" => [
                "campo" => "raca_nome"
            ],
            "label" => "Raça"
        ];

        $va_campos_visualizacao["entidade_biografia"] = ["nome" => "entidade_biografia"];
        $va_campos_visualizacao["entidade_tipo_codigo"] = ["nome" => "entidade_tipo_codigo"];
        $va_campos_visualizacao["entidade_endereco"] = ["nome" => "entidade_endereco"];
        $va_campos_visualizacao["entidade_email"] = ["nome" => "entidade_email"];
        $va_campos_visualizacao["entidade_telefone"] = ["nome" => "entidade_telefone"];
        $va_campos_visualizacao["entidade_site"] = ["nome" => "entidade_site"];
        $va_campos_visualizacao["entidade_entidade_membro_codigo"] = ["nome" => "entidade_entidade_membro_codigo"];

        $va_campos_visualizacao["entidade_localidade_codigo"] = [
            "nome" => "entidade_localidade_codigo",
            "formato" => ["campo" => "localidade_nome"],
        ];

        $this->visualizacoes["ficha"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "entidade_nome" => ["label" => "Nome", "main_field" => true],
            "entidade_variacoes_nome_codigo" => "Variações do nome",
            "entidade_local_nascimento_codigo" => "Local de nascimento",
            "entidade_data_nascimento" => "Data de nascimento",
            "entidade_local_morte_codigo" => "Local da morte",
            "entidade_data_morte" => "Data da morte",
            "entidade_biografia" => "Resumo biográfico"
        ];
    }

    public function salvar($pa_valores, $pb_logar_operacao = true, $pn_idioma_codigo = 1, $pb_salvar_objeto_pai = true, $ps_id_objeto_filho = '', $pb_sobrescrever = true)
    {
        if (get_class($this) != "entidade")
            return parent::salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo);

        //Coloca o nome na ordem direta, se estiver na ordem inversa
        if (isset($pa_valores["entidade_nome"]) && false) {
            $vs_entidade_nome = trim($pa_valores["entidade_nome"]);

            $vs_nome_ordem_direta = "";
            $vn_entidade_principal_codigo = "";
            if (strrpos($vs_entidade_nome, ",")) {
                $vn_posicao_virgula = strpos($vs_entidade_nome, ",");
                $vn_tamanho_string = strlen($vs_entidade_nome);

                if ($vn_posicao_virgula > 0)
                    $vs_nome_ordem_direta = substr($vs_entidade_nome, ($vn_posicao_virgula + 2), $vn_tamanho_string - $vn_posicao_virgula) . " " . substr($vs_entidade_nome, 0, $vn_posicao_virgula);

                // Antes de salvar, tem que ver se já existe
                $va_parametros_leitura["entidade_nome"] = $vs_nome_ordem_direta;

                //var_dump($va_parametros_leitura);
                $va_entidade_ordem_direta = $this->ler_lista($va_parametros_leitura, "lista", 1, 1);

                //var_dump($va_entidade_ordem_direta); exit();
                if (count($va_entidade_ordem_direta)) {
                    $va_entidade_ordem_direta = $va_entidade_ordem_direta[0];
                    $vn_entidade_principal_codigo = $va_entidade_ordem_direta["entidade_codigo"];
                } else {
                    $pa_valores["entidade_nome"] = $vs_nome_ordem_direta;
                    $vn_entidade_principal_codigo = parent::salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo);
                }
            }

            $pa_valores["entidade_nome"] = $vs_entidade_nome;

            if ($vn_entidade_principal_codigo)
                $pa_valores["entidade_principal_codigo"] = $vn_entidade_principal_codigo;
        }

        return parent::salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo);
    }

}

?>