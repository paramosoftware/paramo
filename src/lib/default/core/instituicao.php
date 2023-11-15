<?php
class instituicao extends entidade
{
    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->registros_filhos["acervo"] = [
            "atributo_relacionamento" => "acervo_instituicao_codigo",
            "pode_excluir_pai" => true
        ];

        $this->controlador_acesso = ["instituicao_codigo" => "instituicao_codigo"];
    }

    public function inicializar_tabela_banco()
    {
        return "instituicao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['instituicao_codigo'] = [
            'instituicao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['instituicao_nome'] = [
            'instituicao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['instituicao_admin'] = [
            'instituicao_admin',
            'coluna_tabela' => 'admin',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['instituicao_acervo_codigo'] = [
            ['instituicao_acervo_codigo'],
            'tabela_intermediaria' => 'acervo',
            'chave_exportada' => 'instituicao_codigo',
            'campos_relacionamento' => [
                'instituicao_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'tipo' => '1n',
            'alias' => "acervos"
        ];

        $va_relacionamentos['instituicao_local_armazenamento_codigo'] = [
            ['instituicao_local_armazenamento_codigo'],
            'tabela_intermediaria' => 'local_armazenamento',
            'chave_exportada' => 'instituicao_codigo',
            'campos_relacionamento' => [
                'instituicao_local_armazenamento_codigo' => [
                    ['codigo'],
                    "atributo" => "local_armazenamento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'local_armazenamento',
            'objeto' => 'local_armazenamento',
            'tipo' => '1n',
            'alias' => "locais de armazenamento"
        ];

        $va_relacionamentos['instituicao_usuario_codigo'] = [
            ['instituicao_usuario_codigo'],
            'tabela_intermediaria' => 'usuario',
            'chave_exportada' => 'instituicao_codigo',
            'campos_relacionamento' => [
                'instituicao_usuario_codigo' => [
                    ['codigo'],
                    "atributo" => "usuario_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'usuario',
            'objeto' => 'usuario',
            'tipo' => '1n',
            'alias' => "usuários"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["instituicao_nome"] = [
            "html_text_input",
            "nome" => "instituicao_nome",
            "label" => "Nome"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "instituicao_codigo",
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
            "css-class" => "form-select",
            "nao_exibir" => true
        ];

        $va_filtros_navegacao["instituicao_nome"] = [
            "html_text_input",
            "nome" => "instituicao_nome",
            "label" => "Nome da instituição",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["instituicao_codigo"] = ["nome" => "instituicao_codigo", "exibir" => false];

        $va_campos_visualizacao["instituicao_nome"] = [
            "nome" => "instituicao_nome"
        ];

        $va_campos_visualizacao["instituicao_admin"] = [
            "nome" => "instituicao_admin"
        ];


        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, parent::get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["instituicao_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["navegacao"]["order_by"] = ["instituicao_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "instituicao_nome" => ["Nome", "main_field" => true
            ]
        ];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, parent::get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "instituicao_nome" => ["Nome", "main_field" => true]
        ];
    }

}

?>