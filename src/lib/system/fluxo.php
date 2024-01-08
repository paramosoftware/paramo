<?php

class fluxo extends objeto_base
{

    function __construct()
    {
        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->registros_filhos["etapa_fluxo"] = [
            "atributo_relacionamento" => "fluxo_codigo",
            "pode_excluir_pai" => true
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "fluxo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['fluxo_codigo'] = [
            'fluxo_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['fluxo_nome'] = [
            'fluxo_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's',
            "valor_nao_repete" => "fluxo_nome",
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['fluxo_recurso_sistema_codigo'] = [
            [
                'fluxo_recurso_sistema_codigo',
            ],
            'tabela_intermediaria' => 'fluxo_recurso_sistema',
            'chave_exportada' => 'fluxo_codigo',
            'campos_relacionamento' => [
                'fluxo_recurso_sistema_codigo' => 'recurso_sistema_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'recurso_sistema',
            'objeto' => 'recurso_sistema',
            'alias' => 'recursos do sistema'
        ];

        $va_relacionamentos['fluxo_etapa_fluxo_codigo'] = [
            ['fluxo_etapa_fluxo_codigo'],
            'tabela_intermediaria' => 'etapa_fluxo',
            'chave_exportada' => 'fluxo_codigo',
            'campos_relacionamento' => [
                'fluxo_etapa_fluxo_codigo' => [
                    ['codigo'],
                    "atributo" => "etapa_fluxo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'etapa_fluxo',
            'objeto' => 'etapa_fluxo',
            'tipo' => '1n',
            'alias' => 'etapas do fluxo'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["fluxo_nome"] = [
            "html_text_input",
            "nome" => "fluxo_nome",
            "label" => "Nome",
            "tamanho_maximo" => 100,
            "foco" => true
        ];

        $va_campos_edicao["fluxo_recurso_sistema_codigo"] = [
            "html_autocomplete",
            "nome" => ["fluxo_recurso_sistema", "fluxo_recurso_sistema_codigo"],
            "label" => "Recurso do sistema",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_plural"],
            "multiplos_valores" => true,
            "procurar_por" => "recurso_sistema_nome_plural",
            "visualizacao" => "lista",
        ];

        $va_campos_edicao["fluxo_etapa_fluxo_codigo"] = [
            "html_autocomplete",
            "nome" => ['fluxo_etapa_fluxo', 'fluxo_etapa_fluxo_codigo'],
            "label" => "Etapas",
            "objeto" => "etapa_fluxo",
            "atributos" => ["etapa_fluxo_codigo", "etapa_fluxo_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "etapa_fluxo_nome",
            "visualizacao" => "lista",
            "sugerir_valires" => false
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["fluxo_nome"] = [
            "html_text_input",
            "nome" => "fluxo_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["fluxo_codigo"] = ["nome" => "fluxo_codigo", "exibir" => false];
        $va_campos_visualizacao["fluxo_nome"] = ["nome" => "fluxo_nome"];

        $va_campos_visualizacao["fluxo_recurso_sistema_codigo"] = [
            "nome" => "fluxo_recurso_sistema_codigo",
            "formato" => ["campo" => "recurso_sistema_nome_plural"]
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["fluxo_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["fluxo_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "fluxo_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["fluxo_etapa_fluxo_codigo"] = [
            "nome" => "fluxo_etapa_fluxo_codigo",
            "formato" => ["campo" => "etapa_fluxo_nome"]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "fluxo_nome" => ["label" => "Nome", "main_field" => true],
            "fluxo_recurso_sistema_codigo" => "Recursos do sistema"
        ];
    }

    public function ler_etapas_sem_acesso($pa_fluxos, $pa_usuario_grupos_usuario)
    {
        $va_etapas_sem_acesso = array();

        if (count($pa_fluxos)) {
            // Tem que tratar o caso de o grupo de usuário não estar associado a nenhuma etapa do fluxo
            // !!!!

            $va_fluxo = $pa_fluxos[0];

            foreach ($va_fluxo["fluxo_etapa_fluxo_codigo"] as $va_etapa_fluxo) {
                if (isset($va_etapa_fluxo["etapa_fluxo_grupo_usuario_codigo"]) && count($va_etapa_fluxo["etapa_fluxo_grupo_usuario_codigo"])) {
                    $vb_usuario_tem_acesso = false;

                    //Se a etapa do fluxo está associada a um grupo, para que o usário tenha acesso a ela, ele tem que
                    // fazer parte do grupo
                    foreach ($va_etapa_fluxo["etapa_fluxo_grupo_usuario_codigo"] as $va_grupo_usuario) {
                        if (in_array($va_grupo_usuario["etapa_fluxo_grupo_usuario_codigo"], $pa_usuario_grupos_usuario)) {
                            if ($va_grupo_usuario["etapa_fluxo_acesso_etapa"])
                                $vb_usuario_tem_acesso = true;
                            else
                                $vb_usuario_tem_acesso = false;
                        }
                    }

                    if (!$vb_usuario_tem_acesso)
                        $va_etapas_sem_acesso[] = $va_etapa_fluxo["etapa_fluxo_codigo"];
                }
            }
        }

        return $va_etapas_sem_acesso;
    }

    public function ler_etapa_com_acesso_salvar($pa_fluxos, $pn_etapa_fluxo_sem_acesso_codigo)
    {
        if (count($pa_fluxos)) {
            // Tem que tratar o caso de o grupo de usuário não estar associado a nenhuma etapa do fluxo
            // !!!!

            $va_fluxo = $pa_fluxos[0];

            foreach ($va_fluxo["fluxo_etapa_fluxo_codigo"] as $va_etapa_fluxo) {
                if ($va_etapa_fluxo["fluxo_etapa_fluxo_codigo"]["etapa_fluxo_codigo"] == $pn_etapa_fluxo_sem_acesso_codigo) {
                    if (isset($va_etapa_fluxo["fluxo_etapa_fluxo_codigo"]["etapa_fluxo_substitutiva_codigo"])) {
                        $va_etapa_fluxo_substitutiva = $va_etapa_fluxo["fluxo_etapa_fluxo_codigo"]["etapa_fluxo_substitutiva_codigo"];

                        return $va_etapa_fluxo_substitutiva["etapa_fluxo_codigo"];
                    }
                }
            }
        }

        return "";
    }

}

?>