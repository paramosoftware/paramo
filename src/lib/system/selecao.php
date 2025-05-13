<?php

class selecao extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->filtros_selecao["selecao_tipo_codigo"] = 1;
        $this->tem_representante_digital = false;
    }

    public function inicializar_tabela_banco()
    {
        return "selecao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['selecao_codigo'] = [
            'selecao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['selecao_tipo_codigo'] = [
            'selecao_tipo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_selecao',
            'valor_padrao' => 1
        ];

        $va_atributos['selecao_nome'] = [
            'selecao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['selecao_usuario_codigo'] = [
            'selecao_usuario_codigo',
            'coluna_tabela' => 'usuario_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'usuario'
        ];

        $va_atributos['selecao_data'] = [
            'selecao_data',
            'coluna_tabela' => ['data_inicial' => 'data'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['selecao_recurso_sistema_codigo'] = [
            'selecao_recurso_sistema_codigo',
            'coluna_tabela' => 'recurso_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'recurso_sistema'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['selecao_item_codigo'] = [
            [
                'selecao_item_codigo',
                'selecao_item_acervo_item_acervo_consultado'
            ],
            'tabela_intermediaria' => 'selecao_item',
            'chave_exportada' => 'selecao_codigo',
            'campos_relacionamento' => [
                'selecao_item_codigo' => 'item_codigo',
                'selecao_item_acervo_item_acervo_consultado' => 'item_acervo_consultado'
            ],
            'tipos_campos_relacionamento' => ['i', 'b'],
            'dependencia_objeto' => 'selecao_recurso_sistema_codigo_0_recurso_sistema_id',
            'alias' => 'itens selecionados'
        ];

        $va_relacionamentos['selecao_usuario_compartilhamento_codigo'] = [
            'selecao_usuario_compartilhamento_codigo',
            'tabela_intermediaria' => 'selecao_usuario',
            'chave_exportada' => 'selecao_codigo',
            'campos_relacionamento' => ['selecao_usuario_compartilhamento_codigo' => 'usuario_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'usuario',
            'objeto' => 'usuario',
            'alias' => 'usuários'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '', $pn_bibliografia_codigo = '', $pa_valores_objeto = array())
    {
        $va_campos_edicao = array();

        if (isset($pa_valores_objeto["selecao_recurso_sistema_codigo"]))
        {
            $va_filtro = [
                [
                    "valor" => $pa_valores_objeto["selecao_recurso_sistema_codigo"]["recurso_sistema_codigo"],
                    "atributo" => "recurso_sistema_codigo",
                    "operador" => "="
                ]
            ];
        }
        else
        {
            $va_filtro = [
                [
                    "valor" => "1",
                    "atributo" => "recurso_sistema_selecionavel",
                    "operador" => "="
                ]
            ];
        }

        $va_campos_edicao["selecao_recurso_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "selecao_recurso_sistema_codigo",
            "label" => "Recurso do sistema",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => false,
            "exibicao_obrigatoria" => true,
            "filtro" => $va_filtro,
        ];

        $va_campos_edicao["selecao_nome"] = [
            "html_text_input",
            "nome" => "selecao_nome",
            "label" => "Nome",
            "foco" => true
        ];

        if (isset($pa_valores_objeto["selecao_recurso_sistema_codigo"])) 
        {
            $vs_objeto_id = $pa_valores_objeto["selecao_recurso_sistema_codigo"]["recurso_sistema_id"];

            $vo_objeto = new $vs_objeto_id($vs_objeto_id);
            $va_visualizacao = $vo_objeto->get_visualizacao("navegacao");

            $vs_atributo_identificador = "";
            $vs_atributo_nome = "";

            foreach ($va_visualizacao["ordem_campos"] as $vs_campo => $va_campo) 
            {
                if (isset($va_campo["id_field"])) {
                    $vs_atributo_identificador = $vs_campo;
                }

                if (isset($va_campo["main_field"]) && !$vs_atributo_nome) {
                    $vs_atributo_nome = $vs_campo;
                }
            }

            if (!$vs_atributo_nome) 
            {
                $vs_atributo_nome = $vs_objeto_id.'_nome';
            }

            if (!$vs_atributo_identificador) $vs_atributo_identificador = $vs_atributo_nome;

            if ($vs_atributo_identificador && (strpos($vs_atributo_identificador, "identificador") !== false)) 
            {
                $va_atributos = [
                    $vo_objeto->get_chave_primaria()[0],
                    $vs_atributo_nome
                ];

                $va_campos = array_keys($va_visualizacao["campos"]);
                $vs_campo_codigo = in_array("item_acervo_codigo", $va_campos) ? "item_acervo_codigo" : "texto_codigo";

                $vs_procurar_por = $vs_campo_codigo.'_0_'.$vs_atributo_identificador;
            } 
            else 
            {
                $va_atributos = [$vo_objeto->get_chave_primaria()[0], $vs_atributo_nome];
                $vs_procurar_por = $vs_atributo_nome;
            }

            $va_campos_edicao["selecao_item_codigo"] = [
                "html_autocomplete",
                "nome" => ["selecao_item", "selecao_item_codigo"],
                "label" => "Itens",
                "objeto" => $vs_objeto_id,
                "atributos" => $va_atributos,
                "multiplos_valores" => true,
                "procurar_por" => $vs_procurar_por,
                "visualizacao" => "lista",
            ];
        }

        $va_campos_edicao["selecao_usuario_compartilhamento_codigo"] = [
            "html_autocomplete",
            "nome" => ["selecao_usuario_compartilhamento", "selecao_usuario_compartilhamento_codigo"],
            "label" => "Compartilhar com usuários",
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
        parent::inicializar_filtros_navegacao($pn_bibliografia_codigo);

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["selecao_codigo"] = ["nome" => "selecao_codigo", "exibir" => false];
        $va_campos_visualizacao["selecao_nome"] = ["nome" => "selecao_nome"];
        $va_campos_visualizacao["selecao_recurso_sistema_codigo"] = ["nome" => "selecao_recurso_sistema_codigo"];

        $va_campos_visualizacao["selecao_tipo_codigo"] = [
            "nome" => "selecao_tipo_codigo",
            "formato" => ["campo" => "tipo_selecao_nome"]
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["selecao_nome" => "Nome"];

        $va_campos_visualizacao["selecao_usuario_codigo"] = ["nome" => "selecao_usuario_codigo", "formato" => ["campo" => "usuario_nome"]];

        $va_campos_visualizacao["selecao_data"] = [
            "nome" => "selecao_data",
            "formato" => ["data" => "completo"],
        ];

        $va_campos_visualizacao["selecao_item_codigo"] = ["nome" => "selecao_item_codigo"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["selecao_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "selecao_nome" => ["label" => "Nome", "main_field" => true],
            "selecao_usuario_codigo" => "Usuário",
            "selecao_data" => "Data"
        ];

        $va_campos_visualizacao["selecao_usuario_compartilhamento_codigo"] = [
            "nome" => "selecao_usuario_compartilhamento_codigo",
            "formato" => ["campo" => "usuario_nome"]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "selecao_nome" => ["label" => "Nome", "main_field" => true],
            "selecao_usuario_codigo" => "Usuário",
            "selecao_data" => "Data"
        ];
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 0, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        if (!isset($pa_filtros_busca["selecao_usuario_compartilhamento_codigo"]))
            $pa_filtros_busca["selecao_usuario_codigo"] = $_SESSION["usuario_logado_codigo"];

        return parent::ler_lista($pa_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info, $pn_idioma_codigo);
    }

    public function ler_numero_registros($pa_filtros_busca = null, $pa_log_info = null, $pb_retornar_ramos_inferiores = true)
    {
        $pa_filtros_busca["selecao_usuario_codigo"] = $_SESSION["usuario_logado_codigo"];

        return parent::ler_numero_registros($pa_filtros_busca, $pa_log_info);
    }

    public function salvar($pa_valores, $pb_logar_operacao = true, $pn_idioma_codigo = 1, $pb_salvar_objeto_pai = true, $ps_id_objeto_filho = '', $pb_sobrescrever = true)
    {
        if (!isset($pa_valores["selecao_data"]))
            $pa_valores["selecao_data"] = date('Y-m-d');

        if (!isset($pa_valores["selecao_usuario_codigo"]))
            $pa_valores['selecao_usuario_codigo'] = $pa_valores['usuario_logado_codigo'];

        return parent::salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo, true, "", $pb_sobrescrever);
    }

    public function adicionar_item($pn_selecao_codigo, $pn_item_codigo)
    {
        parent::inserir_relacionamento("selecao_item", "selecao_codigo", $pn_selecao_codigo, ["item_codigo"], ["i"], array($pn_item_codigo));
    }

    public function remover_item($pn_selecao_codigo, $pn_item_codigo)
    {
        parent::excluir_relacionamentos("selecao_item", "selecao_codigo", $pn_selecao_codigo, null, ["selecao_item_codigo" => "item_codigo"], ["i"], ["selecao_item_codigo" => $pn_item_codigo]);
    }

}

?>