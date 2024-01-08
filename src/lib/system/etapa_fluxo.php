<?php

class etapa_fluxo extends objeto_base
{

    function __construct()
    {
        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
        return "etapa_fluxo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['etapa_fluxo_codigo'] = [
            'etapa_fluxo_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['etapa_fluxo_nome'] = [
            'etapa_fluxo_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['etapa_fluxo_descricao'] = [
            'etapa_fluxo_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['fluxo_codigo'] = [
            'fluxo_codigo',
            'coluna_tabela' => 'fluxo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'fluxo'
        ];

        $va_atributos['etapa_fluxo_substitutiva_codigo'] = [
            'etapa_fluxo_substitutiva_codigo',
            'coluna_tabela' => 'etapa_fluxo_substitutiva_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'etapa_fluxo'
        ];

        $va_atributos['etapa_fluxo_tipo_operacao_log_codigo'] = [
            'etapa_fluxo_tipo_operacao_log_codigo',
            'coluna_tabela' => 'tipo_operacao_log_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_operacao_log'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['etapa_fluxo_substitutiva_etapa_fluxo_codigo'] = [
            ['etapa_fluxo_substitutiva_etapa_fluxo_codigo'],
            'tabela_intermediaria' => 'etapa_fluxo',
            'chave_exportada' => 'etapa_fluxo_substitutiva_codigo',
            'campos_relacionamento' => [
                'etapa_fluxo_substitutiva_etapa_fluxo_codigo' => [
                    ['codigo'],
                    "atributo" => "etapa_fluxo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'etapa_fluxo',
            'objeto' => 'etapa_fluxo',
            'tipo' => '1n',
            'alias' => 'etapas fluxos substituídas'
        ];

        $va_relacionamentos['etapa_fluxo_grupo_usuario_codigo'] = [
            [
                'etapa_fluxo_grupo_usuario_codigo',
                'etapa_fluxo_acesso_registro',
                'etapa_fluxo_acesso_etapa',
                'etapa_fluxo_acesso_salvar_codigo'
            ],
            'tabela_intermediaria' => 'etapa_fluxo_grupo_usuario',
            'chave_exportada' => 'etapa_fluxo_codigo',
            'campos_relacionamento' => [
                'etapa_fluxo_grupo_usuario_codigo' => 'grupo_usuario_codigo',
                'etapa_fluxo_acesso_registro' => 'acesso_registro',
                'etapa_fluxo_acesso_etapa' => 'acesso_etapa_fluxo',
                'etapa_fluxo_acesso_salvar_codigo' => 'etapa_fluxo_acesso_salvar_codigo',
            ],
            'tipos_campos_relacionamento' => ['i', 'b', 'b'],
            'tabela_relacionamento' => 'grupo_usuario',
            'objeto' => 'grupo_usuario',
            'alias' => 'grupos de usuários'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao = array();

        $va_campos_edicao["fluxo_codigo"] = [
            "html_combo_input",
            "nome" => "fluxo_codigo",
            "label" => "Fluxo",
            "objeto" => "fluxo",
            "atributos" => ["fluxo_codigo", "fluxo_nome"],
            "atributo" => "fluxo_codigo",
            "sem_valor" => false,
            "foco" => true
        ];

        $va_campos_edicao["etapa_fluxo_nome"] = ["html_text_input", "nome" => "etapa_fluxo_nome", "label" => "Nome"];

        $va_campos_edicao["etapa_fluxo_descricao"] = [
            "html_text_input",
            "nome" => "etapa_fluxo_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["etapa_fluxo_grupo_usuario_codigo"] = [
            "html_autocomplete",
            "nome" => ["etapa_fluxo_grupo_usuario", "etapa_fluxo_grupo_usuario_codigo"],
            "label" => "Grupos de usuário associados",
            "objeto" => "grupo_usuario",
            "atributos" => ["grupo_usuario_codigo", "grupo_usuario_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "grupo_usuario_nome",
            "visualizacao" => "lista",
        ];

        $va_campos_edicao["etapa_fluxo_tipo_operacao_log_codigo"] = [
            "html_combo_input",
            "nome" => "etapa_fluxo_tipo_operacao_log_codigo",
            "label" => "Operação de log associada",
            "objeto" => "tipo_operacao_log",
            "atributos" => ["tipo_operacao_log_codigo", "tipo_operacao_log_nome"],
            "atributo" => "tipo_operacao_log_codigo",
            "sem_valor" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["etapa_fluxo_nome"] = [
            "html_text_input",
            "nome" => "etapa_fluxo_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["etapa_fluxo_codigo"] = ["nome" => "etapa_fluxo_codigo", "exibir" => false];
        $va_campos_visualizacao["etapa_fluxo_nome"] = ["nome" => "etapa_fluxo_nome"];

        $va_campos_visualizacao["fluxo_codigo"] = ["nome" => "fluxo_codigo",
            "formato" => ["campo" => "fluxo_nome"]];

        $va_campos_visualizacao["etapa_fluxo_substitutiva_codigo"] = ["nome" => "etapa_fluxo_substitutiva_codigo",
            "formato" => ["campo" => "etapa_fluxo_nome"]];

        $va_campos_visualizacao["etapa_fluxo_grupo_usuario_codigo"] = [
            "nome" => "etapa_fluxo_grupo_usuario_codigo",
            "formato" => ["campo" => "grupo_usuario_nome"]
        ];

        $va_campos_visualizacao["etapa_fluxo_tipo_operacao_log_codigo"] = ["nome" => "etapa_fluxo_tipo_operacao_log_codigo",
            "formato" => ["campo" => "tipo_operacao_log_nome"]];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["etapa_fluxo_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["etapa_fluxo_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "etapa_fluxo_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["etapa_fluxo_descricao"] = ["nome" => "etapa_fluxo_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "etapa_fluxo_nome" => ["label" => "Nome", "main_field" => true],
            "fluxo_codigo" => "Fluxo"
        ];
    }


}

?>