<?php

class campo_sistema extends objeto_base
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
        return "campo_sistema";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['campo_sistema_codigo'] = [
            'campo_sistema_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['campo_sistema_recurso_sistema_codigo'] = [
            'campo_sistema_recurso_sistema_codigo',
            'coluna_tabela' => 'recurso_sistema_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'recurso_sistema'
        ];

        $va_atributos['campo_sistema_tipo_codigo'] = [
            'campo_sistema_tipo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_campo_sistema'
        ];

        $va_atributos['campo_sistema_nome'] = [
            'campo_sistema_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's',
            //"valor_nao_repete" => "campo_sistema_nome",
        ];

        $va_atributos['campo_sistema_alias'] = [
            'campo_sistema_alias',
            'coluna_tabela' => 'alias',
            'tipo_dado' => 's'
        ];

        $va_atributos['campo_sistema_descricao'] = [
            'campo_sistema_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['campo_sistema_objeto_chave_estangeira_codigo'] = [
            'campo_sistema_objeto_chave_estangeira_codigo',
            'coluna_tabela' => 'objeto_chave_estrangeira',
            'tipo_dado' => 'i',
            'objeto' => 'recurso_sistema'
        ];

        $va_atributos['campo_sistema_campo_sistema_superior_codigo'] = [
            'campo_sistema_campo_sistema_superior_codigo',
            'coluna_tabela' => 'campo_sistema_superior_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'campo_sistema'
        ];

        $va_atributos['campo_sistema_identificador_recurso_sistema'] = [
            'campo_sistema_identificador_recurso_sistema',
            'coluna_tabela' => 'identificador_recurso_sistema',
            'tipo_dado' => 'b'
        ];

        $va_atributos['campo_sistema_obrigatorio'] = [
            'campo_sistema_obrigatorio',
            'coluna_tabela' => 'obrigatorio',
            'tipo_dado' => 'b'
        ];

        $va_atributos['campo_sistema_exibir_lista_agrupadores'] = [
            'campo_sistema_exibir_lista_agrupadores',
            'coluna_tabela' => 'exibir_lista_agrupadores',
            'tipo_dado' => 'b'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['campo_sistema_visualizacao_codigo'] = [
            ['campo_sistema_visualizacao_codigo'],
            'tabela_intermediaria' => 'visualizacao_campo_sistema',
            'chave_exportada' => 'campo_sistema_codigo',
            'campos_relacionamento' => ['campo_sistema_visualizacao_codigo' => 'visualizacao_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'visualizacao',
            'objeto' => 'visualizacao',
            'alias' => 'visualizações'
        ];

        /*
        $va_relacionamentos['campo_sistema_recurso_sistema_codigo'] = [
            ['campo_sistema_recurso_sistema_codigo'],
            'tabela_intermediaria' => 'recurso_sistema',
            'chave_exportada' => 'campo_hierarquico_codigo',
            'campos_relacionamento' => [
                'campo_sistema_recurso_sistema_codigo' => [
                    ['codigo'],
                    "atributo" => "recurso_sistema_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'recurso_sistema',
            'objeto' => 'recurso_sistema',
            'tipo' => '1n',
            'alias' => "recursos do sistema"
        ];
        */
        
        $va_relacionamentos['campo_sistema_importacao_codigo'] = [
            ['campo_sistema_importacao_codigo'],
            'tabela_intermediaria' => 'importacao_campo_sistema',
            'chave_exportada' => 'campo_sistema_codigo',
            'campos_relacionamento' => ['campo_sistema_importacao_codigo' => 'importacao_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'importacao',
            'objeto' => 'importacao',
            'alias' => 'importações'
        ];

        $va_relacionamentos['campo_sistema_campo_sistema_inferior_codigo'] = [
            ['campo_sistema_campo_sistema_inferior_codigo'],
            'tabela_intermediaria' => 'campo_sistema',
            'chave_exportada' => 'campo_sistema_superior_codigo',
            'campos_relacionamento' => [
                'campo_sistema_campo_sistema_inferior_codigo' => [
                    ['codigo'],
                    "atributo" => "campo_sistema_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'campo_sistema',
            'objeto' => 'campo_sistema',
            'tipo' => '1n',
            'alias' => 'campos de sistema inferiores'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '')
    {
        $va_campos_edicao = array();

        $va_campos_edicao["campo_sistema_recurso_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "campo_sistema_recurso_sistema_codigo",
            "label" => "Recurso do sistema",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => false,
            "conectar" => [
                [
                    "campo" => "campo_sistema_campo_sistema_superior_codigo",
                    "atributo" => "campo_sistema_recurso_sistema_codigo"
                ]
            ]
        ];

        $va_campos_edicao["campo_sistema_tipo_codigo"] = [
            "html_combo_input",
            "nome" => "campo_sistema_tipo_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_campo_sistema",
            "atributos" => ["tipo_campo_sistema_codigo", "tipo_campo_sistema_nome"],
            "atributo" => "tipo_campo_sistema_codigo",
            "sem_valor" => false,
            "controlar_exibicao" => ["campo_sistema_objeto_chave_estangeira_codigo", "campo_sistema_tipo_campo_ui_codigo"],
            "foco" => true,
        ];

        $va_campos_edicao["campo_sistema_nome"] = [
            "html_text_input",
            "nome" => "campo_sistema_nome",
            "label" => "Id",
            "tamanho_maximo" => 100
        ];

        $va_campos_edicao["campo_sistema_alias"] = [
            "html_text_input",
            "nome" => "campo_sistema_alias",
            "label" => "Alias",
            "tamanho_maximo" => 100
        ];

        $va_campos_edicao["campo_sistema_descricao"] = [
            "html_text_input",
            "nome" => "campo_sistema_descricao",
            "label" => "Descrição",
            "numero_linhas" => 5
        ];

        $va_campos_edicao["campo_sistema_objeto_chave_estangeira_codigo"] = [
            "html_combo_input",
            "nome" => "campo_sistema_objeto_chave_estangeira_codigo",
            "label" => "Objeto da chave estrangeira",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => true
        ];

        $va_campos_edicao["campo_sistema_campo_sistema_superior_codigo"] = [
            "html_combo_input",
            "nome" => "campo_sistema_campo_sistema_superior_codigo",
            "label" => "Subcampo de",
            "objeto" => "campo_sistema",
            "atributos" => ["campo_sistema_codigo", "campo_sistema_alias"],
            "atributo" => "campo_sistema_codigo",
            "sem_valor" => true,
            "dependencia" => [
                [
                    "campo" => "campo_sistema_recurso_sistema_codigo", 
                    "atributo" => "campo_sistema_recurso_sistema_codigo",
                ]
            ],
            "filtro" => [
                [
                    "valor" => $pn_objeto_codigo,
                    "atributo" => "campo_sistema_codigo",
                    "operador" => "!="
                ]
            ]
        ];

        $va_campos_edicao["campo_sistema_obrigatorio"] = [
            "html_checkbox_input",
            "nome" => "campo_sistema_obrigatorio",
            "label" => "Cadastro obrigatório"
        ];

        $va_campos_edicao["campo_sistema_identificador_recurso_sistema"] = [
            "html_checkbox_input",
            "nome" => "campo_sistema_identificador_recurso_sistema",
            "label" => "Identificador do recurso de sistema"
        ];

        $va_campos_edicao["campo_sistema_exibir_lista_agrupadores"] = [
            "html_checkbox_input",
            "nome" => "campo_sistema_exibir_lista_agrupadores",
            "label" => "Exibir na lista de agrupadores",
            "valor_padrao" => "1"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["campo_sistema_alias"] = [
            "html_text_input",
            "nome" => "campo_sistema_alias",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["campo_sistema_recurso_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "campo_sistema_recurso_sistema_codigo",
            "label" => "Recurso do sistema",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_singular"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => true,
            "operador_filtro" => "="
        ];

        return $va_filtros_navegacao;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return array(
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "campo_sistema",
            "atributos" =>
                [
                    $ps_campo_codigo == '' ? "campo_sistema_codigo" : $ps_campo_codigo,
                    "campo_sistema_alias"
                ],
            "dependencia" => [
                [
                    "campo" => "campo_sistema_alias",
                    "atributo" => "campo_sistema_alias"
                ],
                [
                    "campo" => "campo_sistema_recurso_sistema_codigo",
                    "atributo" => "campo_sistema_recurso_sistema_codigo"
                ]               
            ]
        );
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["campo_sistema_codigo"] = ["nome" => "campo_sistema_codigo", "exibir" => false];
        $va_campos_visualizacao["campo_sistema_nome"] = ["nome" => "campo_sistema_nome"];
        $va_campos_visualizacao["campo_sistema_alias"] = ["nome" => "campo_sistema_alias"];
        $va_campos_visualizacao["campo_sistema_descricao"] = ["nome" => "campo_sistema_descricao"];
        $va_campos_visualizacao["campo_sistema_identificador_recurso_sistema"] = ["nome" => "campo_sistema_identificador_recurso_sistema"];
        $va_campos_visualizacao["campo_sistema_obrigatorio"] = ["nome" => "campo_sistema_obrigatorio"];
        $va_campos_visualizacao["campo_sistema_exibir_lista_agrupadores"] = ["nome" => "campo_sistema_exibir_lista_agrupadores"];

        /*
        $va_campos_visualizacao["campo_sistema_descricao"] = ["nome" => "campo_sistema_descricao"];
        */
        
        $va_campos_visualizacao["campo_sistema_tipo_codigo"] = [
            "nome" => "campo_sistema_tipo_codigo",
            "formato" => ["campo" => "tipo_campo_sistema_nome"]
        ];

        $va_campos_visualizacao["campo_sistema_objeto_chave_estangeira_codigo"] = [
            "nome" => "campo_sistema_objeto_chave_estangeira_codigo",
            "formato" => ["campo" => "recurso_sistema_codigo"]
        ];

        $va_campos_visualizacao["campo_sistema_campo_sistema_superior_codigo"] = [
            "nome" => "campo_sistema_campo_sistema_superior_codigo",
            "formato" => ["campo" => "campo_sistema_alias"]
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["campo_sistema_alias" => "Nome"];

        $va_campos_visualizacao["campo_sistema_recurso_sistema_codigo"] = [
            "nome" => "campo_sistema_recurso_sistema_codigo",
            "formato" => ["campo" => "recurso_sistema_nome_singular"]
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["campo_sistema_alias" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "campo_sistema_alias" => ["label" => "Nome", "main_field" => true],
            "campo_sistema_recurso_sistema_codigo" => "Recurso do sistema",
            "campo_sistema_nome" => "Id"
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "campo_sistema_alias" => ["label" => "Nome", "main_field" => true],
            "campo_sistema_recurso_sistema_codigo" => "Recurso do sistema"
        ];
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_campos_sistema_pai = array();

        if (isset($pa_filtros_busca["campo_sistema_recurso_sistema_codigo"]))
        {
            if (!is_array($pa_filtros_busca["campo_sistema_recurso_sistema_codigo"]))
                $va_recurso_sistema_codigo = array($pa_filtros_busca["campo_sistema_recurso_sistema_codigo"]);
            else
                $va_recurso_sistema_codigo = $pa_filtros_busca["campo_sistema_recurso_sistema_codigo"];

            $vo_recurso_sistema = new recurso_sistema("recurso_sistema");
            $va_recurso_sistema = $vo_recurso_sistema->ler($va_recurso_sistema_codigo[0], "lista");

            if (isset($va_recurso_sistema))
            {
                $vo_objeto = new $va_recurso_sistema["recurso_sistema_id"]($va_recurso_sistema["recurso_sistema_id"]);

                if ($vo_objeto->objeto_pai)
                {
                    $vo_objeto_pai = new $vo_objeto->objeto_pai($vo_objeto->objeto_pai);

                    if ($vo_objeto_pai->recurso_sistema_codigo)
                    {
                        $va_filtros_busca = $pa_filtros_busca;
                        $va_filtros_busca["campo_sistema_recurso_sistema_codigo"] = $vo_objeto_pai->recurso_sistema_codigo;

                        $va_campos_sistema_pai = $this->ler_lista($va_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info);
                    }
                }
            }
        }

        return array_merge($va_campos_sistema_pai, parent::ler_lista($pa_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info));
    }

}

?>