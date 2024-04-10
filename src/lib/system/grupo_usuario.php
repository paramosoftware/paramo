<?php

class grupo_usuario extends objeto_base
{

    private $nome;

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
        return "grupo_usuario";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['grupo_usuario_codigo'] = [
            'grupo_usuario_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['grupo_usuario_nome'] = [
            'grupo_usuario_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['grupo_usuario_controlar_acesso_acervos'] = [
            'grupo_usuario_controlar_acesso_acervos',
            'coluna_tabela' => 'controlar_acesso_acervos',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['grupo_usuario_recurso_sistema_codigo'] = [
            [
                'grupo_usuario_recurso_sistema_codigo',
                'grupo_usuario_recurso_sistema_ler',
                'grupo_usuario_recurso_sistema_inserir',
                'grupo_usuario_recurso_sistema_editar',
                'grupo_usuario_recurso_sistema_excluir',
                'grupo_usuario_recurso_sistema_substituir',
                'grupo_usuario_recurso_sistema_editar_lote',
                'grupo_usuario_recurso_sistema_excluir_lote'
            ],
            'tabela_intermediaria' => 'grupo_usuario_recurso_sistema',
            'chave_exportada' => 'grupo_usuario_codigo',
            'campos_relacionamento' => [
                'grupo_usuario_recurso_sistema_codigo' => 'recurso_sistema_codigo',
                'grupo_usuario_recurso_sistema_ler' => 'ler',
                'grupo_usuario_recurso_sistema_inserir' => 'inserir',
                'grupo_usuario_recurso_sistema_editar' => 'editar',
                'grupo_usuario_recurso_sistema_excluir' => 'excluir',
                'grupo_usuario_recurso_sistema_substituir' => 'substituir',
                'grupo_usuario_recurso_sistema_editar_lote' => 'editar_lote',
                'grupo_usuario_recurso_sistema_excluir_lote' => 'excluir_lote',
            ],
            'tipos_campos_relacionamento' => ['i', 'b', 'b', 'b', 'b', 'b', 'b', 'b'],
            'tabela_relacionamento' => 'recurso_sistema',
            'objeto' => 'recurso_sistema',
            'campos_saida' => [
                'recurso_sistema_nome_plural' => 'nome_plural',
                'recurso_sistema_id' => 'id'
            ],
            'alias' => 'recursos do sistema'
        ];

        $va_relacionamentos['grupo_usuario_usuario_codigo'] = [
            'grupo_usuario_usuario_codigo',
            'tabela_intermediaria' => 'usuario_grupo_usuario',
            'chave_exportada' => 'grupo_usuario_codigo',
            'campos_relacionamento' => ['grupo_usuario_usuario_codigo' => 'usuario_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'usuario',
            'objeto' => 'usuario',
            'alias' => 'usuários'
        ];

        $va_relacionamentos['grupo_usuario_etapa_fluxo_codigo'] = [
            'grupo_usuario_etapa_fluxo_codigo',
            'tabela_intermediaria' => 'etapa_fluxo_grupo_usuario',
            'chave_exportada' => 'grupo_usuario_codigo',
            'campos_relacionamento' => [
                'grupo_usuario_etapa_fluxo_codigo' => 'etapa_fluxo_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'etapa_fluxo',
            'objeto' => 'etapa_fluxo',
            'alias' => 'etapas de fluxo'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["grupo_usuario_nome"] = ["html_text_input", "nome" => "grupo_usuario_nome", "label" => "Nome", "foco" => true];

        $va_campos_edicao["grupo_usuario_controlar_acesso_acervos"] = [
            "html_checkbox_input", 
            "nome" => "grupo_usuario_controlar_acesso_acervos", 
            "label" => "Controlar acesso a acervos",
            "valor_padrao" => 1
        ];

        $va_campos_edicao["grupo_usuario_recurso_sistema_codigo"] = [
            "html_grid_input",
            "nome" => "grupo_usuario_recurso_sistema_codigo",
            "label" => "Permissões",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_plural"],
            "subcampos" => [
                "grupo_usuario_recurso_sistema_ler" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_ler", "label" => "Ler", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"],
                "grupo_usuario_recurso_sistema_inserir" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_inserir", "label" => "Criar", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"],
                "grupo_usuario_recurso_sistema_editar" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_editar", "label" => "Editar", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"],
                "grupo_usuario_recurso_sistema_excluir" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_excluir", "label" => "Excluir", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"],
                "grupo_usuario_recurso_sistema_substituir" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_substituir", "label" => "Substituir", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"],
                "grupo_usuario_recurso_sistema_editar_lote" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_editar_lote", "label" => "Editar em lote", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"],
                "grupo_usuario_recurso_sistema_excluir_lote" => ["html_checkbox_input", "nome" => "grupo_usuario_recurso_sistema_excluir_lote", "label" => "Excluir em lote", "formato" => "linha", "campo_pai" => "grupo_usuario_recurso_sistema_codigo"]
            ],
            "esconder_subcampos" => true,
            "filtro" => [
                [
                    "atributo" => "recurso_sistema_habilitado",
                    "valor" => 1
                ]
            ]
        ];

        $va_campos_edicao["grupo_usuario_usuario_codigo"] = [
            "html_autocomplete",
            "nome" => ["grupo_usuario_usuario", "grupo_usuario_usuario_codigo"],
            "label" => "Usuários",
            "objeto" => "Usuario",
            "atributos" => ["usuario_codigo", "usuario_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "usuario_nome",
            "visualizacao" => "lista"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["grupo_usuario_nome"] = [
            "html_text_input",
            "nome" => "grupo_usuario_nome",
            "label" => "Nome do grupo",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        $va_filtros_navegacao["grupo_usuario_usuario_codigo"] = [
            "html_combo_input",
            "nome" => "grupo_usuario_usuario_codigo",
            "label" => "Usuário",
            "objeto" => "usuario",
            "atributos" => [
                "usuario_codigo",
                "usuario_nome"
            ],
            "atributo" => "usuario_codigo",
            "sem_valor" => true,
            "filtro" => [
                [
                    "atributo" => "usuario_tipo_codigo",
                    "valor" => 3,
                    "operador" => "!="
                ]
            ]
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["grupo_usuario_codigo"] = ["nome" => "grupo_usuario_codigo", "exibir" => false];
        $va_campos_visualizacao["grupo_usuario_nome"] = ["nome" => "grupo_usuario_nome"];
        $va_campos_visualizacao["grupo_usuario_controlar_acesso_acervos"] = ["nome" => "grupo_usuario_controlar_acesso_acervos"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["grupo_usuario_nome" => "Nome"];

        $va_campos_visualizacao["grupo_usuario_recurso_sistema_codigo"] = ["nome" => "grupo_usuario_recurso_sistema_codigo"];
        $va_campos_visualizacao["grupo_usuario_usuario_codigo"] = [
            "nome" => "grupo_usuario_usuario_codigo",
            "formato" => ["campo" => "usuario_nome"]
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["grupo_usuario_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "grupo_usuario_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "grupo_usuario_nome" => ["label" => "Nome", "main_field" => true],
            "grupo_usuario_usuario_codigo" => "Usuários membros"
        ];
    }

    public function ler_permissoes($pn_grupo_usuario_codigo, $pn_recurso_sistema_codigo)
    {
        $va_campos = [
            'grupo_usuario_recurso_sistema_codigo' => 'recurso_sistema_codigo',
            'grupo_usuario_recurso_sistema_ler' => 'ler',
            'grupo_usuario_recurso_sistema_inserir' => 'inserir',
            'grupo_usuario_recurso_sistema_editar' => 'editar',
            'grupo_usuario_recurso_sistema_excluir' => 'excluir',
            'grupo_usuario_recurso_sistema_substituir' => 'substituir',
            'grupo_usuario_recurso_sistema_editar_lote' => 'editar_lote',
            'grupo_usuario_recurso_sistema_excluir_lote' => 'excluir_lote'
        ];

        $va_filtros = ["grupo_usuario_recurso_sistema_codigo" => $pn_recurso_sistema_codigo];

        $va_permissoes = $this->ler_relacionamento("grupo_usuario_recurso_sistema", "grupo_usuario_codigo", $pn_grupo_usuario_codigo, $va_campos, ['i', 'b', 'b', 'b', 'b', 'b', 'b'], null, null, $va_filtros);

        if (count($va_permissoes)) {
            return array(
                "pode_ler" => $va_permissoes[0]["grupo_usuario_recurso_sistema_ler"],
                "pode_inserir" => $va_permissoes[0]["grupo_usuario_recurso_sistema_inserir"],
                "pode_editar" => $va_permissoes[0]["grupo_usuario_recurso_sistema_editar"],
                "pode_excluir" => $va_permissoes[0]["grupo_usuario_recurso_sistema_excluir"],
                "pode_substituir" => $va_permissoes[0]["grupo_usuario_recurso_sistema_substituir"],
                "pode_editar_lote" => $va_permissoes[0]["grupo_usuario_recurso_sistema_editar_lote"],
                "pode_excluir_lote" => $va_permissoes[0]["grupo_usuario_recurso_sistema_excluir_lote"],
            );
        }
    }

}

?>