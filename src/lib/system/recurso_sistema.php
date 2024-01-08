<?php

class recurso_sistema extends objeto_base
{

    function __construct()
    {
        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->registros_filhos["campo_sistema"] = [
            "atributo_relacionamento" => "campo_sistema_recurso_sistema_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["selecao"] = [
            "atributo_relacionamento" => "selecao_recurso_sistema_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["visualizacao"] = [
            "atributo_relacionamento" => "visualizacao_recurso_sistema_codigo",
            "pode_excluir_pai" => true
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "recurso_sistema";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['recurso_sistema_codigo'] = [
            'recurso_sistema_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['recurso_sistema_nome_singular'] = [
            'recurso_sistema_nome_singular',
            'coluna_tabela' => 'nome_singular',
            'tipo_dado' => 's'
        ];

        $va_atributos['recurso_sistema_nome_plural'] = [
            'recurso_sistema_nome_plural',
            'coluna_tabela' => 'nome_plural',
            'tipo_dado' => 's'
        ];

        $va_atributos['recurso_sistema_tabela_banco'] = [
            'recurso_sistema_tabela_banco',
            'coluna_tabela' => 'tabela_banco',
            'tipo_dado' => 's'
        ];

        $va_atributos['recurso_sistema_id'] = [
            'recurso_sistema_id',
            'coluna_tabela' => 'id',
            'tipo_dado' => 's'
        ];

        $va_atributos['recurso_sistema_genero_gramatical_codigo'] = [
            'recurso_sistema_genero_gramatical_codigo',
            'coluna_tabela' => 'genero_gramatical_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'genero_gramatical'
        ];

        $va_atributos['recurso_sistema_hierarquico'] = [
            'recurso_sistema_hierarquico',
            'coluna_tabela' => 'hierarquico',
            'tipo_dado' => 'b'
        ];

        $va_atributos['recurso_sistema_campo_hierarquico_codigo'] = [
            'recurso_sistema_campo_hierarquico_codigo',
            'coluna_tabela' => 'campo_hierarquico_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'campo_sistema'
        ];

        $va_atributos['recurso_sistema_item_acervo'] = [
            'recurso_sistema_item_acervo',
            'coluna_tabela' => 'item_acervo',
            'tipo_dado' => 'b'
        ];

        $va_atributos['recurso_sistema_agrupado_acervo'] = [
            'recurso_sistema_agrupado_acervo',
            'coluna_tabela' => 'agrupado_acervo',
            'tipo_dado' => 'b'
        ];

        $va_atributos['recurso_sistema_habilitado'] = [
            'recurso_sistema_habilitado',
            'coluna_tabela' => 'habilitado',
            'tipo_dado' => 'b'
        ];

        $va_atributos['recurso_sistema_selecionavel'] = [
            'recurso_sistema_selecionavel',
            'coluna_tabela' => 'selecionavel',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['recurso_sistema_campo_sistema_codigo'] = [
            'recurso_sistema_campo_sistema_codigo',
            'tabela_intermediaria' => 'campo_sistema',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_campo_sistema_codigo' => [
                    ['codigo'],
                    "atributo" => "campo_sistema_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'objeto' => 'campo_sistema',
            'tabela_relacionamento' => 'campo_sistema',
            'tipo' => '1n',
            'alias' => 'campos de sistema'
        ];

        $va_relacionamentos['recurso_sistema_grupo_usuario_codigo'] = [
            [
                'recurso_sistema_grupo_usuario_codigo',
                'recurso_sistema_grupo_usuario_ler',
                'recurso_sistema_grupo_usuario_editar',
                'recurso_sistema_grupo_usuario_excluir',
                'recurso_sistema_grupo_usuario_substituir',
                'recurso_sistema_grupo_usuario_editar_lote',
                'recurso_sistema_grupo_usuario_excluir_lote'
            ],
            'tabela_intermediaria' => 'grupo_usuario_recurso_sistema',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' =>
                [
                    'recurso_sistema_grupo_usuario_codigo' => 'grupo_usuario_codigo',
                    'recurso_sistema_grupo_usuario_ler' => 'ler',
                    'recurso_sistema_grupo_usuario_editar' => 'editar',
                    'recurso_sistema_grupo_usuario_excluir' => 'excluir',
                    'recurso_sistema_grupo_usuario_substituir' => 'substituir',
                    'recurso_sistema_grupo_usuario_editar_lote' => 'editar_lote',
                    'recurso_sistema_grupo_usuario_excluir_lote' => 'excluir_lote'
                ],
            'tipos_campos_relacionamento' => ['i', 'b', 'b', 'b', 'b', 'b', 'b'],
            'tabela_relacionamento' => 'grupo_usuario',
            'objeto' => 'grupo_usuario',
            'alias' => 'grupos de usuários'
        ];

        $va_relacionamentos['recurso_sistema_setor_sistema_codigo'] = [
            [
                'recurso_sistema_setor_sistema_codigo'
            ],
            'tabela_intermediaria' => 'setor_sistema_recurso_sistema',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_setor_sistema_codigo' => 'setor_sistema_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'setor_sistema_instituicao',
            'objeto' => 'setor_sistema',
            'alias' => 'setores do sistema'
        ];

        $va_relacionamentos['recurso_sistema_fluxo_codigo'] = [
            [
                'recurso_sistema_fluxo_codigo',
            ],
            'tabela_intermediaria' => 'fluxo_recurso_sistema',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_fluxo_codigo' => 'fluxo_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'fluxo',
            'objeto' => 'fluxo',
            'alias' => 'fluxos'
        ];

        $va_relacionamentos['recurso_sistema_selecao_codigo'] = [
            'recurso_sistema_selecao_codigo',
            'tabela_intermediaria' => 'selecao',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_selecao_codigo' => [
                    ['codigo'],
                    "atributo" => "selecao_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'objeto' => 'selecao',
            'tabela_relacionamento' => 'selecao',
            'tipo' => '1n',
            'alias' => 'seleções'
        ];

        $va_relacionamentos['recurso_sistema_visualizacao_codigo'] = [
            'recurso_sistema_visualizacao_codigo',
            'tabela_intermediaria' => 'visualizacao',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_visualizacao_codigo' => [
                    ['codigo'],
                    "atributo" => "visualizacao_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'objeto' => 'visualizacao',
            'tabela_relacionamento' => 'visualizacao',
            'tipo' => '1n',
            'alias' => 'visualizações'
        ];

        $va_relacionamentos['recurso_sistema_padrao_setor_sistema_codigo'] = [
            'recurso_sistema_padrao_setor_sistema_codigo',
            'tabela_intermediaria' => 'setor_sistema',
            'chave_exportada' => 'recurso_sistema_padrao_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_padrao_setor_sistema_codigo' => [
                    ['codigo'],
                    "atributo" => "setor_sistema_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'objeto' => 'setor_sistema',
            'tabela_relacionamento' => 'setor_sistema',
            'tipo' => '1n',
            'alias' => 'setores do sistema'
        ];

        $va_relacionamentos['recurso_sistema_importacao_codigo'] = [
            'recurso_sistema_importacao_codigo',
            'tabela_intermediaria' => 'importacao',
            'chave_exportada' => 'recurso_sistema_codigo',
            'campos_relacionamento' => [
                'recurso_sistema_importacao_codigo' => [
                    ['codigo'],
                    "atributo" => "importacao_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'objeto' => 'importacao',
            'tabela_relacionamento' => 'importacao',
            'tipo' => '1n',
            'alias' => 'importações'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["recurso_sistema_nome_singular"] = [
            "html_text_input",
            "nome" => "recurso_sistema_nome_singular",
            "label" => "Nome no singular",
            "foco" => true
        ];

        $va_campos_edicao["recurso_sistema_nome_plural"] = ["html_text_input", "nome" => "recurso_sistema_nome_plural", "label" => "Nome no plural"];
        $va_campos_edicao["recurso_sistema_tabela_banco"] = ["html_text_input", "nome" => "recurso_sistema_tabela_banco", "label" => "Tabela no banco de dados"];

        $va_campos_edicao["recurso_sistema_id"] = [
            "html_text_input",
            "nome" => "recurso_sistema_id",
            "label" => "ID",
            "obrigatorio" => true
        ];

        $va_campos_edicao["recurso_sistema_genero_gramatical_codigo"] = [
            "html_combo_input",
            "nome" => "recurso_sistema_genero_gramatical_codigo",
            "label" => "Gênero gramatical",
            "objeto" => "genero_gramatical",
            "atributos" => ["genero_gramatical_codigo", "genero_gramatical_nome"],
            "atributo" => "genero_gramatical_codigo",
            "sem_valor" => false
        ];

        $va_campos_edicao["recurso_sistema_hierarquico"] = [
            "html_checkbox_input",
            "nome" => "recurso_sistema_hierarquico",
            "label" => "É hierárquico"
        ];

        $va_campos_edicao["recurso_sistema_campo_hierarquico_codigo"] = [
            "html_combo_input",
            "nome" => "recurso_sistema_campo_hierarquico_codigo",
            "label" => "Nome do campo hierárquico",
            "objeto" => "campo_sistema",
            "atributos" => ["campo_sistema_codigo", "campo_sistema_nome"],
            "atributo" => "campo_sistema_codigo",
            "sem_valor" => true,
            "dependencia" => [
                "campo" => "recurso_sistema_codigo",
                "atributo" => "campo_sistema_recurso_sistema_codigo"
            ]
        ];

        $va_campos_edicao["recurso_sistema_item_acervo"] = [
            "html_checkbox_input",
            "nome" => "recurso_sistema_item_acervo",
            "label" => "É item de acervo"
        ];

        $va_campos_edicao["recurso_sistema_agrupado_acervo"] = [
            "html_checkbox_input",
            "nome" => "recurso_sistema_agrupado_acervo",
            "label" => "Agrupa-se por acervos"
        ];

        $va_campos_edicao["recurso_sistema_habilitado"] = [
            "html_checkbox_input",
            "nome" => "recurso_sistema_habilitado",
            "label" => "Habilitado"
        ];

        $va_campos_edicao["recurso_sistema_selecionavel"] = [
            "html_checkbox_input",
            "nome" => "recurso_sistema_selecionavel",
            "label" => "Pode ser incluído em seleções?"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["recurso_sistema_nome_plural,recurso_sistema_nome_singular"] = [
            "html_text_input",
            "nome" => "recurso_sistema_nome_plural,recurso_sistema_nome_singular",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["recurso_sistema_codigo"] = ["nome" => "recurso_sistema_codigo", "exibir" => false];
        $va_campos_visualizacao["recurso_sistema_nome_singular"] = ["nome" => "recurso_sistema_nome_singular"];
        $va_campos_visualizacao["recurso_sistema_nome_plural"] = ["nome" => "recurso_sistema_nome_plural"];
        $va_campos_visualizacao["recurso_sistema_id"] = ["nome" => "recurso_sistema_id"];

        $va_campos_visualizacao["recurso_sistema_genero_gramatical_codigo"] = [
            "nome" => "recurso_sistema_genero_gramatical_codigo",
            "formato" => ["campo" => "genero_gramatical_nome"]
        ];

        $va_campos_visualizacao["recurso_sistema_item_acervo"] = [
            "nome" => "recurso_sistema_item_acervo",
            "label" => "É item de acervo"
        ];

        $va_campos_visualizacao["recurso_sistema_agrupado_acervo"] = [
            "nome" => "recurso_sistema_agrupado_acervo",
            "label" => "Agrupa-se por acervos"
        ];

        $va_campos_visualizacao["recurso_sistema_habilitado"] = ["nome" => "recurso_sistema_habilitado"];

        $va_campos_visualizacao["recurso_sistema_selecionavel"] = [
            "nome" => "recurso_sistema_selecionavel",
            "label" => "Pode ser incluído em seleções?"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["recurso_sistema_nome_plural" => "Nome"];

        $va_campos_visualizacao["recurso_sistema_tabela_banco"] = ["nome" => "recurso_sistema_tabela_banco"];
        $va_campos_visualizacao["recurso_sistema_hierarquico"] = ["nome" => "recurso_sistema_hierarquico"];

        $va_campos_visualizacao["recurso_sistema_campo_hierarquico_codigo"] = [
            "nome" => "recurso_sistema_campo_hierarquico_codigo",
            "formato" => ["campo" => "campo_sistema_nome"]
        ];

        $va_campos_visualizacao['recurso_sistema_campo_sistema_codigo'] = [
            "nome" => "recurso_sistema_campo_sistema_codigo"
        ];

        $va_campos_visualizacao['recurso_sistema_grupo_usuario_codigo'] = [
            "nome" => "recurso_sistema_grupo_usuario_codigo"
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["recurso_sistema_nome_plural" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "recurso_sistema_nome_singular" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "recurso_sistema_nome_singular" => ["label" => "Nome", "main_field" => true],
        ];

    }

}

?>