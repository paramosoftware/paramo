<?php

class usuario extends usuario_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = "usuario";
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->registros_filhos["selecao"] = [
            "atributo_relacionamento" => "selecao_usuario_codigo",
            "pode_excluir_pai" => true
        ];

        $this->registros_filhos["oauth"] = [
            "atributo_relacionamento" => "oauth_usuario_codigo",
            "pode_excluir_pai" => true
        ];

        $this->controlador_acesso = [
            "instituicao_codigo" => "usuario_instituicao_codigo"
        ];
    }

    public function inicializar_chave_primaria()
    {
        return [
            'usuario_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        return parent::inicializar_atributos();
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos = array_merge($va_relacionamentos, parent::inicializar_relacionamentos());

        $va_relacionamentos['usuario_endereco'] = [
            [
                'endereco_logradouro',
                'endereco_bairro',
                'endereco_localidade_codigo',
            ],
            'tabela_intermediaria' => 'usuario_endereco',
            'chave_exportada' => 'usuario_codigo',
            'campos_relacionamento' => [
                'endereco_logradouro' => 'logradouro',
                'endereco_bairro' => 'bairro',
                'endereco_localidade_codigo' => 'localidade_codigo'
            ],
            'tipos_campos_relacionamento' => ['s', 's', 'i'],
            'alias' => 'endereços'
        ];

        $va_relacionamentos['usuario_setor_sistema_codigo'] = [
            'usuario_setor_sistema_codigo',
            'tabela_intermediaria' => 'usuario_setor_sistema',
            'chave_exportada' => 'usuario_codigo',
            'campos_relacionamento' => ['usuario_setor_sistema_codigo' => 'setor_sistema_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'setor_sistema',
            'objeto' => 'setor_sistema',
            'alias' => 'setores do sistema'
        ];

        $va_relacionamentos['usuario_grupo_usuario_codigo'] = [
            'usuario_grupo_usuario_codigo',
            'tabela_intermediaria' => 'usuario_grupo_usuario',
            'chave_exportada' => 'usuario_codigo',
            'campos_relacionamento' => ['usuario_grupo_usuario_codigo' => 'grupo_usuario_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'grupo_usuario',
            'objeto' => 'grupo_usuario',
            'alias' => 'grupos de usuáro'
        ];

        $va_relacionamentos['usuario_acervo_codigo'] = [
            'usuario_acervo_codigo',
            'tabela_intermediaria' => 'usuario_acervo',
            'chave_exportada' => 'usuario_codigo',
            'campos_relacionamento' => ['usuario_acervo_codigo' => 'acervo_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'alias' => 'acervos'
        ];

        $va_relacionamentos['usuario_selecao_codigo'] = [
            ['usuario_selecao_codigo'],
            'tabela_intermediaria' => 'selecao',
            'chave_exportada' => 'usuario_codigo',
            'campos_relacionamento' => [
                'usuario_selecao_codigo' => [
                    ['codigo'],
                    "atributo" => "selecao_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'selecao',
            'objeto' => 'selecao',
            'tipo' => '1n',
            'alias' => "seleções"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["usuario_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "usuario_instituicao_codigo",
            "atributo" => "instituicao_codigo",
            "label" => "Instituição",
            "objeto" => "Instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "sem_valor" => false,
            "dependencia" => [
                [
                    "campo" => "instituicao_codigo",
                    "atributo" => "instituicao_codigo",
                    "obrigatoria" => true
                ]
            ],
            "conectar" => [
                [
                    "campo" => "usuario_acervo_codigo",
                    "atributo" => "acervo_instituicao_codigo"
                ]
            ]
        ];

        $va_campos_edicao["usuario_setor_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "usuario_setor_sistema_codigo",
            "label" => "Setor do sistema",
            "objeto" => "setor_sistema",
            "atributos" => ["setor_sistema_codigo", "setor_sistema_nome"],
            "formato" => "multi_selecao"
        ];

        $va_campos_edicao["usuario_tipo_codigo"] = [
            "html_combo_input",
            "nome" => "usuario_tipo_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_usuario",
            "atributo" => "tipo_usuario_codigo",
            "atributos" => ["tipo_usuario_codigo", "tipo_usuario_nome"],
            "sem_valor" => false
        ];

        $va_campos_edicao["usuario_nome"] = [
            "html_text_input",
            "nome" => "usuario_nome",
            "label" => "Nome",
            "foco" => true,
            "obrigatorio" => true
        ];

        $va_campos_edicao["usuario_login"] = [
            "html_text_input",
            "nome" => "usuario_login",
            "label" => "Login",
            "obrigatorio" => true
        ];

        $va_campos_edicao["usuario_email"] = [
            "html_text_input",
            "nome" => "usuario_email",
            "label" => "E-mail",
            "obrigatorio" => true
        ];

        $va_campos_edicao["usuario_telefone"] = [
            "html_text_input",
            "nome" => "usuario_telefone",
            "label" => "Telefone"
        ];

        $va_campos_edicao["usuario_alterar_senha"] = [
            "html_checkbox_input",
            "nome" => "usuario_alterar_senha",
            "label" => "Alterar senha",
            "modo" => "insert",
            "controlar_exibicao" => ["usuario_senha"]
        ];

        $va_campos_edicao["usuario_senha"] = [
            "html_text_input",
            "nome" => "usuario_senha",
            "label" => "Senha",
            "formato" => "senha",
            "regra_exibicao" => ["usuario_alterar_senha" => 1]
        ];

        $va_campos_edicao["usuario_grupo_usuario"] = [
            "html_autocomplete",
            "nome" => "usuario_grupo_usuario",
            "label" => "Grupo de permissões",
            "objeto" => "Grupo_Usuario",
            "atributos" => ["grupo_usuario_codigo", "grupo_usuario_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "grupo_usuario_nome",
            "visualizacao" => "lista"
        ];

        $va_campos_edicao["usuario_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["usuario_acervo", "usuario_acervo_codigo"],
            "label" => "Acervos atribuídos",
            "objeto" => "acervo",
            "atributos" => ["acervo_codigo", "acervo_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "acervo_nome",
            "visualizacao" => "lista",
            /*
            "dependencia" => [
                [
                    "campo" => "usuario_instituicao_codigo",
                    "atributo" => "acervo_instituicao_codigo"
                ]
            ]
            */
        ];

        $va_campos_edicao["usuario_ativo"] = [
            "html_checkbox_input",
            "nome" => "usuario_ativo",
            "label" => "Ativo",
            "valor_padrao" => "1"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["usuario_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "usuario_instituicao_codigo",
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

        $va_filtros_navegacao["usuario_nome"] = ["html_text_input", "nome" => "usuario_nome", "label" => "Nome", "operador_filtro" => "LIKE"];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["usuario_codigo"] = ["nome" => "usuario_codigo", "exibir" => false];
        $va_campos_visualizacao["usuario_nome"] = ["nome" => "usuario_nome"];
        $va_campos_visualizacao["usuario_email"] = ["nome" => "usuario_email"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["usuario_nome" => "Nome"];

        $va_campos_visualizacao["usuario_instituicao_codigo"] = [
            "nome" => "usuario_instituicao_codigo",
            "formato" => ["campo" => "instituicao_nome"]
        ];

        $va_campos_visualizacao["usuario_tipo_codigo"] = [
            "nome" => "usuario_tipo_codigo",
            "formato" => ["campo" => "tipo_usuario_nome"]
        ];

        $va_campos_visualizacao["usuario_setor_sistema_codigo"] = [
            "nome" => "usuario_setor_sistema_codigo",
            "formato" => ["campo" => "setor_sistema_nome"]
        ];

        $va_campos_visualizacao["usuario_acervo_codigo"] = [
            "nome" => "usuario_acervo_codigo",
            "formato" => ["campo" => "acervo_nome"]
        ];

        $va_campos_visualizacao["usuario_grupo_usuario_codigo"] = [
            "nome" => "usuario_grupo_usuario_codigo",
            "formato" => ["campo" => "grupo_usuario_nome"]
        ];

        $va_campos_visualizacao["usuario_login"] = ["nome" => "usuario_login"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["usuario_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "usuario_nome" => ["label" => "Nome", "main_field" => true],
            "usuario_login" => "Login"
        ];

        $va_campos_visualizacao["usuario_telefone"] = ["nome" => "usuario_telefone"];
        //$va_campos_visualizacao["usuario_senha"] = ["nome" => "usuario_senha", "exibir" => false];
        $va_campos_visualizacao["usuario_endereco"] = ["nome" => "usuario_endereco"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "usuario_nome" => ["label" => "Nome", "main_field" => true],
            "usuario_login" => "Login",
            "usuario_email" => "E-mail",
            "usuario_setor_sistema_codigo" => "setor_sistema",
            "usuario_grupo_usuario_codigo" => "Grupo de permissões",
            "usuario_acervo_codigo" => "Acervos atribuídos",
        ];

        $this->visualizacoes["senha"]["campos"]["usuario_codigo"] = ["nome" => "usuario_codigo"];
        $this->visualizacoes["senha"]["campos"]["usuario_senha"] = ["nome" => "usuario_senha"];
        $this->visualizacoes["senha"]["campos"]["usuario_token"] = ["nome" => "usuario_token"];
        $this->visualizacoes["senha"]["campos"]["usuario_ultimo_login"] = ["nome" => "usuario_ultimo_login"];
        $this->visualizacoes["senha"]["campos"]["usuario_senha_provisoria"] = ["nome" => "usuario_senha_provisoria"];
        $this->visualizacoes["senha"]["campos"]["usuario_data_expiracao_senha_provisoria"] = ["nome" => "usuario_data_expiracao_senha_provisoria"];
    }

    public function ler_numero_registros($pa_filtros_busca = null, $pa_log_info = null, $pb_retornar_ramos_inferiores = true)
    {
        if (isset($pa_filtros_busca["instituicao_codigo"]))
            $pa_filtros_busca["usuario_instituicao_codigo"] = $pa_filtros_busca["instituicao_codigo"];

        return parent::ler_numero_registros($pa_filtros_busca, $pa_log_info, $pb_retornar_ramos_inferiores);
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        if (isset($pa_filtros_busca["instituicao_codigo"]))
            $pa_filtros_busca["usuario_instituicao_codigo"] = $pa_filtros_busca["instituicao_codigo"];

        return parent::ler_lista($pa_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info, $pn_idioma_codigo, $pb_retornar_ramos_inferiores);
    }
}

?>