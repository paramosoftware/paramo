<?php

class objeto_base
{

    protected $recurso_sistema_codigo = "";
    
    protected $codigo;

    protected $tabela_banco;
    protected $chave_primaria;
    protected $atributos = array();
    protected $relacionamentos = array();

    protected $objeto_pai = "";
    protected $campo_relacionamento_pai = "";
    protected $excluir_objeto_pai = false;

    protected $hierarquico = false;
    protected $campo_hierarquico = "";
    public $exibir_lista_hierarquica = true;
    public $tipo_hierarquia = "default";
    public $tipo_hierarquia_codigo;

    protected $filtros_selecao = array();

    protected $registros_filhos = array();

    protected $tem_representante_digital = true;
    protected $pode_ser_incluido_selecao = false;

    protected $form_edicao = array();
    protected $campos_edicao = array();
    protected $campos_visualizacao = array();
    protected $filtros_navegacao = array();
    protected $visualizacoes = array();
    protected $campos_importacao = array();

    protected $banco_dados;
    protected $va_campos;
    protected $va_joins;
    protected $va_wheres;
    protected $va_tipos_parametros;
    protected $va_parametros;
    protected $va_order_by;

    public $controlador_acesso = array();
    public $registros_protegidos = array();

    // Eu posso criar um objeto que vai armazenar informações
    // de vários tipos de objetos
    
    protected $objetos = array();
    protected $numero_registros_por_objeto = array();

    protected $autoincrement_codigo = false;

    function __construct($ps_recurso_sistema_id = '')
    {
        $this->banco_dados = $this->get_banco();
    }

    public static function ler_recurso_sistema_codigo($ps_recurso_sistema_id = '')
    {
        if ($ps_recurso_sistema_id)
        {
            if (isset($_SESSION[$ps_recurso_sistema_id . "_recurso_sistema_codigo"]))
                return $_SESSION[$ps_recurso_sistema_id . "_recurso_sistema_codigo"];
            else
            {
                $vo_recurso_sistema = new recurso_sistema();
                $va_recurso_sistema = $vo_recurso_sistema->ler_lista(['recurso_sistema_id' => $ps_recurso_sistema_id], "lista");

                if (count($va_recurso_sistema)) 
                {
                    $_SESSION[$ps_recurso_sistema_id . "_recurso_sistema_codigo"] = $va_recurso_sistema[0]["recurso_sistema_codigo"];
                    return $va_recurso_sistema[0]["recurso_sistema_codigo"];
                }
            }
        }

        return "";
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $vo_recurso_sistema = new recurso_sistema();
        $va_recursos_sistema = $vo_recurso_sistema->ler_lista(["recurso_sistema_metadado_codigo_0_metadado_recurso_sistema_codigo" => $pn_recurso_sistema_codigo]);

        foreach ($va_recursos_sistema as $va_recurso_sistema) {
            $this->relacionamentos[$this->tabela_banco . '_' . $va_recurso_sistema["recurso_sistema_id"] . '_codigo'] = [
                [
                    $this->tabela_banco . '_' . $va_recurso_sistema["recurso_sistema_id"] . '_codigo'
                ],
                'tabela_intermediaria' => $va_recurso_sistema["recurso_sistema_id"],
                'chave_exportada' => $this->tabela_banco . '_codigo',
                'campos_relacionamento' => [
                    $this->tabela_banco . '_' . $va_recurso_sistema["recurso_sistema_id"] . '_codigo' => [
                        ['codigo'],
                        "atributo" => $va_recurso_sistema["recurso_sistema_id"] . "_codigo"
                    ]
                ],
                'tipos_campos_relacionamento' => ['i'],
                'tabela_relacionamento' => $va_recurso_sistema["recurso_sistema_id"],
                'objeto' => $va_recurso_sistema["recurso_sistema_id"],
                'tipo' => '1n',
                'alias' => strtolower($va_recurso_sistema["recurso_sistema_nome_plural"])
            ];
        }
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();
        return $va_campos_edicao;
    }

    public function inicializar_form_edicao()
    {
        $va_form_edicao = array();
        return $va_form_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        if ($this->pode_ser_incluido_selecao) {

            $va_filtros_navegacao["item_selecao_codigo"] = [
                "html_combo_input",
                "nome" => "item_selecao_codigo",
                "label" => "Seleção",
                "objeto" => "selecao",
                "atributos" => ["selecao_codigo", "selecao_nome"],
                "atributo" => "selecao_codigo",
                "sem_valor" => true,
                "operador_filtro" => "=",
                "css-class" => "form-select",
                "filtro" => [
                    [
                        "atributo" => "selecao_usuario_codigo",
                        "operador" => "=",
                        "valor" => $_SESSION["usuario_logado_codigo"]
                    ],
                    [
                        "atributo" => "selecao_recurso_sistema_codigo",
                        "operador" => "=",
                        "valor" => $this->recurso_sistema_codigo
                    ]
                ]
            ];
        }

        $this->filtros_navegacao = $va_filtros_navegacao;

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        
        $va_campos_visualizacao["representante_digital_codigo"] = [
            "nome" => "representante_digital_codigo",
            "formato" => ["campo" => "representante_digital_path"]
        ];

        $va_campos_visualizacao["arquivo_download_codigo"] = [
            "nome" => "arquivo_download_codigo",
            "formato" => ["campo" => "representante_digital_path"]
        ];
        

        $this->visualizacoes["lista"]["campos"] = array();
        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;

        //$va_campos_visualizacao["item_selecao_codigo"] = ["nome" => "item_selecao_codigo"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo='')
    {
        return array();
    }

    public function get_recurso_sistema_codigo()
    {
        return $this->recurso_sistema_codigo;
    }

    public function get_tabela_banco()
    {
        return $this->inicializar_tabela_banco();
    }

    public function get_chave_primaria()
    {
        return $this->chave_primaria;
    }

    public function get_objeto_pai()
    {
        return $this->objeto_pai;
    }

    public function get_campo_relacionamento_pai()
    {
        return $this->campo_relacionamento_pai;
    }

    public function get_atributos()
    {
        return $this->inicializar_atributos();
    }

    public function get_atributo_identificador()
    {
        foreach ($this->atributos as $va_atributo)
        {
            if (isset($va_atributo["identificador"]) && $va_atributo["identificador"])
                return $va_atributo[0];
        }

        if ($this->objeto_pai)
        {
            $vo_objeto_pai = new $this->objeto_pai;
            
            return $vo_objeto_pai->get_atributo_identificador();
        }

        return false;
    }

    public function get_numero_registros_por_objeto()
    {
        return $this->numero_registros_por_objeto;
    }

    public function ler_recurso_sistema($ps_recurso_sistema_id = '')
    {
        if ($ps_recurso_sistema_id) {
            $vo_recurso_sistema = new recurso_sistema();
            $va_recurso_sistema = $vo_recurso_sistema->ler_lista(['recurso_sistema_id' => $ps_recurso_sistema_id], "ficha");

            if (count($va_recurso_sistema)) {
                return $va_recurso_sistema[0];
            }
        }

        return array();
    }

    public function get_relacionamentos($pn_recurso_sistema_codigo)
    {
        $va_relacionamentos = array();

        if ($this->tem_representante_digital && $pn_recurso_sistema_codigo) {
            $va_relacionamentos['representante_digital_codigo'] = [
                [
                    'representante_digital_codigo',
                    'representante_digital_recurso_sistema_codigo',
                    'representante_digital_tipo',
                    'representante_digital_formato',
                    'representante_digital_path',
                    'representante_digital_sequencia',
                    'representante_digital_publicado_online'
                ],
                'tabela_intermediaria' => 'representante_digital',
                'chave_exportada' => 'registro_codigo',
                'campos_relacionamento' => [
                    'representante_digital_codigo' => 'codigo',
                    'representante_digital_recurso_sistema_codigo' => ['recurso_sistema_codigo', $pn_recurso_sistema_codigo],
                    'representante_digital_tipo' => ['tipo', 1],
                    'representante_digital_formato' => 'formato',
                    'representante_digital_path' => 'path',
                    'representante_digital_sequencia' => 'sequencia',
                    'representante_digital_publicado_online' => 'publicado_online'
                ],
                'tipos_campos_relacionamento' => ['i', 'i', 'i', 's', 'i', 'b'],
                //'objeto' => 'representante_digital',
                'alias' => 'representantes digitais'
            ];
        
            $va_relacionamentos['arquivo_download_codigo'] = [
                [
                    'arquivo_download_codigo',
                    'representante_digital_recurso_sistema_codigo',
                    'representante_digital_tipo',
                    'representante_digital_formato',
                    'representante_digital_path',
                    'representante_digital_sequencia',
                    'representante_digital_publicado_online'
                ],
                'tabela_intermediaria' => 'representante_digital',
                'chave_exportada' => 'registro_codigo',
                'campos_relacionamento' => [
                    'arquivo_download_codigo' => 'codigo',
                    'representante_digital_recurso_sistema_codigo' => ['recurso_sistema_codigo', $pn_recurso_sistema_codigo],
                    'representante_digital_tipo' => ['tipo', 2],
                    'representante_digital_formato' => 'formato',
                    'representante_digital_path' => 'path',
                    'representante_digital_sequencia' => 'sequencia',
                    'representante_digital_publicado_online' => 'publicado_online'
                ],
                'tipos_campos_relacionamento' => ['i', 'i', 'i', 's', 'i', 'b'],
                //'objeto' => 'representante_digital',
                'alias' => 'arquivos para download'
            ];
        }

        /*
    $va_relacionamentos['etapa_fluxo_codigo'] = [
        ['etapa_fluxo_codigo'],
        'tabela_intermediaria' => 'registro_etapa_fluxo',
        'chave_exportada' => 'registro_codigo',
        'campos_relacionamento' => ['etapa_fluxo_codigo' => 'etapa_fluxo_codigo'],
        'tipos_campos_relacionamento' => ['i'],
        "filtros" => ["recurso_sistema_codigo" => $pn_recurso_sistema_codigo]
    ];
    */

    if ($pn_recurso_sistema_codigo)
    {
        $va_relacionamentos['item_selecao_codigo'] = [
            [
                'item_selecao_codigo',
            ],
            'tabela_intermediaria' => 'selecao_item',
            'chave_exportada' => 'item_codigo',
            'campos_relacionamento' => [
                'item_selecao_codigo' => 'selecao_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            "filtros" => ["selecao_recurso_sistema_codigo" => $pn_recurso_sistema_codigo],
            'tabela_relacionamento' => 'selecao',
            'objeto' => 'selecao',
            'alias' => 'seleções'
        ];
    }

        return $va_relacionamentos;
    }

    public function get_relacionamento($pn_recurso_sistema_codigo, $ps_id_relacionamento)
    {
        $va_relacionamentos = $this->get_relacionamentos($pn_recurso_sistema_codigo);

        if (isset($va_relacionamentos[$ps_id_relacionamento]))
            return $va_relacionamentos[$ps_id_relacionamento];
        else
            return array();
    }

    public function get_registros_filhos()
    {
        return $this->registros_filhos;
    }

    public function get_visualizacao($ps_visualizacao)
    {
        if (intval($ps_visualizacao) && !isset($this->visualizacoes[$ps_visualizacao])) {
            $vo_visualizacao = new visualizacao;
            $va_visualizacao = $vo_visualizacao->ler($ps_visualizacao, "ficha");

            if (count($va_visualizacao)) {
                $this->visualizacoes[$ps_visualizacao]["campos"][$this->chave_primaria[0]] = $this->visualizacoes["ficha"]["campos"][$this->chave_primaria[0]];

                if (isset($this->visualizacoes["navegacao"]["order_by"]))
                    $this->visualizacoes[$ps_visualizacao]["order_by"] = $this->visualizacoes["navegacao"]["order_by"];

                foreach ($va_visualizacao["visualizacao_campo_sistema_codigo"] as $va_campo_sistema)
                {
                    foreach ($this->visualizacoes["ficha"]["campos"] as $ps_key_campo_visualizacao => $va_campo_visualizacao) 
                    {
                        $va_campo_sistema_nome = explode("_0_", $va_campo_sistema["visualizacao_campo_sistema_codigo"]["campo_sistema_nome"]);

                        if ($ps_key_campo_visualizacao == $va_campo_sistema_nome[0])
                        {
                            $this->visualizacoes[$ps_visualizacao]["campos"][$ps_key_campo_visualizacao] = $va_campo_visualizacao;
                            $this->visualizacoes[$ps_visualizacao]["ordem_campos"][$va_campo_sistema["visualizacao_campo_sistema_codigo"]["campo_sistema_nome"]] = $va_campo_sistema["visualizacao_campo_sistema_codigo"]["campo_sistema_alias"];
                        }
                    }

                    if (
                        isset($va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]) 
                        &&
                        isset($this->visualizacoes["ficha"]["campos"][$va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]])
                    )
                    {
                        $this->visualizacoes[$ps_visualizacao]["campos"][$va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]] = $this->visualizacoes["ficha"]["campos"][$va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]];
                    }
                }
            }
        }

        if (isset($this->visualizacoes[$ps_visualizacao]))
            return $this->visualizacoes[$ps_visualizacao];
        else
            return $this->visualizacoes["navegacao"];
    }

    public function get_campos_visualizacao($ps_visualizacao)
    {
        return $this->visualizacoes[$ps_visualizacao]["campos"];
    }

    public function get_campos_edicao($pn_objeto_codigo = '', $pn_bibliografia_codigo = '', $pa_valores_objeto = array())
    {
        if (count($this->campos_edicao))
            return $this->campos_edicao;
        else
            return $this->inicializar_campos_edicao($pn_objeto_codigo, $pn_bibliografia_codigo, $pa_valores_objeto);
    }

    public function get_subcampos($ps_nome_campo, $pn_linha_codigo = '')
    {
        $va_subcampos = array();
        $va_campos_edicao = $this->inicializar_campos_edicao();

        // Por conveniência, vamos excluir aqui os sufixos numéricos
        // que eventualmente venham passados em $ps_nome_campo
        // (subcampos no interior de um subcampo)
        // Concatena todos os códigos de linha de subcampos em cascata
        ////////////////////////////////////////////////////////////

        $va_codigos_linha = array();
        $vn_posicao_separador = strripos($ps_nome_campo, "_");

        while ($vn_posicao_separador !== false) {
            $vn_sufixo = substr($ps_nome_campo, ($vn_posicao_separador + 1), (strlen($ps_nome_campo) - $vn_posicao_separador - 1));

            if (intval($vn_sufixo)) {
                $ps_nome_campo = substr($ps_nome_campo, 0, $vn_posicao_separador);
                $va_codigos_linha[] = $vn_sufixo;

                $vn_posicao_separador = strripos($ps_nome_campo, "_");
            } else
                break;
        }

        $va_codigos_linha[] = $pn_linha_codigo;
        $pn_linha_codigo = implode("_", $va_codigos_linha);

        ////////////////////////////////////////////////////////////

        $va_campos = explode("_0_", $ps_nome_campo);

        // Antes de qualquer coisa, preciso ver se o campo identificado por
        // $va_campos[0] possui subcampos. Se não possui, já retorna array vazio
        /////////////////////////////////////////////////////////////////////////////

        if (!isset($va_campos_edicao[$va_campos[0]]["subcampos"]))
            return $va_subcampos;

        /////////////////////////////////////////////////////////////////////////////

        $vs_nome_campo_temp = "";
        foreach ($va_campos as $vs_nome_campo) {
            if ($vs_nome_campo_temp)
                $vs_nome_campo = $vs_nome_campo_temp . "_0_" . $vs_nome_campo;

            if (isset($va_campos_edicao[$vs_nome_campo]["subcampos"])) {
                $va_campos_edicao = $va_campos_edicao[$vs_nome_campo]["subcampos"];
                $vs_nome_campo_temp = $vs_nome_campo;
            }
        }

        foreach ($va_campos_edicao as $vs_key_subcampo => $va_subcampo) {
            $va_subcampo["sufixo_nome"] = "_" . $pn_linha_codigo;
            $va_subcampos[$vs_key_subcampo] = $va_subcampo;
        }

        return $va_subcampos;
    }

    public function get_form_edicao($ps_modo_form = "completo")
    {
        if (count($this->form_edicao))
            return $this->form_edicao;
        else
            return $this->inicializar_form_edicao($ps_modo_form);
    }

    public function get_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        return $this->inicializar_filtros_navegacao($pn_bibliografia_codigo);
    }

    public function get_campos_importacao()
    {
        return $this->campos_importacao;
    }

    public function get_campo_hierarquico()
    {
        return $this->campo_hierarquico;
    }

    public function get_filtros_selecao()
    {
        return $this->filtros_selecao;
    }

    public function get_objetos()
    {
        return $this->objetos;
    }

    public function inicializar_variaveis_banco()
    {
        $this->va_campos = array();
        $this->va_joins = array();
        $this->va_wheres = array();
        $this->va_tipos_parametros = array();
        $this->va_parametros = array();
        $this->va_order_by = array();
    }

    public function ler_proximo_codigo($ps_tabela)
    {
        $va_campos_select = array();
        $va_campos_select[] = "(MAX(" . $ps_tabela . ".codigo) + 1) as codigo";

        $va_selects[] = [
            "tabela" => $ps_tabela,
            "campos" => $va_campos_select,
        ];

        $vo_banco = $this->get_banco();
        $va_resultado = $vo_banco->consultar($va_selects);

        if ($va_resultado[0]["codigo"] == null)
            return 1;
        else
            return $va_resultado[0]["codigo"];
    }

    public function ler_proximo_numero_sequencia_representante_digital($pn_objeto_codigo, $pn_tipo)
    {
        $va_campos_select = array();
        $va_wheres_select = array();
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();

        $va_campos_select[] = "(MAX(sequencia) + 1) as sequencia";

        $va_wheres_select[] = "representante_digital.recurso_sistema_codigo = (?)";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $this->recurso_sistema_codigo;

        $va_wheres_select[] = "representante_digital.registro_codigo = (?)";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $pn_objeto_codigo;

        $va_wheres_select[] = "representante_digital.tipo = (?)";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $pn_tipo;

        $va_selects[] = [
            "tabela" => "representante_digital",
            "campos" => $va_campos_select,
            "wheres" => $va_wheres_select,
        ];

        $vo_banco = $this->get_banco();
        $va_resultado = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select);

        if ($va_resultado[0]["sequencia"] == null)
            return 1;
        else
            return $va_resultado[0]["sequencia"];
    }

    public function verificar_registro_existe($ps_tabela, $ps_chave_primaria, $pn_objeto_codigo)
    {
        $va_campos_select = array();
        $va_wheres_select = array();
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();

        $va_campos_select[] = $ps_chave_primaria . " as codigo";
        $va_wheres_select[] = $ps_tabela . "." . $ps_chave_primaria . " = (?)";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $pn_objeto_codigo;

        $va_selects[] = [
            "tabela" => $ps_tabela,
            "campos" => $va_campos_select,
            "wheres" => $va_wheres_select,
        ];

        $vo_banco = $this->get_banco();
        $va_resultado = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select);

        if (count($va_resultado))
            return true;
        else
            return false;
    }

    private function tratar_filtros_busca($pa_filtros_busca)
    {
        $va_filtros_busca_campo_union = array();

        foreach ($pa_filtros_busca as $vs_key_filtro_busca => $va_filtro_busca) {
            $va_campo_filtro_busca = explode(",", $vs_key_filtro_busca);

            if (count($va_campo_filtro_busca) > 1) {
                unset($pa_filtros_busca[$vs_key_filtro_busca]);

                $va_filtros_busca_campo_union_temp = array();
                foreach ($va_campo_filtro_busca as $vs_campo_filtro_busca) {
                    $va_filtros_busca_campo_union_temp[$vs_campo_filtro_busca] = $va_filtro_busca;
                }

                $va_filtros_busca_campo_union[] = $va_filtros_busca_campo_union_temp;
            }
        }

        $va_filtros_busca_union = array();
        if (count($va_filtros_busca_campo_union)) {
            $va_fitros_base_permutacao = array_shift($va_filtros_busca_campo_union);

            foreach ($va_fitros_base_permutacao as $vs_key_filtro_base_permutacao => $va_fitro_base_permutacao) {
                $va_filtros_busca_union_parte = $pa_filtros_busca;
                $va_filtros_busca_union_parte[$vs_key_filtro_base_permutacao] = $va_fitro_base_permutacao;

                if (count($va_filtros_busca_campo_union)) {
                    foreach ($va_filtros_busca_campo_union as $va_filtros_busca_campo_union_restantes) {
                        foreach ($va_filtros_busca_campo_union_restantes as $vs_key_filtro_busca_campo_union_restante => $va_filtro_busca_campo_union_restante) {
                            $va_filtros_busca_union_parte[$vs_key_filtro_busca_campo_union_restante] = $va_filtro_busca_campo_union_restante;

                            // Criar cada filtro de busca para cada parte do UNION
                            $va_filtros_busca_union[] = $va_filtros_busca_union_parte;

                            unset($va_filtros_busca_union_parte[$vs_key_filtro_busca_campo_union_restante]);
                        }
                    }
                } else
                    $va_filtros_busca_union[] = $va_filtros_busca_union_parte;
            }
        } else
            $va_filtros_busca_union[] = $pa_filtros_busca;

        return $va_filtros_busca_union;
    }

    public function ler_numero_registros($pa_filtros_busca = null, $pa_log_info = null, $pb_retornar_ramos_inferiores = true)
    {
        // Vamos tratar aqui o caso específico de o filtro de busca incluir mais de um campo
        ////////////////////////////////////////////////////////////////////////////////////

        $vn_numero_loops_filtros = 1;
        $va_filtros_busca = $pa_filtros_busca;

        if (isset($pa_filtros_busca)) {
            $va_filtros_busca_union = $this->tratar_filtros_busca($pa_filtros_busca);
            $vn_numero_loops_filtros = count($va_filtros_busca_union);
        }

        $this->inicializar_variaveis_banco();

        // Verifica se é um objeto que vai armazenar informações de vários objetos
        //////////////////////////////////////////////////////////////////////////

        if (count($this->objetos))
            $va_objetos = $this->objetos;
        else
            $va_objetos = array(get_class($this));

        $va_resultado = array();
        $vn_numero_registros = 0;
        $vo_banco = $this->get_banco();

        foreach ($va_objetos as $vs_objeto)
        {
            $contador = 0;
            $va_resultados_objeto = array();

            while ($contador < $vn_numero_loops_filtros) {
                if (isset($va_filtros_busca_union))
                    $va_filtros_busca = $va_filtros_busca_union[$contador];

                $va_selects = array();
                $va_tipos_parametros_select = array();
                $va_parametros_select = array();
                $va_campos_select = array();
                $va_joins_select = array();
                $va_wheres_select = array();

                $va_tabelas_adicionadas = array();

                if ($vs_objeto)
                    $vo_objeto = new $vs_objeto($vs_objeto);

                if (method_exists($vo_objeto, "get_filtros_interditados"))
                {
                    if (count(array_intersect(array_keys($va_filtros_busca), $vo_objeto->get_filtros_interditados())) > 0)
                    {
                        $contador++;
                        continue;
                    }
                }

                $va_tabelas_adicionadas[$vo_objeto->tabela_banco][] = $vo_objeto->tabela_banco;

                $va_campos_select[] = " DISTINCT " . $vo_objeto->tabela_banco . ".Codigo as " . $vo_objeto->tabela_banco . "_codigo";

                // Se algum atributo do objeto tiver um valor padrão, tem que filtrar por ele
                /////////////////////////////////////////////////////////////////////////////

                $va_atributos_objeto = $vo_objeto->atributos;

                foreach ($va_atributos_objeto as $va_atributo_objeto) 
                {
                    if (isset($va_atributo_objeto["valor_padrao"]) && !in_array(reset($va_atributo_objeto), $va_filtros_busca))
                    {
                        $va_filtros_busca[reset($va_atributo_objeto)] = $va_atributo_objeto["valor_padrao"];
                        $va_filtros_busca["concatenadores"][] = "AND";
                    }
                }

                $this->montar_filtros_busca($va_filtros_busca, $vo_objeto, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select, $va_tabelas_adicionadas, $pb_retornar_ramos_inferiores);

                $this->montar_parametros_log($pa_log_info, $vo_objeto, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select);

                $this->montar_parametros_fluxos($va_filtros_busca, $vo_objeto, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select);

                $va_selects[] = [
                    "tabela" => $vo_objeto->tabela_banco,
                    "campos" => $va_campos_select,
                    "joins" => $va_joins_select,
                    "wheres" => $va_wheres_select,
                    "concatenadores" => (isset($va_filtros_busca["concatenadores"]) ? $va_filtros_busca["concatenadores"] : array())
                ];

                if (count($va_selects))
                {
                    $va_resultado_temp = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, null, null, false);

                    foreach ($va_resultado_temp as $va_item) {
                        foreach ($va_item as $vs_key => $vn_codigo) {
                            $va_resultado[$vs_key . "_" . $vn_codigo] = $vn_codigo;
                            $va_resultados_objeto[$vs_key . "_" . $vn_codigo] = $vn_codigo;
                        }
                    }
                }

                $contador++;
            }

            if (count($va_resultados_objeto))
                $this->numero_registros_por_objeto[$vs_objeto] = count($va_resultados_objeto);
        }

        return count($va_resultado);
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista([$this->chave_primaria[0] => $pn_codigo], $ps_visualizacao, 0, 1, null, null, null, $pn_idioma_codigo, false);

        if (count($va_resultado)) {
            $va_resultado = $va_resultado[0];
            $this->codigo = $pn_codigo;
        }

        return $va_resultado;
    }

    private function montar_valores_busca($pa_parametro, &$pa_valores_busca = array(), &$ps_operador = "=", &$ps_interrogacoes = "(?)", &$ps_operador_logico = "AND")
    {
        if (!is_array($pa_parametro))
            $pa_parametro = array($pa_parametro);

        $vb_tem_valor = false;

        $va_valor_filtro = reset($pa_parametro);

        // Se veio o operador, ele é o segundo valor do array
        // Se não, o operador default é "="
        /////////////////////////////////////////////////////

        if (!isset($pa_parametro[1]))
            $ps_operador = "=";
        else
            $ps_operador = $pa_parametro[1];

        // Se o operador é o null safe equal, estamos comparando com null e deixa pra lá todo o resto

        if ($ps_operador == "<=>") {
            $pa_valores_busca = [null];
            $vb_tem_valor = true;
        } elseif ($ps_operador == "NOT") {
            $pa_valores_busca = [null];
            $vb_tem_valor = true;
        } elseif ($ps_operador == "_EXISTS_") {
            // $va_valor_filtro é 0 ou 1
            /////////////////////////////

            $pa_valores_busca[] = $va_valor_filtro;
            $vb_tem_valor = true;
        } else {
            // Verifica se estão vindo vários valores num mesmo filtro
            // e prepara a montagem de vários ? e parâmetros correspondentes

            if (isset($va_valor_filtro)) 
            {
                if (is_array($va_valor_filtro))
                {
                    // Se os valores do campo vêm como um array, então existe
                    // mais de um campo do mesmo tipo na interface

                    $va_valores_filtro = $va_valor_filtro;
                    $ps_operador_logico = "OR";
                }
                else
                    $va_valores_filtro = explode("|", $va_valor_filtro);

                $va_interrogracoes = array();

                foreach ($va_valores_filtro as $vs_valor_filtro) 
                {
                    if (trim($vs_valor_filtro ?? "") != "") 
                    {
                        $vb_tem_valor = true;
                        $va_interrogracoes[] = "?";

                        if (in_array($ps_operador, ["LIKE", "LIKERIGHT", "LIKELEFT"]))
                        {
                            $ps_operador_logico = "OR";

                            // Vamos separar sequência de palavras entre aspas
                            //////////////////////////////////////////////////

                            if (preg_match_all('/"([^"]+)"/', $vs_valor_filtro, $va_matches))
                            {
                                foreach ($va_matches[0] as $vs_match)
                                {
                                    $vs_valor_filtro = str_replace($vs_match, "", $vs_valor_filtro);
                                }
                            }

                            $va_valores_filtro_like = explode(" ", $vs_valor_filtro);
                            $va_valores_filtro_like = array_merge($va_valores_filtro_like, $va_matches[1]);
                            $va_novos_valores_filtro_like = array();

                            foreach ($va_valores_filtro_like as &$vs_valor_filtro_like)
                            {
                                if (trim($vs_valor_filtro_like) != "")
                                {
                                    if ($ps_operador == "LIKE")
                                        $vs_valor_filtro_like = "%" . $vs_valor_filtro_like . "%";

                                    elseif ($ps_operador == "LIKERIGHT")
                                    {
                                        $vs_valor_filtro_like =  $vs_valor_filtro_like . "%";
                                        $ps_operador = "LIKE";
                                    }
                                    elseif ($ps_operador == "LIKELEFT")
                                    {
                                        $vs_valor_filtro_like = "%" . $vs_valor_filtro_like;
                                        $ps_operador = "LIKE";
                                    }

                                    $va_novos_valores_filtro_like[] = $vs_valor_filtro_like;
                                }
                            }

                            $pa_valores_busca[] = $va_novos_valores_filtro_like;
                        }
                        else
                            $pa_valores_busca[] = $vs_valor_filtro;
                    }
                }

                if ( (count($va_interrogracoes) > 1) && ($ps_operador != "LIKE") )
                {
                    $ps_interrogacoes = "(" . join(",", $va_interrogracoes) . ")";

                    if ($ps_operador != "NOT IN")
                        $ps_operador = "IN";
                }
            }
        }

        return $vb_tem_valor;
    }

    private function montar_filtros_busca($pa_filtros_busca, $po_objeto, &$pa_joins_select = array(), &$pa_wheres_select = array(), &$pa_tipos_parametros_select = array(), &$pa_parametros_select = array(), &$pa_tabelas_adicionadas = array(), $pb_retornar_ramos_inferiores = true, &$pa_joins_trail = array(), $pn_primeiro_registro = 0, $pn_numero_registros = 0)
    {
        if (isset($pa_filtros_busca)) 
        {
            // Para cada um dos filtros...
            //////////////////////////////

            foreach ($pa_filtros_busca as $vs_parametro_nome => $va_parametro) 
            {
                $pa_joins_trail["current_trail"] = $pa_tabelas_adicionadas[0] ?? "";

                // Tentativa: restringir pela existência de um relacionamento (hardcoded)
                /////////////////////////////////////////////////////////////////////////

                if ($vs_parametro_nome == "tipo_relacionamento") 
                {
                    if (isset($po_objeto->relacionamentos[$va_parametro])) {
                        $va_relacionamento = $po_objeto->relacionamentos[$va_parametro];

                        $vs_tabela_join = $va_relacionamento["tabela_intermediaria"];
                        $vs_chave_primaria = $po_objeto->chave_primaria["coluna_tabela"];
                        $vs_chave_exportada = $va_relacionamento["chave_exportada"];

                        if (!in_array($vs_tabela_join, $pa_joins_select)) {
                            $pa_joins_select[$vs_tabela_join] = " JOIN " . $vs_tabela_join . " ON " . $po_objeto->tabela_banco . "." . $vs_chave_primaria . " = " . $vs_tabela_join . "." . $vs_chave_exportada;
                            $pa_tabelas_adicionadas[$vs_tabela_join] = $vs_tabela_join;
                        }

                        continue;
                    }
                }
                elseif (substr($vs_parametro_nome, 0, 6) == "LENGTH")
                {
                    $vs_parametro_nome = explode(":", $vs_parametro_nome)[1];

                    if (isset($po_objeto->atributos[$vs_parametro_nome]))
                    {
                        $pa_wheres_select[] = "LENGTH(".$po_objeto->atributos[$vs_parametro_nome]["coluna_tabela"].")" . " = (?) ";
                        $pa_tipos_parametros_select[] = "i";
                        $pa_parametros_select[] = $va_parametro;
                    }

                    continue;
                }

                // FIM
                // Tentativa: restringir pela existência de um relacionamento
                /////////////////////////////////////////////////////////////

                // Se o valor do parâmetro não vêm como array, transforma em arrray
                ///////////////////////////////////////////////////////////////////

                if (!is_array($va_parametro))
                    $va_parametro = array($va_parametro, "=");

                if (is_array($va_parametro)) 
                {
                    // É array e não está vazio...
                    //////////////////////////////

                    if (count($va_parametro)) 
                    {
                        $va_valor_filtro = reset($va_parametro);

                        $va_valores_busca = array();
                        $vs_operador = "";
                        $vs_interrogacoes = "(?)";
                        $vs_operador_logico = "AND";
                        $vb_tem_valor = false;

                        //Vamos tratar as DATAS
                        // Primeiro para o caso de a data ser atributo do próprio objeto
                        //if ( ($va_valor_filtro == "_data_") && (isset($po_objeto->atributos[$vs_parametro_nome])) )
                        /////////////////////////////////////////////////////////////////////////////////////////////

                        if ($va_valor_filtro == "_data_") 
                        {
                            // O primeiro valor do array (reset), dá os valores
                            // O segundo dá o operador
                            ///////////////////////////////////////////////////

                            $vo_data = new Periodo;

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_dia_inicial"]))
                                $vo_data->set_dia_inicial($pa_filtros_busca[$vs_parametro_nome . "_dia_inicial"]);

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_mes_inicial"]))
                                $vo_data->set_mes_inicial($pa_filtros_busca[$vs_parametro_nome . "_mes_inicial"]);

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_ano_inicial"]))
                                $vo_data->set_ano_inicial($pa_filtros_busca[$vs_parametro_nome . "_ano_inicial"]);

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_dia_final"]))
                                $vo_data->set_dia_final($pa_filtros_busca[$vs_parametro_nome . "_dia_final"]);

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_mes_final"]))
                                $vo_data->set_mes_final($pa_filtros_busca[$vs_parametro_nome . "_mes_final"]);

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_ano_final"]))
                                $vo_data->set_ano_final($pa_filtros_busca[$vs_parametro_nome . "_ano_final"]);

                            $vo_data->consolidar();

                            if ($vo_data->get_data_inicial())
                                $va_valores_busca[] = $vo_data->get_data_inicial();

                            if ($vo_data->get_data_final())
                                $va_valores_busca[] = $vo_data->get_data_final();

                            if (isset($pa_filtros_busca[$vs_parametro_nome . "_sem_data"]))
                                $va_valores_busca[] = "_sem_data_";

                            if (count($va_valores_busca))
                                $vb_tem_valor = true;

                            // Se for um filtro de busca que pode aparecer mais de uma vez na tela
                            //////////////////////////////////////////////////////////////////////

                            $vs_campo = $vs_parametro_nome;
                            if (strpos($vs_parametro_nome, "_F_") !== false)
                                $vs_campo = substr($vs_parametro_nome, 0, strpos($vs_parametro_nome, "_F_"));

                            if (isset($po_objeto->atributos[$vs_campo])) 
                            {
                                $va_atributo = $po_objeto->atributos[$vs_campo]["coluna_tabela"];

                                if (isset($pa_filtros_busca[$vs_parametro_nome . "_ano_inicial"]) && $pa_filtros_busca[$vs_parametro_nome . "_ano_inicial"]) 
                                {
                                    if ($vo_data->get_data_inicial()) 
                                    {
                                        $pa_tipos_parametros_select[] = "s";
                                        $pa_parametros_select[] = $vo_data->get_data_inicial();
                                        $pa_wheres_select[] = $po_objeto->tabela_banco . "." . $va_atributo["data_inicial"] . " >= (?) ";
                                    }

                                    if ($vo_data->get_data_final()) 
                                    {
                                        $pa_tipos_parametros_select[] = "s";
                                        $pa_parametros_select[] = $vo_data->get_data_final();
                                        $pa_wheres_select[] = $po_objeto->tabela_banco . "." . $va_atributo["data_final"] . " <= (?) ";
                                    }
                                } 
                                elseif (isset($pa_filtros_busca[$vs_parametro_nome . "_sem_data"])) {
                                    $pa_tipos_parametros_select[] = "i";
                                    $pa_parametros_select[] = 1;
                                    $pa_wheres_select[] = $po_objeto->tabela_banco . "." . $va_atributo["sem_data"] . " = (?) ";
                                }
                            }
                        } else {
                            $vb_tem_valor = $this->montar_valores_busca($va_parametro, $va_valores_busca, $vs_operador, $vs_interrogacoes, $vs_operador_logico);
                        }

                        if ($vb_tem_valor) 
                        {
                            if (preg_match('/\w+(_F_\d+)$/', $vs_parametro_nome))
                                $vs_parametro_nome = substr($vs_parametro_nome, 0, strpos($vs_parametro_nome, "_F_"));
                            
                            $va_filtro = explode("_0_", $vs_parametro_nome);

                            // O terceiro parâmetro vazio quer dizer que ainda não foi feito nenhum join para este filtro
                            /////////////////////////////////////////////////////////////////////////////////////////////
                            
                            $this->montar_filtro_busca($va_filtro, $po_objeto, '', $va_valores_busca, $vs_operador, $vs_interrogacoes, $pa_joins_select, $pa_wheres_select, $pa_tipos_parametros_select, $pa_parametros_select, $pa_tabelas_adicionadas, $vs_operador_logico, $pb_retornar_ramos_inferiores, $pa_joins_trail, $pn_primeiro_registro, $pn_numero_registros);
                        }
                    }
                }
            }
        }
    }

    private function montar_filtro_busca($pa_filtro, $po_objeto, $ps_ultima_tabela_filtro, $pa_valores_busca, $ps_operador, $ps_interrogacoes, &$pa_joins_select = array(), &$pa_wheres_select = array(), &$pa_tipos_parametros_select = array(), &$pa_parametros_select = array(), &$pa_tabelas_adicionadas = array(), $ps_operador_logico = 'AND', $pb_retornar_ramos_inferiores = true, &$pa_joins_trail = array(), $pn_primeiro_registro = 0, $pn_numero_registros = 0)
    {
        // A partir daqui, vamos procurar o campo/atributo ao qual o filtro se refere
        ////////////////////////////////////////////////////////////////////////////

        // Temos que começar a considerar a possibilidade de o nome do filtro ser composto
        // E vamos descobrir aqui que tipo de filtro é esse
        //////////////////////////////////////////////////////////////////////////////////

        $vb_chave_primaria = false;
        $vb_atributo_objeto = false;
        $vb_relacionamento_objeto = false;
        $vb_atributo_chave_estrangeira = false;
        $vb_relacionamento_chave_estrangeira = false;
        $vb_atributo_relacionamento_objeto = false;

        $va_filtro = $pa_filtro;

        if ($this->recurso_sistema_codigo)
            $po_objeto->relacionamentos = array_merge($po_objeto->relacionamentos, $this->get_relacionamentos($this->recurso_sistema_codigo));

        if (count($va_filtro) > 1) 
        {
            // Aqui vamos verificar se o filtro é atributo de uma chave estrangeira ou relacionamento
            // Para simplificar, vamos chamar ambos de relacionamento
            /////////////////////////////////////////////////////////////////////////////////////////

            $va_novo_filtro = array_slice($va_filtro, 1);
            $vs_id_objeto = "";
            $vb_filtro_chave_estrangeira_objeto = false;
            $vb_filtro_relacionamento_objeto = false;
            $vb_filtro_atributo_pai_objeto = false;

            if (($po_objeto->chave_primaria[0] == $va_filtro[0]) && ($po_objeto->campo_relacionamento_pai == $va_filtro[0])) 
            {
                // Se a chave primária do objeto é idêntica ao campo de relacionamento com o pai
                // Caso de herança usando apenas 1 tabela
                ////////////////////////////////////////////////////////////////////////////////

                $vo_objeto_pai = new $po_objeto->objeto_pai($po_objeto->objeto_pai);

                $this->montar_filtro_busca($va_novo_filtro, $vo_objeto_pai, $ps_ultima_tabela_filtro, $pa_valores_busca, $ps_operador, $ps_interrogacoes, $pa_joins_select, $pa_wheres_select, $pa_tipos_parametros_select, $pa_parametros_select, $pa_tabelas_adicionadas, $ps_operador_logico, $pb_retornar_ramos_inferiores, $pa_joins_trail);
            } 
            elseif (isset($po_objeto->atributos[$va_filtro[0]]) || isset($po_objeto->chave_primaria[$va_filtro[0]])) 
            {
                // Se o filtro é chave primária, atributo
                // ou campo de relacionamento com o pai (atributo sem objeto)
                ////////////////////////////////////////////////////////////

                if (isset($po_objeto->atributos[$va_filtro[0]]["objeto"])) 
                {
                    $vb_filtro_chave_estrangeira_objeto = true;

                    $vs_id_objeto = $po_objeto->atributos[$va_filtro[0]]["objeto"];
                    $vs_campo_chave_importada = $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"];
                } 
                elseif ($po_objeto->campo_relacionamento_pai == $va_filtro[0]) 
                {
                    $vb_filtro_atributo_pai_objeto = true;

                    $vs_id_objeto = $po_objeto->objeto_pai;
                    $vs_campo_chave_importada = $po_objeto->campo_relacionamento_pai;
                }
            }
            elseif (isset($po_objeto->relacionamentos[$va_filtro[0]])) 
            {
                // Se o filtro corresponde a um relacionamento do objeto
                ////////////////////////////////////////////////////////

                if (isset($po_objeto->relacionamentos[$va_filtro[0]]["objeto"])) 
                {
                    // Relacionamento com objeto
                    ////////////////////////////

                    $vb_filtro_relacionamento_objeto = true;

                    $vs_id_objeto = $po_objeto->relacionamentos[$va_filtro[0]]["objeto"];
                    $vs_campo_chave_importada = $po_objeto->chave_primaria["coluna_tabela"];
                } 
                else 
                {
                    // Pode ser um relacionamento sem objeto
                    // Para na tabela intermediária e já adiciona o campo
                    /////////////////////////////////////////////////////

                    $vs_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["tabela_intermediaria"];

                    if (isset($pa_tabelas_adicionadas[$vs_tabela_join]))
                        $vs_alias_tabela_join = $vs_tabela_join . "_" . (count($pa_tabelas_adicionadas[$vs_tabela_join]) + 1);
                    else
                        $vs_alias_tabela_join = $vs_tabela_join . "_1";

                    $vs_campo_chave_importada = $po_objeto->chave_primaria["coluna_tabela"];
                    $vs_campo_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["chave_exportada"];
                    $vb_tem_idioma = $po_objeto->relacionamentos[$va_filtro[0]]["tem_idioma"] ?? false;
                    $v_campo_tabela = $po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"][$va_filtro[1]] ?? null;

                    if (is_array($v_campo_tabela))
                    {
                        $vs_campo_tabela = reset($v_campo_tabela);
                    }
                    elseif (!empty($v_campo_tabela))
                    {
                        $vs_campo_tabela = $v_campo_tabela;
                    }
                    elseif ($vb_tem_idioma && $va_filtro[1] == "idioma_codigo")
                    {
                        $vs_campo_tabela = "idioma_codigo";
                    }


                    $vs_tabela_filtro = $ps_ultima_tabela_filtro;

                    if (!$vs_tabela_filtro)
                        $vs_tabela_filtro = $po_objeto->tabela_banco;

                    if (!in_array($vs_alias_tabela_join, $pa_joins_select)) 
                    {
                        $vs_tipo_join = " JOIN ";
                        if ($ps_operador == "_EXISTS_")
                            $vs_tipo_join = " LEFT JOIN ";

                        $pa_joins_select[$vs_alias_tabela_join] = $vs_tipo_join . $vs_tabela_join . " AS " . $vs_alias_tabela_join . " ON " . $vs_tabela_filtro . "." . $vs_campo_chave_importada . " = " . $vs_alias_tabela_join . "." . $vs_campo_tabela_join;
                        $pa_tabelas_adicionadas[$vs_tabela_join][] = $vs_alias_tabela_join;
                    }

                    // Tem que verificar se o campo da tabela intermediária é simples
                    // ou pertence a um objeto

                    if (isset($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"][$va_filtro[1]]["objeto"]) && isset($va_filtro[2]))
                    {
                        // Uma simplificação necessária, por enquanto: somente possível busca por atributo imediato do objeto relacionado à coluna

                        $vo_objeto_relacionamento = new $po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"][$va_filtro[1]]["objeto"];

                        $vs_tabela_join_objeto_relacionamento = $vo_objeto_relacionamento->get_tabela_banco();

                        if (isset($pa_tabelas_adicionadas[$vs_tabela_join_objeto_relacionamento]))
                            $vs_alias_tabela_join_objeto_relacionamento = $vs_tabela_join_objeto_relacionamento . "_" . (count($pa_tabelas_adicionadas[$vs_tabela_join_objeto_relacionamento]) + 1);
                        else
                            $vs_alias_tabela_join_objeto_relacionamento = $vs_tabela_join_objeto_relacionamento . "_1";

                        if (!in_array($vs_alias_tabela_join_objeto_relacionamento, $pa_joins_select)) 
                        {
                            $pa_joins_select[$vs_alias_tabela_join_objeto_relacionamento] = " JOIN " . $vs_tabela_join_objeto_relacionamento . " AS " . $vs_alias_tabela_join_objeto_relacionamento . " ON " . $vs_alias_tabela_join . "." . $vs_campo_tabela . " = " . $vs_alias_tabela_join_objeto_relacionamento . ".codigo";
                            $pa_tabelas_adicionadas[$vs_tabela_join_objeto_relacionamento][] = $vs_alias_tabela_join_objeto_relacionamento;
                        }

                        $va_atributo_filtro_relacionamento = $vo_objeto_relacionamento->atributos[$va_filtro[2]];
                       
                        if ( ($ps_operador != "LIKE") && ($ps_operador_logico != "OR") )
                        {
                            if ($ps_operador == "_EXISTS_") {
                                $vb_valor_busca = reset($pa_valores_busca);
        
                                if (!$vb_valor_busca)
                                    $pa_wheres_select[] = $vs_alias_tabela_join_objeto_relacionamento . "." . $va_atributo_filtro_relacionamento["coluna_tabela"] . " IS NULL ";
                                else
                                    $pa_wheres_select[] = $vs_alias_tabela_join_objeto_relacionamento . "." . $va_atributo_filtro_relacionamento["coluna_tabela"] . " IS NOT NULL ";

                                unset($pa_valores_busca);
                            }
                            else
                                $pa_wheres_select[] = $vs_alias_tabela_join_objeto_relacionamento . "." . $va_atributo_filtro_relacionamento["coluna_tabela"] . " " . $ps_operador . " " . $ps_interrogacoes;
                        }

                        $va_or_conditions = array();
                        $va_and_conditions = array();

                        if (isset($pa_valores_busca))
                        {
                            foreach ($pa_valores_busca as $va_valor_busca) 
                            {
                                if (!is_array($va_valor_busca))
                                    $va_valor_busca = array($va_valor_busca);

                                foreach ($va_valor_busca as $vs_valor_busca)
                                {
                                    if ($ps_operador_logico == "OR")
                                        $va_and_conditions[] = $vs_alias_tabela_join_objeto_relacionamento . "." . $va_atributo_filtro_relacionamento["coluna_tabela"] . " " . $ps_operador . " (?)";

                                    $pa_parametros_select[] = $vs_valor_busca;
                                    $pa_tipos_parametros_select[] = $va_atributo_filtro_relacionamento["tipo_dado"];
                                }
                                
                                if ($ps_operador_logico == "OR")
                                    $va_or_conditions[] = implode(" AND ", $va_and_conditions);
                            }
                        }

                        if ($ps_operador_logico == "OR")
                            $pa_wheres_select[] = " (" . implode(" OR ", $va_or_conditions) . ") ";
                    }                     
                    else
                    {
                        $vn_index_tipo_campo = array_search($va_filtro[1], array_keys($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"]));
                        $vs_tipo_dado_campo = $po_objeto->relacionamentos[$va_filtro[0]]["tipos_campos_relacionamento"][$vn_index_tipo_campo];

                        if ($ps_operador_logico != "OR")
                        {
                            if ($ps_operador == "_EXISTS_") {
                                $vb_valor_busca = reset($pa_valores_busca);
        
                                if (!$vb_valor_busca)
                                    $pa_wheres_select[] = $vs_alias_tabela_join . "." . $vs_campo_tabela . " IS NULL ";
                                else
                                    $pa_wheres_select[] = $vs_alias_tabela_join . "." . $vs_campo_tabela . " IS NOT NULL ";

                                unset($pa_valores_busca);
                            }
                            else
                                $pa_wheres_select[] = $vs_alias_tabela_join . "." . $vs_campo_tabela . " " . $ps_operador . " " . $ps_interrogacoes;
                        }

                        $va_or_conditions = array();
                        
                        if (isset($pa_valores_busca))
                        {
                            foreach ($pa_valores_busca as $va_valor_busca) 
                            {
                                if (is_array($va_valor_busca))
                                {
                                    $va_and_conditions = array();

                                    foreach ($va_valor_busca as $vs_valor_busca)
                                    {
                                        $va_and_conditions[] = $vs_alias_tabela_join . "." . $vs_campo_tabela . " " . $ps_operador . " (?)";

                                        $pa_parametros_select[] = $vs_valor_busca;
                                        $pa_tipos_parametros_select[] = $vs_tipo_dado_campo;
                                    }

                                    $va_or_conditions[] = implode(" AND ", $va_and_conditions);
                                }
                                else
                                {
                                    if ($ps_operador_logico == "OR")
                                        $va_or_conditions[] = $vs_alias_tabela_join . "." . $vs_campo_tabela . " " . $ps_operador . " (?)";

                                    $pa_parametros_select[] = $va_valor_busca;
                                    $pa_tipos_parametros_select[] = $vs_tipo_dado_campo;
                                }
                            }
                        }

                        if ($ps_operador_logico == "OR")
                            $pa_wheres_select[] = " (" . implode(" OR ", $va_or_conditions) . ") ";
                    }
                }
            }

            if ($vs_id_objeto) 
            {
                $va_filtros_relacionamento = array();

                // $vs_tabela_proximo_filtro vai guardar o alias da última tabela adicionada para este filtro específico
                ////////////////////////////////////////////////////////////////////////////////////////////////////////

                if (!isset($vo_objeto_relacionamento))
                    $vo_objeto_relacionamento = new $vs_id_objeto($vs_id_objeto);

                if ($vb_filtro_chave_estrangeira_objeto || $vb_filtro_atributo_pai_objeto) 
                {
                    // Se é chave estrangeira ou o pai, o join é direto com a tabela do objeto
                    ////////////////////////////////////////////////////////////////////////////////////

                    $vs_tabela_join = $vo_objeto_relacionamento->tabela_banco;
                    $vs_campo_tabela_join = $vo_objeto_relacionamento->chave_primaria["coluna_tabela"];
                } 
                elseif ($vb_filtro_relacionamento_objeto) 
                {
                    // Se é relacionamento (nxn) ou (1xn), vamos fazer o join com a tabela intermediária
                    ///////////////////////////////////////////////////////////////////////////

                    $vs_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["tabela_intermediaria"];
                    $va_campos_relacionamento = $po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"];
                    $va_filtros_relacionamento = $po_objeto->relacionamentos[$va_filtro[0]]["filtros"] ?? array();

                    if (is_array($po_objeto->relacionamentos[$va_filtro[0]]["chave_exportada"]))
                        $vs_campo_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["chave_exportada"][0];
                    else
                        $vs_campo_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["chave_exportada"];
                }

                $vs_tabela_filtro = $ps_ultima_tabela_filtro;

                if (!$vs_tabela_filtro)
                    $vs_tabela_filtro = $po_objeto->tabela_banco;

                $vs_current_trail = $pa_joins_trail["current_trail"];

                if ($vs_current_trail)
                    $vs_new_trail_candidate = $vs_current_trail . ":" . $vs_tabela_join;
                else
                    $vs_new_trail_candidate = $vs_tabela_filtro . ":" . $vs_tabela_join;

                if (isset($pa_joins_trail[$vs_new_trail_candidate]))
                {
                    $vs_alias_tabela_join = $pa_joins_trail[$vs_new_trail_candidate];
                }
                else
                {
                    if (isset($pa_tabelas_adicionadas[$vs_tabela_join]))
                        $vs_alias_tabela_join = $vs_tabela_join . "_" . (count($pa_tabelas_adicionadas[$vs_tabela_join]) + 1);
                    else
                        $vs_alias_tabela_join = $vs_tabela_join . "_1";

                    $pa_joins_trail[$vs_new_trail_candidate] = $vs_alias_tabela_join;
                }

                $pa_joins_trail["current_trail"] = $vs_new_trail_candidate;

                $vs_ultima_tabela_filtro = $vs_alias_tabela_join;

                if (!in_array($vs_alias_tabela_join, $pa_joins_select)) 
                {
                    $pa_joins_select[$vs_alias_tabela_join] = " JOIN " . $vs_tabela_join . " as " . $vs_alias_tabela_join . " ON " . $vs_tabela_filtro . "." . $vs_campo_chave_importada . " = " . $vs_alias_tabela_join . "." . $vs_campo_tabela_join;

                    $pa_tabelas_adicionadas[$vs_tabela_join][] = $vs_alias_tabela_join;
                }

                foreach ($va_filtros_relacionamento as $vs_campo_filtro_relacionamento => $va_filtro_relacionamento)
                {
                    $va_valores_busca = array();
                    $vs_operador = "";
                    $vs_interrogacoes = " (?) ";

                    if (isset($va_campos_relacionamento[$vs_campo_filtro_relacionamento]))
                    {
                        if ($this->montar_valores_busca($va_filtro_relacionamento, $va_valores_busca, $vs_operador, $vs_interrogacoes))
                        {
                            if ($vs_operador == "NOT")
                                $pa_joins_select[$vs_alias_tabela_join] .= " AND " . $vs_operador . " " . $vs_alias_tabela_join . "." . $va_campos_relacionamento[$vs_campo_filtro_relacionamento][0] . " <=> " . $vs_interrogacoes;
                            else
                                $pa_joins_select[$vs_alias_tabela_join] .= " AND " . $vs_alias_tabela_join . "." . $va_campos_relacionamento[$vs_campo_filtro_relacionamento][0] . " " . $vs_operador . $vs_interrogacoes;

                            foreach ($va_valores_busca as $va_valor_busca)
                            {
                                $pa_parametros_select[] = $va_valor_busca;
                                $pa_tipos_parametros_select[] = "i";
                            }
                        }
                    }
                }

                if ($vb_filtro_relacionamento_objeto && isset($po_objeto->relacionamentos[$va_filtro[0]]["tabela_relacionamento"])) 
                {
                    $vs_tabela_relacionamento = $po_objeto->relacionamentos[$va_filtro[0]]["tabela_relacionamento"];

                    // Se é um autorrelacionamento usando a mesma tabela, não adiciona
                    // novamente a tabela com as informações do item relacionado
                    //////////////////////////////////////////////////////

                    if ($vs_tabela_relacionamento != $vs_tabela_join) {
                        if (isset($pa_tabelas_adicionadas[$vs_tabela_relacionamento]))
                            $vs_alias_tabela_relacionamento = $vs_tabela_relacionamento . "_" . (count($pa_tabelas_adicionadas[$vs_tabela_relacionamento]) + 1);
                        else
                            $vs_alias_tabela_relacionamento = $vs_tabela_relacionamento . "_1";

                        $vs_ultima_tabela_filtro = $vs_alias_tabela_relacionamento;

                        if (!in_array($vs_alias_tabela_relacionamento, $pa_joins_select)) {
                            if (!is_array(reset($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"])))
                                $vs_campo_tabela_join = reset($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"]);
                            else
                                $vs_campo_tabela_join = reset($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"])[0][0];

                            $pa_joins_select[$vs_alias_tabela_relacionamento] = " JOIN " . $vs_tabela_relacionamento . " as " . $vs_alias_tabela_relacionamento . " ON " . $vs_alias_tabela_join . "." . $vs_campo_tabela_join . " = " . $vs_alias_tabela_relacionamento . "." . $vo_objeto_relacionamento->chave_primaria["coluna_tabela"];
                            $pa_tabelas_adicionadas[$vs_tabela_relacionamento][] = $vs_alias_tabela_relacionamento;
                        }
                    }
                }

                $this->montar_filtro_busca($va_novo_filtro, $vo_objeto_relacionamento, $vs_ultima_tabela_filtro, $pa_valores_busca, $ps_operador, $ps_interrogacoes, $pa_joins_select, $pa_wheres_select, $pa_tipos_parametros_select, $pa_parametros_select, $pa_tabelas_adicionadas, $ps_operador_logico, $pb_retornar_ramos_inferiores, $pa_joins_trail);
            }
        } else {
            // Se o filtro for chave primária ou atributo da tabela do objeto, a montagem é simples
            ///////////////////////////////////////////////////////////////////////////////////////

            $vs_campo_tabela = "";

            $vs_tabela_filtro = $ps_ultima_tabela_filtro;
            if (!$vs_tabela_filtro)
                $vs_tabela_filtro = $po_objeto->tabela_banco;

            $vb_is_attribute = false;
            foreach ($po_objeto->atributos as $va_atributo) {
                if (isset($va_atributo["atributo_filtro"]) && ($va_atributo["atributo_filtro"] == $va_filtro[0])) {
                    $vb_is_attribute = true;
                    $va_filtro[0] = $va_atributo[0];
                    break;
                }
            }

            if ($po_objeto->chave_primaria[0] == $va_filtro[0]) 
            {
                $vs_tabela_banco = $po_objeto->tabela_banco;

                if ($ps_ultima_tabela_filtro)
                    $vs_tabela_banco = $ps_ultima_tabela_filtro;
                else
                    $vs_tabela_banco = $po_objeto->tabela_banco;

                $vs_campo_tabela = "codigo";
                $vs_tipo_dado_campo = "i";
            } 
            elseif (isset($po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]) || $vb_is_attribute || ($va_filtro[0] == $po_objeto->campo_hierarquico)) 
            {
                // Preciso descobrir se é uma chave estrangeira ou um campo simples da tabela do objeto
                ///////////////////////////////////////////////////////////////////////////////////////

                if (isset($po_objeto->atributos[$va_filtro[0]]["objeto"]))
                    $vo_objeto_coluna = new $po_objeto->atributos[$va_filtro[0]]["objeto"];
                else
                    $vo_objeto_coluna = $po_objeto;

                if ($po_objeto->atributos[$va_filtro[0]]["tipo_dado"] == "dt")
                {
                    if ($ps_operador == "_EXISTS_") 
                    {
                        $vb_valor_busca = reset($pa_valores_busca);

                        if (!$vb_valor_busca)
                            $pa_wheres_select[] = $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_inicial"] . " IS NULL ";
                        else
                            $pa_wheres_select[] = $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_inicial"] . " IS NOT NULL ";
                    }
                    elseif ($pa_valores_busca[0] == "_sem_data_")
                    {
                        $pa_wheres_select[] = $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_inicial"] . " IS NULL ";
                    }
                    elseif (isset($po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_final"]) || isset($pa_valores_busca[1]))
                    {
                        $vs_where = $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_inicial"] . " >= (?) ";

                        if (isset($po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_final"]))
                            $vs_where .= " AND " . $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_final"] . " <= (?) ";
                        else
                            $vs_where .= " AND " . $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_inicial"] . " <= (?) ";

                        $pa_wheres_select[] = $vs_where;

                        $pa_tipos_parametros_select[] = "s";
                        $pa_parametros_select[] = $pa_valores_busca[0];

                        $pa_tipos_parametros_select[] = "s";
                        $pa_parametros_select[] = $pa_valores_busca[1];
                    } 
                    else {
                        $pa_wheres_select[] = $vs_tabela_filtro . "." . $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"]["data_inicial"] . " = (?) ";

                        $pa_tipos_parametros_select[] = "s";
                        $pa_parametros_select[] = $pa_valores_busca[0];
                    }
                } else {
                    // Rolha temporária na enchente
                    // A tabela do atributo a ser comparado é a última do JOIN
                    // (para os casos em que existe alias de tabela)

                    if ($ps_ultima_tabela_filtro)
                        $vs_tabela_banco = $ps_ultima_tabela_filtro;
                    else
                        $vs_tabela_banco = $po_objeto->tabela_banco;

                    if (isset($po_objeto->atributos[$va_filtro[0]]))
                    {
                        $vs_campo_tabela = $po_objeto->atributos[$va_filtro[0]]["coluna_tabela"];
                        $vs_tipo_dado_campo = $po_objeto->atributos[$va_filtro[0]]["tipo_dado"];
                    }
                    elseif ($va_filtro[0] == $po_objeto->campo_hierarquico)
                    {
                        $vs_campo_tabela = $po_objeto->tabela_banco . "_superior_codigo";
                        $vs_tipo_dado_campo = "i";
                    }

                    if ($ps_operador == "_EXISTS_") 
                    {
                        $vb_valor_busca = reset($pa_valores_busca);

                        if (!$vb_valor_busca)
                            $pa_wheres_select[] = $vs_tabela_banco . "." . $vs_campo_tabela . " IS NULL ";
                        else
                            $pa_wheres_select[] = $vs_tabela_banco . "." . $vs_campo_tabela . " IS NOT NULL ";
                    }
                    //elseif (($pb_retornar_ramos_inferiores) && $vo_objeto_coluna->campo_hierarquico && ($va_filtro[0] != $po_objeto->campo_hierarquico))
                    elseif (($pb_retornar_ramos_inferiores) && $vo_objeto_coluna->campo_hierarquico && !is_null($pa_valores_busca[0]))
                    {
                        $this->adicionar_ramos_hierarquicos($po_objeto, $vo_objeto_coluna, $vs_campo_tabela, $vs_tipo_dado_campo, $pa_valores_busca, $ps_operador, $ps_interrogacoes, $ps_operador_logico, false, $pn_primeiro_registro, $pn_numero_registros);
                    }
                }
            } 
            elseif (isset($po_objeto->relacionamentos[$va_filtro[0]])) 
            {
                $vs_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["tabela_intermediaria"];
                $vs_campo_tabela_join = $po_objeto->relacionamentos[$va_filtro[0]]["chave_exportada"];
                $va_campos_relacionamento = $po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"];
                $va_filtros_relacionamento = $po_objeto->relacionamentos[$va_filtro[0]]["filtros"] ?? array();

                if (isset($po_objeto->relacionamentos[$va_filtro[0]]["objeto"]))
                {
                    if (class_exists($po_objeto->relacionamentos[$va_filtro[0]]["objeto"]))
                        $vo_objeto_tabela_intermediaria = new $po_objeto->relacionamentos[$va_filtro[0]]["objeto"];
                    else
                        $vo_objeto_tabela_intermediaria = new base_class($po_objeto->relacionamentos[$va_filtro[0]]["objeto"]);
                }

                if (isset($pa_tabelas_adicionadas[$vs_tabela_join]))
                    $vs_alias_tabela_join = $vs_tabela_join . "_" . (count($pa_tabelas_adicionadas[$vs_tabela_join]) + 1);
                else
                    $vs_alias_tabela_join = $vs_tabela_join . "_1";

                if (!in_array($vs_alias_tabela_join, $pa_joins_select)) 
                {
                    $vs_tipo_join = " JOIN ";

                    if ($ps_operador == "_EXISTS_") 
                    {
                        // Se o operador de busca for "_EXISTS_" os JOINS são diferentes
                        ////////////////////////////////////////////////////////////////

                        $vb_valor_busca = reset($pa_valores_busca);

                        if (!$vb_valor_busca) 
                        {
                            $vs_tipo_join = " LEFT JOIN ";

                            $pa_wheres_select[] = $vs_alias_tabela_join . "." . $vs_campo_tabela_join . " IS NULL ";
                        }
                    }
                    elseif (($pb_retornar_ramos_inferiores) && isset($vo_objeto_tabela_intermediaria) && $vo_objeto_tabela_intermediaria->campo_hierarquico)
                    {
                        $vs_campo = "codigo";
                        $vs_tipo_campo = "i";
                        
                        $this->adicionar_ramos_hierarquicos($this, $vo_objeto_tabela_intermediaria, $vs_campo, $vs_tipo_campo, $pa_valores_busca, $ps_operador, $ps_interrogacoes, $ps_operador_logico);
                    }

                    $pa_joins_select[$vs_alias_tabela_join] = $vs_tipo_join . $vs_tabela_join . " AS " . $vs_alias_tabela_join . " ON " . $vs_tabela_filtro . "." . $po_objeto->chave_primaria["coluna_tabela"] . " = " . $vs_alias_tabela_join . "." . $vs_campo_tabela_join;
                    $pa_tabelas_adicionadas[$vs_tabela_join][] = $vs_alias_tabela_join;

                    $contador = 0;

                    foreach ($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"] as $v_campo_relacionamento)
                    {
                        if (is_array($v_campo_relacionamento))
                        {
                            if (isset($v_campo_relacionamento[0]) && isset($v_campo_relacionamento[1]))
                            {
                                $pa_tipos_parametros_select[] = $po_objeto->relacionamentos[$va_filtro[0]]["tipos_campos_relacionamento"][$contador];
                                $pa_parametros_select[] = $v_campo_relacionamento[1];
                                $pa_joins_select[] = " AND " . $vs_alias_tabela_join . "." . $v_campo_relacionamento[0] . " = (?)";
                            }
                        }
                        $contador++;
                    }

                    
                }

                $vs_tabela_banco = $vs_alias_tabela_join;
                $vs_campo_tabela = reset($po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"]);
                if (is_array($vs_campo_tabela))
                {
                    $vs_campo_tabela = reset($vs_campo_tabela);
                }
                $vs_tipo_dado_campo = reset($po_objeto->relacionamentos[$va_filtro[0]]["tipos_campos_relacionamento"]);
            }

            if (isset($vs_tipo_dado_campo) && $vs_tipo_dado_campo == "dt")
            {
                $campo_data_inicial = $vs_tabela_banco . "." . $po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"][$va_filtro[0]]["data_inicial"] ?? null;
                $campo_data_final = $vs_tabela_banco . "." . $po_objeto->relacionamentos[$va_filtro[0]]["campos_relacionamento"][$va_filtro[0]]["data_final"] ?? null;

                if ($ps_operador == "_EXISTS_")
                {
                    $vb_valor_busca = reset($pa_valores_busca);
                    $condition = $vb_valor_busca ? " IS NOT NULL " : " IS NULL ";
                    $pa_wheres_select[] = $campo_data_inicial . $condition;
                }
                elseif ($pa_valores_busca[0] == "_sem_data_")
                {
                    $pa_wheres_select[] = $campo_data_inicial . " IS NULL ";
                }
                elseif (isset($campo_data_final) || isset($pa_valores_busca[1]))
                {
                    $vs_where = $campo_data_inicial . " >= (?) ";
                    $vs_where .= isset($campo_data_final) ? " AND " . $campo_data_final . " <= (?) " : " AND " . $campo_data_inicial . " <= (?) ";
                    $pa_wheres_select[] = $vs_where;

                    $pa_tipos_parametros_select[] = "s";
                    $pa_parametros_select[] = $pa_valores_busca[0];

                    if (isset($campo_data_final) || isset($pa_valores_busca[1]))
                    {
                        $pa_tipos_parametros_select[] = "s";
                        $pa_parametros_select[] = $pa_valores_busca[1];
                    }
                }
                else
                {
                    $pa_wheres_select[] = $campo_data_inicial . " = (?) ";
                    $pa_tipos_parametros_select[] = "s";
                    $pa_parametros_select[] = $pa_valores_busca[0];
                }
            }
            elseif (($vs_campo_tabela) && ($ps_operador != "_EXISTS_"))
            {
                if ($ps_operador == "NOT")
                    $pa_wheres_select[] = $ps_operador . " " . $vs_tabela_banco . "." . $vs_campo_tabela . "<=>" . $ps_interrogacoes;
                
                elseif ($ps_operador_logico != "OR")
                    $pa_wheres_select[] = $vs_tabela_banco . "." . $vs_campo_tabela . " " . $ps_operador . " " . $ps_interrogacoes;

                $va_or_conditions = array();
                
                foreach ($pa_valores_busca as $va_valor_busca) 
                {
                    if (is_array($va_valor_busca))
                    {
                        $va_and_conditions = array();

                        foreach ($va_valor_busca as $vs_valor_busca)
                        {
                            $va_and_conditions[] = $vs_tabela_banco . "." . $vs_campo_tabela . " " . $ps_operador . " (?)";

                            $pa_parametros_select[] = $vs_valor_busca;
                            $pa_tipos_parametros_select[] = $vs_tipo_dado_campo;
                        }

                        $va_or_conditions[] = implode(" AND ", $va_and_conditions);
                    }
                    else
                    {
                        if ($ps_operador_logico == "OR")
                            $va_or_conditions[] = $vs_tabela_banco . "." . $vs_campo_tabela . " " . $ps_operador . " (?)";

                        $pa_parametros_select[] = $va_valor_busca;
                        $pa_tipos_parametros_select[] = $vs_tipo_dado_campo;
                    }
                }

                if ($ps_operador_logico == "OR")
                    $pa_wheres_select[] = " (" . implode(" OR ", $va_or_conditions) . ") ";
            }
        }
    }

    private function adicionar_ramos_hierarquicos($po_objeto, $po_objeto_coluna, &$ps_campo_tabela, &$ps_tipo_dado_campo, &$pa_valores_busca, &$ps_operador, &$ps_interrogacoes, $ps_operador_logico, $pb_tabela_dados_textuais = false, $pn_primeiro_registro = 0, $pn_numero_registros = 0)
    {
        $this->banco_dados = $this->get_banco();

        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_campos_select = array();
        $va_wheres_select = array();

        if (!$pb_tabela_dados_textuais)
        {
            $vs_tabela_objeto = $po_objeto_coluna->tabela_banco;
            $vs_coluna_chave_primaria = "codigo";
        }
        else
        {
            $vs_tabela_objeto = $po_objeto_coluna->tabela_banco . "_dados_textuais";
            $vs_coluna_chave_primaria = $po_objeto_coluna->get_id() . "_codigo";
        }

        $va_campos_select[] = $vs_tabela_objeto . "." . $vs_coluna_chave_primaria . " as codigo";

        $vs_campo_tabela_objeto_coluna = $ps_campo_tabela;

        if ( ($po_objeto_coluna->tabela_banco != $po_objeto->tabela_banco) && !$pb_tabela_dados_textuais)
        {
            $vs_campo_tabela_objeto_coluna = "codigo";
        }
        
        if ($ps_operador == "NOT")
            $va_wheres_select[] = $ps_operador . " " . $vs_tabela_objeto . "." . $vs_campo_tabela_objeto_coluna . "<=>" . $ps_interrogacoes;

        elseif ($ps_operador_logico != "OR")
            $va_wheres_select[] = $vs_tabela_objeto . "." . $vs_campo_tabela_objeto_coluna . " " . $ps_operador . " " . $ps_interrogacoes;

        $va_or_conditions = array();

        foreach ($pa_valores_busca as $va_valor_busca) 
        {
            if (is_array($va_valor_busca))
            {
                $va_and_conditions = array();

                foreach ($va_valor_busca as $vs_valor_busca)
                {
                    $va_and_conditions[] = $vs_tabela_objeto . "." . $vs_campo_tabela_objeto_coluna . " " . $ps_operador . " (?)";

                    $va_parametros_select[] = $vs_valor_busca;
                    $va_tipos_parametros_select[] = $ps_tipo_dado_campo;
                }

                $va_or_conditions[] = implode(" AND ", $va_and_conditions);
            }
            else
            {
                if ($ps_operador_logico == "OR")
                    $va_or_conditions[] = $vs_tabela_objeto . "." . $vs_campo_tabela_objeto_coluna . " " . $ps_operador . " (?)";

                $va_parametros_select[] = $va_valor_busca;
                $va_tipos_parametros_select[] = $ps_tipo_dado_campo;
            }
        }

        if ($ps_operador_logico == "OR")
            $va_wheres_select[] = " (" . implode(" OR ", $va_or_conditions) . ") ";

        $va_selects[] = [
            "tabela" => $vs_tabela_objeto,
            "campos" => $va_campos_select,
            "wheres" => $va_wheres_select,
        ];

        $vs_limit = "";
        if ($pn_primeiro_registro)
            $vs_limit = " LIMIT " . ($pn_primeiro_registro - 1) . ", " . $pn_numero_registros;

        $va_objetos_match_filtro_busca = $this->banco_dados->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, null, $vs_limit);

        $pa_valores_busca = array();
        $va_interrogracoes = array();

        if (!count($va_objetos_match_filtro_busca))
        {
            $pa_valores_busca[] = 0;
            $va_interrogracoes[] = "?";
        }

        foreach ($va_objetos_match_filtro_busca as $va_objeto_match)
        {                            
            $pa_valores_busca[] = $va_objeto_match["codigo"];
            $va_interrogracoes[] = "?";

            $va_codigos_ramo_inferior = array();
            $va_codigos_ramo_superior = array();

            $va_codigos_ramo_inferior = $po_objeto_coluna->ler_codigos_ramo_inferior($va_objeto_match["codigo"], $po_objeto_coluna->campo_hierarquico);

            if ($po_objeto_coluna->tipo_hierarquia_codigo == 3)
                $va_codigos_ramo_superior = $po_objeto_coluna->ler_codigos_ramo_superior($va_objeto_match["codigo"], $po_objeto_coluna->campo_hierarquico);

            $va_codigos_ramos = array_merge($va_codigos_ramo_inferior, $va_codigos_ramo_superior);

            foreach ($va_codigos_ramos as $vn_codigo_no)
            {
                $pa_valores_busca[] = $vn_codigo_no;
                $va_interrogracoes[] = "?";
            }
        }

        if ($po_objeto_coluna->tabela_banco == $po_objeto->tabela_banco)
        {
            $ps_campo_tabela = $vs_coluna_chave_primaria;
            $ps_tipo_dado_campo = "i";
        }

        $ps_interrogacoes = "(" . join(",", $va_interrogracoes) . ")";

        if ($ps_operador != "NOT IN")
            $ps_operador = "IN";
    }

    private function montar_parametros_log($pa_log_info, $po_objeto, &$pa_joins_select = array(), &$pa_wheres_select = array(), &$pa_tipos_parametros_select = array(), &$pa_parametros_select = array())
    {
        if (!isset($pa_log_info))
            $pa_log_info = array();

        if (count($pa_log_info)) 
        {
            $pa_joins_select["log"] = " JOIN log ON " . $po_objeto->tabela_banco . "." . $po_objeto->chave_primaria["coluna_tabela"] . " = log.registro_codigo ";

            $pa_wheres_select[] = " log.id_registro = (?) ";
            $pa_tipos_parametros_select[] = "s";
            $pa_parametros_select[] = get_class($po_objeto);

            if (isset($pa_log_info["log_usuario_codigo"])) 
            {
                $pa_wheres_select[] = " log.usuario_codigo = (?) ";
                $pa_tipos_parametros_select[] = "i";
                $pa_parametros_select[] = $pa_log_info["log_usuario_codigo"];
            }

            if (isset($pa_log_info["log_data_inicial"])) 
            {
                if (isset($pa_log_info["log_data_final"]))
                {
                    $pa_wheres_select[] = " DATE(log.data) >= (?) ";
                    $pa_tipos_parametros_select[] = "s";
                    $pa_parametros_select[] = $pa_log_info["log_data_inicial"];
                
                    $pa_wheres_select[] = " DATE(log.data) <= (?) ";
                    $pa_tipos_parametros_select[] = "s";
                    $pa_parametros_select[] = $pa_log_info["log_data_final"];
                } 
                else 
                {
                    $pa_wheres_select[] = " DATE(log.data) = (?) ";
                    $pa_tipos_parametros_select[] = "s";
                    $pa_parametros_select[] = $pa_log_info["log_data_inicial"];
                }
            }

            if (isset($pa_log_info["log_tipo_operacao_codigo"])) 
            {
                $pa_wheres_select[] = " log.tipo_operacao_codigo = (?) ";
                $pa_tipos_parametros_select[] = "i";
                $pa_parametros_select[] = $pa_log_info["log_tipo_operacao_codigo"];
            }
        }
    }

    private function montar_parametros_fluxos($pa_filtros, $po_objeto, &$pa_joins_select = array(), &$pa_wheres_select = array(), &$pa_tipos_parametros_select = array(), &$pa_parametros_select = array())
    {
        if (!isset($pa_filtros))
            $pa_filtros = array();

        if (count($pa_filtros) && ($this->recurso_sistema_codigo)) {
            foreach ($pa_filtros as $va_key_filtro => $va_filtro) {
                if (substr($va_key_filtro, 0, 18) == "etapa_fluxo_codigo") {
                    $pa_joins_select[$va_key_filtro] = " JOIN registro_etapa_fluxo as " . $va_key_filtro . " ON " . $po_objeto->tabela_banco . "." . $po_objeto->chave_primaria["coluna_tabela"] . " = " . $va_key_filtro . ".registro_codigo ";

                    $pa_wheres_select[] = $va_key_filtro . ".recurso_sistema_codigo = (?) ";
                    $pa_tipos_parametros_select[] = "i";
                    $pa_parametros_select[] = $this->recurso_sistema_codigo;

                    $pa_wheres_select[] = $va_key_filtro . ".etapa_fluxo_codigo = (?) ";
                    $pa_tipos_parametros_select[] = "i";
                    $pa_parametros_select[] = $va_filtro[0];
                }
            }
        }
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 0, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        // Vamos tratar aqui o caso específico de o filtro de busca incluir mais de um campo
        ////////////////////////////////////////////////////////////////////////////////////

        $vn_numero_loops_filtros = 1;
        $va_filtros_busca = $pa_filtros_busca;

        if (isset($pa_filtros_busca)) {
            $va_filtros_busca_union = $this->tratar_filtros_busca($pa_filtros_busca);
            $vn_numero_loops_filtros = count($va_filtros_busca_union);
        }

        // Verifica se é um objeto que vai armazenar informações de vários objetos
        if (count($this->objetos))
            $va_objetos = $this->objetos;
        else
            $va_objetos = array(get_class($this));

        $va_resultado = array();
        $va_selects = array();
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_order_by = array();
        $va_group_by = array();

        // Se veio passado um termo de ordenação como string, transforma em array
        /////////////////////////////////////////////////////////////////////////

        if (!is_array($pa_order_by) && ($pa_order_by == ''))
            $pa_order_by = array();
        elseif (!is_array($pa_order_by) && ($pa_order_by != ''))
            $pa_order_by = array($pa_order_by);

        // Se não veio passado um termo de ordenação tenta atribuir a ordenação definida na visualização
        ////////////////////////////////////////////////////////////////////////////////////////////////

        if (!count($pa_order_by) && isset($this->visualizacoes[$ps_visualizacao]["order_by"]))
            $pa_order_by[] = array_keys($this->visualizacoes[$ps_visualizacao]["order_by"])[0];

        $va_objetos_instanciados = array();

        foreach ($va_objetos as $vs_objeto) 
        {
            $contador = 0;

            while ($contador < $vn_numero_loops_filtros) 
            {
                if (isset($va_filtros_busca_union))
                    $va_filtros_busca = $va_filtros_busca_union[$contador];

                if ($vs_objeto)
                    $vo_objeto = new $vs_objeto($vs_objeto);

                if (method_exists($vo_objeto, "get_filtros_interditados"))
                {
                    if (count(array_intersect(array_keys($va_filtros_busca), $vo_objeto->get_filtros_interditados())) > 0)
                    {
                        $contador++;
                        continue;
                    }
                }

                $va_objetos_instanciados[$vs_objeto] = $vo_objeto;

                $va_visualizacao = $vo_objeto->get_visualizacao($ps_visualizacao)["campos"];

                $vs_chave_primaria = $vo_objeto->chave_primaria[0];
                $vs_coluna_chave_primaria = "codigo";
                $vs_tabela_banco = $vo_objeto->tabela_banco;
                $va_atributos_objeto = $vo_objeto->atributos;

                $va_campos_select = array();
                $va_joins_select = array();
                $va_wheres_select = array();

                $va_tabelas_adicionadas = array();
                $va_tabelas_adicionadas[$vs_tabela_banco][] = $vs_tabela_banco;

                $va_joins_trail = [$vs_tabela_banco => $vs_tabela_banco];

                // Adiciono um campo na mão para identificar a qual objeto o registro pertence
                $va_campos_select["_objeto"] = "'" . $vs_objeto . "' as _objeto";

                $vb_achou_campo_relacionamento_pai = false;

                foreach ($va_visualizacao as $ps_key_campo_visualizacao => $va_campo_visualizacao) 
                {
                    $vb_achou_campo = false;

                    // Precisamos adicionar o campo de relacionamento do pai,
                    // mesmo se ele não vier na visualização

                    if ($this->campo_relacionamento_pai) {
                        if ($va_campo_visualizacao["nome"] == $this->campo_relacionamento_pai)
                            $vb_achou_campo_relacionamento_pai = true;
                    }

                    if ($va_campo_visualizacao["nome"] == $vs_chave_primaria) {
                        //Adiciona a chave primária
                        ///////////////////////////

                        $vs_alias_chave_primaria = $ps_key_campo_visualizacao;

                        $va_campos_select[$ps_key_campo_visualizacao] = $vs_tabela_banco . "." . $vs_coluna_chave_primaria . " as " . $ps_key_campo_visualizacao;

                        if (($pa_order_by == 2) && isset($pa_filtros_busca["tipo_relacionamento"]) && ($pa_filtros_busca["tipo_relacionamento"])) {
                            $vs_campo_quantidade = $vo_objeto->relacionamentos[$pa_filtros_busca["tipo_relacionamento"]]["campos_relacionamento"][$pa_filtros_busca["tipo_relacionamento"]];

                            $va_campos_select["Q"] = "COUNT(DISTINCT " . $vs_campo_quantidade . ") as Q ";
                            $va_group_by[] = $vs_chave_primaria;
                            $va_order_by[] = "Q DESC";
                        }

                        $vb_achou_campo = true;
                    } 
                    else 
                    {
                        // Primeiro, ver se o campo de visualização é atributo do próprio objeto
                        ////////////////////////////////////////////////////////////////////////

                        if (isset($va_atributos_objeto[$va_campo_visualizacao["nome"]])) 
                        {
                            if ($va_atributos_objeto[$va_campo_visualizacao["nome"]]["tipo_dado"] <> 'dt')
                                $va_campos_select[$ps_key_campo_visualizacao] = $vs_tabela_banco . "." . $va_atributos_objeto[$va_campo_visualizacao["nome"]]["coluna_tabela"] . " as " . $ps_key_campo_visualizacao;
                            else 
                            {
                                foreach ($va_atributos_objeto[$va_campo_visualizacao["nome"]]["coluna_tabela"] as $vs_key_atributo => $vs_atributo) 
                                {
                                    // Vamos retornar a data no formato pedido
                                    //////////////////////////////////////////

                                    if (isset($va_campo_visualizacao["formato"]["data"]) && $va_campo_visualizacao["formato"]["data"] == "distinct_ano") 
                                    {
                                        $va_campos_select[$ps_key_campo_visualizacao . "_" . $vs_key_atributo] = "YEAR(" . $vs_tabela_banco . "." . $vs_atributo . ") as " . $ps_key_campo_visualizacao . "_" . $vs_key_atributo;

                                        break;
                                    } 
                                    else
                                        $va_campos_select[$ps_key_campo_visualizacao . "_" . $vs_key_atributo] = $vs_tabela_banco . "." . $vs_atributo . " as " . $ps_key_campo_visualizacao . "_" . $vs_key_atributo;
                                }
                            }

                            $vb_achou_campo = true;
                        }

                        // Para o caso de UNIONs com objetos diferentes, preciso adicionar um campo
                        // "fake" para garantir o mesmo número de campos em cada SELECT

                        if (!$vb_achou_campo)
                            $va_campos_select[$ps_key_campo_visualizacao] = "NULL as " . $ps_key_campo_visualizacao;

                        if (!$vb_achou_campo) 
                        {
                            $va_campo = explode(".", $va_campo_visualizacao["nome"]);

                            if (count($va_campo) > 1) 
                            {
                                if (isset($va_atributos_objeto[$va_campo[0]])) 
                                {
                                    if (isset($va_atributos_objeto[$va_campo[0]]["objeto"])) 
                                    {
                                        $vs_id_objeto = $va_atributos_objeto[$va_campo[0]]["objeto"];
                                        $vo_objeto_relacionamento = new $vs_id_objeto($vs_id_objeto);

                                        $vs_tabela_join = $vo_objeto_relacionamento->tabela_banco;
                                        $vs_campo_tabela_join = $vo_objeto_relacionamento->chave_primaria["coluna_tabela"];

                                        $va_atributos_objeto_relacionamento = $vo_objeto_relacionamento->get_atributos();

                                        if (isset($va_atributos_objeto_relacionamento[$va_campo[1]])) 
                                        {
                                            if (!in_array($vs_tabela_join, $va_joins_select))
                                                $va_joins_select[$vs_tabela_join] = " JOIN " . $vs_tabela_join . " ON " . $vs_tabela_banco . "." . $va_atributos_objeto[$va_campo[0]]["coluna_tabela"] . " = " . $vs_tabela_join . "." . $vs_campo_tabela_join;

                                            if ($va_atributos_objeto_relacionamento[$va_campo[1]]["tipo_dado"] <> 'dt')
                                                $va_campos_select[$ps_key_campo_visualizacao] = $vs_tabela_join . "." . $va_atributos_objeto_relacionamento[$va_campo[1]]["coluna_tabela"] . " as " . $ps_key_campo_visualizacao;
                                            else {
                                                foreach ($va_atributos_objeto_relacionamento[$va_campo[1]]["coluna_tabela"] as $vs_key_atributo => $vs_atributo) {
                                                    //$va_campos_select[] = $vs_tabela_banco . "." . $vs_atributo  . " as " . $va_atributos_objeto[$va_campo_visualizacao["nome"]][0] . "_" . $vs_key_atributo;
                                                    $va_campos_select[$ps_key_campo_visualizacao . "_" . $vs_key_atributo] = $vs_tabela_join . "." . $vs_atributo . " as " . $ps_key_campo_visualizacao . "_" . $vs_key_atributo;
                                                }
                                            }

                                            $vb_achou_campo = true;
                                        }
                                    }
                                }
                            }
                        }

                        // Precisamos adicionar o campo de relacionamento do pai,
                        // mesmo se ele não vier na visualização
                        if ($this->campo_relacionamento_pai && !$vb_achou_campo_relacionamento_pai) 
                        {
                            $va_campos_select[$this->campo_relacionamento_pai] = $vs_tabela_banco . "." . $va_atributos_objeto[$this->campo_relacionamento_pai]["coluna_tabela"];
                            $vb_achou_campo_relacionamento_pai = true;
                            $vb_achou_campo = true;
                        }
                    }

                    // Para o caso de UNIONs com objetos diferentes, preciso adicionar um campo
                    // "fake" para garantir o mesmo número de campos em cada SELECT

                    if (!$vb_achou_campo)
                        $va_campos_select[$ps_key_campo_visualizacao] = "NULL as " . $ps_key_campo_visualizacao;
                }

                // Se algum atributo do objeto tiver um valor padrão, tem que filtrar por ele
                /////////////////////////////////////////////////////////////////////////////

                foreach ($va_atributos_objeto as $va_atributo_objeto) 
                {
                    if (isset($va_atributo_objeto["valor_padrao"]) && !in_array(reset($va_atributo_objeto), $va_filtros_busca ?? []))
                    {
                        $va_filtros_busca[reset($va_atributo_objeto)] = $va_atributo_objeto["valor_padrao"];
                    }
                }

                $this->montar_filtros_busca($va_filtros_busca, $vo_objeto, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select, $va_tabelas_adicionadas, $pb_retornar_ramos_inferiores, $va_joins_trail, $pn_primeiro_registro, $pn_numero_registros);

                $this->montar_parametros_log($pa_log_info, $vo_objeto, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select);

                $this->montar_parametros_fluxos($va_filtros_busca, $vo_objeto, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select);

                $va_order_by = $this->montar_ordenacao($vo_objeto, $pa_order_by, $va_campos_select, $va_joins_select, $ps_order, $va_tabelas_adicionadas);
    
                $va_selects[] = [
                    "tabela" => $vo_objeto->tabela_banco,
                    "campos" => $va_campos_select,
                    "joins" => $va_joins_select,
                    "wheres" => $va_wheres_select,
                    "concatenadores" => (isset($va_filtros_busca["concatenadores"]) ? $va_filtros_busca["concatenadores"] : array())
                ];

                $contador++;
            }
        }

        if (count($va_selects) > 0)
        {
            $vs_limit = "";
            if ($pn_primeiro_registro)
                $vs_limit = " LIMIT " . ($pn_primeiro_registro - 1) . ", " . $pn_numero_registros;

            $vo_banco = $this->get_banco();
            $va_resultado = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, $va_order_by, $vs_limit, true, $va_group_by);
        }

        // Agora vamos montar o objeto pai, atributos que
        // são chaves estrangeiras e relacionamentos
        //////////////////////////////////////////////////////////////

        if (count($va_resultado)) 
        {
            foreach ($va_resultado as &$va_item_resultado) 
            {
                $vs_objeto = $va_item_resultado["_objeto"];
                $vo_objeto = $va_objetos_instanciados[$vs_objeto];

                if (isset($vo_objeto->visualizacoes[$ps_visualizacao]))
                    $vs_visualizacao = $ps_visualizacao;
                else
                    $vs_visualizacao = "lista";

                $va_visualizacao = $vo_objeto->get_visualizacao($ps_visualizacao)["campos"];

                foreach ($va_visualizacao as $ps_key_campo_visualizacao => $va_campo_visualizacao) {
                    // Primeiro, ver se o campo de visualização é atributo
                    //////////////////////////////////////////////////////

                    if (isset($vo_objeto->atributos[$va_campo_visualizacao["nome"]])) {
                        if (isset($vo_objeto->atributos[$va_campo_visualizacao["nome"]]["objeto"])) {
                            if (isset($va_item_resultado[$ps_key_campo_visualizacao])) {
                                $vs_id_objeto = $vo_objeto->atributos[$va_campo_visualizacao["nome"]]["objeto"];

                                $vo_objeto_chave_estrangeira = new $vs_id_objeto($vs_id_objeto);

                                if ($va_item_resultado[$ps_key_campo_visualizacao]) {
                                    $va_objeto_chave_estrangeira = $vo_objeto_chave_estrangeira->ler($va_item_resultado[$ps_key_campo_visualizacao], "lista", $pn_idioma_codigo);
                                    $va_item_resultado[$ps_key_campo_visualizacao] = $va_objeto_chave_estrangeira;
                                }
                            }
                        }
                    }
                }

                // // Agora, vamos ver se o campo de visualização é relacionamento
                //////////////////////////////////////////////////////////////////

                $contador = 0;
                foreach ($va_visualizacao as $ps_campo_visualizacao => $va_campo_visualizacao) 
                {
                    if (!isset($vo_objeto->relacionamentos[$va_campo_visualizacao["nome"]]) && $this->recurso_sistema_codigo) 
                    {
                        $va_novo_relacionamento = $this->get_relacionamento($this->recurso_sistema_codigo, $va_campo_visualizacao["nome"]);

                        if (count($va_novo_relacionamento))
                            $vo_objeto->relacionamentos[$va_campo_visualizacao["nome"]] = $va_novo_relacionamento;
                    }

                    if (isset($vo_objeto->relacionamentos[$va_campo_visualizacao["nome"]])) 
                    {
                        $pn_codigo = $va_item_resultado[$vs_alias_chave_primaria];
                        $va_relacionamento = $vo_objeto->relacionamentos[$va_campo_visualizacao["nome"]];

                        $vs_tabela_join = "";
                        if (isset($va_relacionamento["tabela_relacionamento"]))
                            $vs_tabela_join = $va_relacionamento["tabela_relacionamento"];

                        $vs_objeto_relacionamento = "";

                        if (isset($va_relacionamento["objeto"]))
                            $vs_objeto_relacionamento = $va_relacionamento["objeto"];
                        elseif (isset($va_relacionamento["dependencia_objeto"])) {
                            $va_dependencia_objeto = explode("_0_", $va_relacionamento["dependencia_objeto"]);

                            $vs_objeto_relacionamento = $va_item_resultado[$va_dependencia_objeto[0]][$va_dependencia_objeto[1]];

                            $vo_objeto_relacionamento = new $vs_objeto_relacionamento($vs_objeto_relacionamento);

                            $va_novo_campo_relacionamento = [
                                $va_relacionamento["campos_relacionamento"][$va_campo_visualizacao["nome"]],
                                "atributo" => $vo_objeto_relacionamento->get_chave_primaria()[0]
                            ];

                            $va_relacionamento["campos_relacionamento"][$va_campo_visualizacao["nome"]] = $va_novo_campo_relacionamento;
                        }

                        $va_filtros = array();
                        if (isset($va_relacionamento["filtros"]))
                            $va_filtros = $va_relacionamento["filtros"];

                        if (!isset($va_relacionamento["tem_idioma"]))
                            $vn_idioma_codigo = null;
                        else
                            $vn_idioma_codigo = $pn_idioma_codigo;

                        $va_novo_resultado = array();
                        $va_novo_resultado[$ps_campo_visualizacao] = $vo_objeto->ler_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_relacionamento["campos_relacionamento"], $va_relacionamento["tipos_campos_relacionamento"], $vs_tabela_join, $vs_objeto_relacionamento, $va_filtros, $vn_idioma_codigo);
                        
                        // Vamos verificar se precisamos retornar os dados do relacionamento ou
                        // somente a quantidade de itens relacionados

                        /* FIXME CDTxCDP - Retorna a quantidade de relacionamentos no lugar dos itens relacionados.
                    // Analisar se é a melhor implementação. É possível mover para ler valor.

                    if (isset($va_campo_visualizacao["formato"]["campo"]) && $va_campo_visualizacao["formato"]["campo"] == "_Q_")
                    {
                        $va_selects = array();
                        $va_campos_select = array();
                        $va_wheres_select = array();
                        $va_tipos_parametros_select = array();
                        $va_parametros_select = array();

                        $va_campos_select[] = " DISTINCT COUNT(*) as Q";

                        $va_wheres_select[] = $va_relacionamento["tabela_intermediaria"] . "." . $va_relacionamento["chave_exportada"] . " = (?) ";
                        $va_tipos_parametros_select[] = "i";
                        $va_parametros_select[] = $pn_codigo;

                        $va_selects[] = [
                            "tabela" => $va_relacionamento["tabela_intermediaria"],
                            "campos" => $va_campos_select,
                            "wheres" => $va_wheres_select,
                        ];

                        $va_novo_resultado[$ps_campo_visualizacao] = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, null, null, false);

                        //var_dump($va_novo_resultado);
                    }
                    else
                        $va_novo_resultado[$ps_campo_visualizacao] = $vo_objeto->ler_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_relacionamento["campos_relacionamento"], $va_relacionamento["tipos_campos_relacionamento"], $vs_tabela_join, $vs_objeto_relacionamento, $va_filtros, $vn_idioma_codigo);

                    */

                        if (count($va_novo_resultado))
                            $va_item_resultado = array_merge($va_item_resultado, $va_novo_resultado);

                        $contador++;
                    }

                    if (
                        (!isset($va_item_resultado[$ps_campo_visualizacao]) || is_null($va_item_resultado[$ps_campo_visualizacao]))
                        ||
                        (is_array($va_item_resultado[$ps_campo_visualizacao]) && !count($va_item_resultado[$ps_campo_visualizacao]))
                    ) {
                        unset($va_item_resultado[$ps_campo_visualizacao]);
                    }
                }

                if ($vo_objeto->objeto_pai) 
                {
                    $vs_id_objeto = $vo_objeto->objeto_pai;
                    $vo_objeto_pai = new $vs_id_objeto($vs_id_objeto);

                    if (isset($va_item_resultado[$vo_objeto_pai->chave_primaria[0]])) 
                    {
                        $va_resultado_pai = $vo_objeto_pai->ler($va_item_resultado[$vo_objeto_pai->chave_primaria[0]], $ps_visualizacao, $pn_idioma_codigo);

                        $va_item_resultado = array_merge($va_item_resultado, $va_resultado_pai);

                        // Por conveniência, vamos retornar o tipo do objeto para o filho, porque o pai sobrescreveu o valor
                        ////////////////////////////////////////////////////////////////////////////////////////////////////

                        $va_item_resultado["_objeto"] = $vs_objeto;
                    }
                }

                if ($vo_objeto->recurso_sistema_codigo) 
                {
                    $vs_alias_chave_primaria = $vo_objeto->chave_primaria[0];
                    foreach ($va_visualizacao as $vs_key_campo_visualizacao => $va_campo_visualizacao) 
                    {
                        if ($va_campo_visualizacao["nome"] == $vo_objeto->chave_primaria[0])
                            $vs_alias_chave_primaria = $vs_key_campo_visualizacao;
                    }

                    $pn_codigo = "";
                    $va_representantes_digitais = array();

                    if (isset($va_item_resultado[$vs_alias_chave_primaria]))
                        $pn_codigo = $va_item_resultado[$vs_alias_chave_primaria];

                    if ($pn_codigo && isset($va_visualizacao["representante_digital_codigo"]))
                        $va_representantes_digitais["representante_digital_codigo"] = $vo_objeto->ler_representantes_digitais($pn_codigo, 1);

                    if ($pn_codigo && isset($va_visualizacao["arquivo_download_codigo"]))
                        $va_representantes_digitais["arquivo_download_codigo"] = $vo_objeto->ler_representantes_digitais($pn_codigo, 2);

                    if ($pn_codigo && isset($va_visualizacao["etapa_fluxo_codigo_1"])) {
                        // Leitura de etapa no fluxo
                        $va_representantes_digitais["etapa_fluxo_codigo_1"] = $vo_objeto->ler_etapa_fluxo_registro($pn_codigo);
                    }

                    $va_item_resultado = array_merge($va_item_resultado, $va_representantes_digitais);
                }

                foreach ($va_visualizacao as $vs_key_campo_visualizacao => $va_campo_visualizacao) 
                {
                    if (isset($va_campo_visualizacao["metodo"]))
                    {
                        $vn_objeto_codigo = $va_item_resultado[$vo_objeto->chave_primaria[0]];
                        $vs_metodo = $va_campo_visualizacao["metodo"];
                        
                        if (isset($va_campo_visualizacao["parametros"]))
                        {
                            foreach($va_campo_visualizacao["parametros"] as $vs_parametro)
                            {
                                $va_parametros[] = $va_item_resultado[$vs_parametro];
                            }
                        }

                        $va_novo_resultado = $vo_objeto->$vs_metodo($va_parametros);

                        $va_item_resultado[$va_campo_visualizacao["nome"]] = $va_novo_resultado;
                    }
                }

                foreach ($va_visualizacao as $vs_key_campo_visualizacao => $va_campo_visualizacao) 
                {
                    if (isset($va_campo_visualizacao["formato"])) 
                    {
                        foreach ($va_campo_visualizacao["formato"] as $vs_id_formato => $vs_parametro) 
                        {
                            switch ($vs_id_formato) {
                                case "data":
                                    $vo_periodo = new Periodo;

                                    if (isset($va_item_resultado[$vs_key_campo_visualizacao . "_data_inicial"])) {
                                        $vo_periodo->set_data_inicial($va_item_resultado[$vs_key_campo_visualizacao . "_data_inicial"]);

                                        if (isset($va_item_resultado[$vs_key_campo_visualizacao . "_data_final"]))
                                            $vo_periodo->set_data_final($va_item_resultado[$vs_key_campo_visualizacao . "_data_final"]);

                                        $va_item_resultado[$vs_key_campo_visualizacao] = $vo_periodo->get_data_exibicao();
                                    }

                                    break;

                                case "custom":
                                    $va_campos_custom = explode(",", $va_campo_visualizacao["formato"]["custom"]);

                                    $va_valor_campo_custom = array();
                                    foreach ($va_campos_custom as $vs_campo_custom) {
                                        $va_valor_campo_custom[] = $va_item_resultado[$vs_campo_custom];
                                    }

                                    $va_item_resultado[$vs_key_campo_visualizacao] = join(" | ", $va_valor_campo_custom);

                                    break;
                            }
                        }
                    }
                }

                $va_item_resultado["_number_of_children"] = 0;
                
                if ($vo_objeto->campo_hierarquico)
                    $va_item_resultado["_number_of_children"] = $vo_objeto->ler_numero_registros([$vo_objeto->campo_hierarquico => $va_item_resultado[$vs_alias_chave_primaria]]);
            }
        }

        return $va_resultado;
    }

    public function ler_lista_quantitativa($ps_id_relacionamento, $ps_label_objeto_relacionamento, $pa_filtros_busca = null, $pb_ordenar_por_quantidade = false)
    {
        if (isset($pa_filtros_busca))
            $va_filtros_busca = $pa_filtros_busca;
        else
            $va_filtros_busca = array();

        $va_selects = array();
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_order_by = array();
        $va_group_by = array();

        $va_campos_select = array();
        $va_joins_select = array();
        $va_wheres_select = array();
        $vs_limit = "";

        $vs_tabela_intermediaria = "";
        $va_tabelas_adicionadas = array();
        $vb_achou_relacionamento_pai = false;

        $vs_tabela_objeto = $this->tabela_banco;
        $va_tabelas_adicionadas[$vs_tabela_objeto][] = $vs_tabela_objeto;

        $vs_atributo_relacionamento = "";
        $va_id_relacionamento = explode("_0_", $ps_id_relacionamento);

        if (count($va_id_relacionamento) > 1)
        {
            $ps_id_relacionamento = $va_id_relacionamento[0];
            $vs_atributo_relacionamento = $va_id_relacionamento[1];
        }

        if ($this->objeto_pai) 
        {
            $vo_objeto_pai = new $this->objeto_pai;

            $vs_tabela_objeto_pai = $vo_objeto_pai->tabela_banco;

            if (isset($vo_objeto_pai->atributos[$ps_id_relacionamento]["objeto"]))
            {
                $va_atributos_objeto = $vo_objeto_pai->atributos;
                $vs_tabela_objeto = $vs_tabela_objeto_pai;
                $vb_achou_relacionamento_pai = true;
            }
            elseif (isset($vo_objeto_pai->relacionamentos[$ps_id_relacionamento]["objeto"]))
            {
                $va_relacionamentos_objeto = $vo_objeto_pai->relacionamentos;
                $vb_achou_relacionamento_pai = true;
            }
            elseif (isset($vo_objeto_pai->relacionamentos[$ps_id_relacionamento]["campos_relacionamento"][$vs_atributo_relacionamento]["objeto"]))
            {
                $va_relacionamentos_objeto = $vo_objeto_pai->relacionamentos;
                $vb_achou_relacionamento_pai = true;
            }

            if ($vb_achou_relacionamento_pai)
            {
                $va_joins_select[$vs_tabela_objeto_pai] = " JOIN " . $vs_tabela_objeto_pai . " ON " . $this->tabela_banco . "." . $this->campo_relacionamento_pai . " = " . $vs_tabela_objeto_pai . ".codigo";
                $va_tabelas_adicionadas[$vs_tabela_objeto_pai][] = $vs_tabela_objeto_pai;
            }
        }

        if (!$vb_achou_relacionamento_pai)
        {
            $va_atributos_objeto = $this->atributos;
            $va_relacionamentos_objeto = $this->relacionamentos;
        }
        else
            $vs_tabela_objeto = $vs_tabela_objeto_pai;

        if (isset($va_atributos_objeto[$ps_id_relacionamento]["objeto"]))
        {
            $vo_objeto_relacionamento = new $va_atributos_objeto[$ps_id_relacionamento]["objeto"];

            $vs_coluna_relacionamento = $va_atributos_objeto[$ps_id_relacionamento]["coluna_tabela"];

            $vs_tabela_join = $vs_tabela_objeto;
            $vs_coluna_join = "codigo";
        }
        elseif (isset($va_relacionamentos_objeto[$ps_id_relacionamento]["objeto"]))
        {
            $vo_objeto_relacionamento = new $va_relacionamentos_objeto[$ps_id_relacionamento]["objeto"];

            $vs_coluna_relacionamento = reset($va_relacionamentos_objeto[$ps_id_relacionamento]["campos_relacionamento"]);

            $vs_tabela_join = $va_relacionamentos_objeto[$ps_id_relacionamento]["tabela_intermediaria"];
            $vs_coluna_join = $va_relacionamentos_objeto[$ps_id_relacionamento]["chave_exportada"];

            $va_joins_select[$vs_tabela_intermediaria] = " JOIN " . $vs_tabela_join . " ON " . $vs_tabela_objeto . ".codigo = " . $vs_tabela_join . "." . $vs_coluna_join;
            $va_tabelas_adicionadas[$vs_tabela_join][] = $vs_tabela_join;
        }
        elseif (isset($va_relacionamentos_objeto[$ps_id_relacionamento]["campos_relacionamento"][$vs_atributo_relacionamento]["objeto"]))
        {
            $vo_objeto_relacionamento = new $va_relacionamentos_objeto[$ps_id_relacionamento]["campos_relacionamento"][$vs_atributo_relacionamento]["objeto"];

            $vs_coluna_relacionamento = reset($va_relacionamentos_objeto[$ps_id_relacionamento]["campos_relacionamento"][$vs_atributo_relacionamento]);

            $vs_tabela_join = $va_relacionamentos_objeto[$ps_id_relacionamento]["tabela_intermediaria"];
            $vs_coluna_join = $va_relacionamentos_objeto[$ps_id_relacionamento]["chave_exportada"];

            $va_joins_select[$vs_tabela_intermediaria] = " JOIN " . $vs_tabela_join . " ON " . $vs_tabela_objeto . ".codigo = " . $vs_tabela_join . "." . $vs_coluna_join;
            $va_tabelas_adicionadas[$vs_tabela_join][] = $vs_tabela_join;
        }
        
        $va_label_objeto_relacionamento = explode("_0_", $ps_label_objeto_relacionamento);

        if (count($va_label_objeto_relacionamento) == 2)
        {
            $vs_tabela_objeto_relacionamento = $vo_objeto_relacionamento->relacionamentos[$va_label_objeto_relacionamento[0]]["tabela_intermediaria"];
            $vs_coluna_label_objeto_relacionamento = $vo_objeto_relacionamento->relacionamentos[$va_label_objeto_relacionamento[0]]["campos_relacionamento"][$va_label_objeto_relacionamento[1]];
            $vs_coluna_join_objeto_relacionamento = $vo_objeto_relacionamento->relacionamentos[$va_label_objeto_relacionamento[0]]["chave_exportada"];
        }
        else
        {
            $vs_tabela_objeto_relacionamento = $vo_objeto_relacionamento->tabela_banco;
            $vs_coluna_label_objeto_relacionamento = $vo_objeto_relacionamento->atributos[$ps_label_objeto_relacionamento]["coluna_tabela"];
            $vs_coluna_join_objeto_relacionamento = "codigo";
        }

        $va_campos_select["codigo"] = $vs_tabela_objeto_relacionamento . "." . $vs_coluna_join_objeto_relacionamento . " as Q_codigo";
        $va_campos_select[$ps_label_objeto_relacionamento] = $vs_tabela_objeto_relacionamento . "." . $vs_coluna_label_objeto_relacionamento . " as " . $ps_label_objeto_relacionamento;
        $va_campos_select["Q"] = "COUNT(DISTINCT " . $vs_tabela_join . "." . $vs_coluna_join . ") as Q ";

        $va_joins_select[$vs_tabela_objeto_relacionamento] = " JOIN " . $vs_tabela_objeto_relacionamento . " ON " . $vs_tabela_join . "." . $vs_coluna_relacionamento . " = " . $vs_tabela_objeto_relacionamento . "." . $vs_coluna_join_objeto_relacionamento;
        $va_tabelas_adicionadas[$vs_tabela_objeto_relacionamento][] = $vs_tabela_objeto_relacionamento;

        // Se algum atributo do objeto tiver um valor padrão, tem que filtrar por ele
        /////////////////////////////////////////////////////////////////////////////

        foreach ($this->atributos as $va_atributo_objeto) 
        {
            if (isset($va_atributo_objeto["valor_padrao"]) && !in_array(reset($va_atributo_objeto), $va_filtros_busca))
                $va_filtros_busca[reset($va_atributo_objeto)] = $va_atributo_objeto["valor_padrao"];
        }
                
        $this->montar_filtros_busca($va_filtros_busca, $this, $va_joins_select, $va_wheres_select, $va_tipos_parametros_select, $va_parametros_select, $va_tabelas_adicionadas, $pb_retornar_ramos_inferiores);

        $va_group_by[] = $ps_label_objeto_relacionamento;
        $va_group_by[] = "Q_codigo";

        if ($pb_ordenar_por_quantidade)
            $va_order_by = "Q DESC";

        $va_selects[] = [
            "tabela" => $this->tabela_banco,
            "campos" => $va_campos_select,
            "joins" => $va_joins_select,
            "wheres" => $va_wheres_select,
        ];

        $vo_banco = $this->get_banco();
        $va_resultados = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, $va_order_by, $vs_limit, true, $va_group_by);

        if ($vo_objeto_relacionamento->hierarquico)
        {
            foreach ($va_resultados as &$va_resultado)
            {
                $va_resultado[$ps_label_objeto_relacionamento] = $vo_objeto_relacionamento->montar_hierarquia($vo_objeto_relacionamento->atributos[$vo_objeto_relacionamento->campo_hierarquico]["coluna_tabela"], $va_resultado["Q_codigo"], $vs_coluna_label_objeto_relacionamento);
            }
        }

        return $va_resultados;
    }

    public function ler_estatisticas_catalogacao($ps_data_inicial = "", $ps_data_final = "", $ps_tipo_operacao = "", $ps_agrupador = null, $pb_ordenar_por_quantidade = false)
    {
        $va_selects = array();
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_order_by = array();
        $va_group_by = array();

        $va_campos_select = array();
        $va_joins_select = array();
        $va_wheres_select = array();
        $vs_limit = "";

        $vs_campo_ordenador = "";
        $va_order_by = "agrupador";

        switch ($ps_agrupador)
        {
            case "dia":
                $vs_campo_agrupador_select = "DATE_FORMAT(log.data,'%d/%m/%Y')";
                $vs_campo_ordenador = "DATE_FORMAT(log.data,'%Y%m%d')";
                $vs_campo_agrupador_label = "Dia";
                break;

            case "mes":
                $vs_campo_agrupador_select = "DATE_FORMAT(log.data, '%m/%Y')";
                $vs_campo_ordenador = "DATE_FORMAT(log.data, '%Y%m')";
                break;

            case "ano":
                $vs_campo_agrupador_select = "YEAR(log.data)";
                break;

            case "usuario":
                $vs_campo_agrupador_select = "usuario.nome";
                $va_joins_select["usuario"] = " JOIN usuario ON log.usuario_codigo = usuario.codigo";

                break;
        }

        $va_campos_select["agrupador"] =  $vs_campo_agrupador_select . " as agrupador";
        $va_campos_select["Q"] = "COUNT(DISTINCT log.registro_codigo) as Q";

        $va_wheres_select[] = "log.id_registro = ?";
        $va_tipos_parametros_select[] = "s";
        $va_parametros_select[] = get_class($this);

        if ($ps_tipo_operacao != "")
        {
            $va_wheres_select[] = "log.tipo_operacao_codigo = ?";
            $va_tipos_parametros_select[] = "i";
            $va_parametros_select[] = $ps_tipo_operacao;
        }

        if ($ps_data_inicial)
        {
            if ($ps_data_final)
                $va_wheres_select[] = "DATE(log.data) >= ?";
            else
                $va_wheres_select[] = "DATE(log.data) = ?";

            $va_tipos_parametros_select[] = "s";
            $va_parametros_select[] = $ps_data_inicial;

            if ($ps_data_final)
            {
                $va_wheres_select[] = "log.data <= ?";
                $va_tipos_parametros_select[] = "s";
                $va_parametros_select[] = $ps_data_final;
            }
        }

        $va_group_by[] = "agrupador";

        if ($vs_campo_ordenador)
        {
            $va_campos_select["ordenador"] =  $vs_campo_ordenador . " as ordenador";

            $va_order_by = "ordenador";
            $va_group_by[] = "ordenador";
        }
        
        if ($pb_ordenar_por_quantidade)
            $va_order_by = "Q DESC";

        $va_selects[] = [
            "tabela" => "log",
            "campos" => $va_campos_select,
            "joins" => $va_joins_select,
            "wheres" => $va_wheres_select,
        ];

        $vo_banco = $this->get_banco();
        $va_resultado = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, $va_order_by, $vs_limit, true, $va_group_by);

        return $va_resultado;
    }

    public function ler_atividades_pesquisa_usuario($ps_data_inicial = "", $ps_data_final = "", $vn_setor_sistema_codigo = "")
    {
        $va_selects = array();
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_order_by = array();
        $va_group_by = array();

        $va_campos_select = array();
        $va_joins_select = array();
        $va_wheres_select = array();
        $vs_limit = "";

        $vs_campo_ordenador = "";

        $va_campos_select["setor"] =  "extroversao_busca.setor_sistema_codigo as setor";
        $va_campos_select["campo"] =  "extroversao_busca_campos.alias as campo";
        $va_campos_select["valor"] =  "extroversao_busca_campos.valor as valor";
        $va_campos_select["Q"] = "COUNT(DISTINCT extroversao_busca_campos.codigo) as Q";

        $va_joins_select[] = " JOIN extroversao_busca ON extroversao_busca_campos.extroversao_busca_codigo = extroversao_busca.codigo ";

        if ($vn_setor_sistema_codigo != "")
        {
            $va_wheres_select[] = "extroversao_busca.setor_sistema_codigo = ?";
            $va_tipos_parametros_select[] = "i";
            $va_parametros_select[] = $vn_setor_sistema_codigo;
        }

        if ($ps_data_inicial)
        {
            if ($ps_data_final)
                $va_wheres_select[] = "DATE(extroversao_busca.data) >= ?";
            else
                $va_wheres_select[] = "DATE(extroversao_busca.data) = ?";

            $va_tipos_parametros_select[] = "s";
            $va_parametros_select[] = $ps_data_inicial;

            if ($ps_data_final)
            {
                $va_wheres_select[] = "extroversao_busca.data <= ?";
                $va_tipos_parametros_select[] = "s";
                $va_parametros_select[] = $ps_data_final;
            }
        }

        $va_group_by = ["setor", "campo", "valor"];
        $va_order_by = ["setor", "campo", "Q", "valor"];

        $va_selects[] = [
            "tabela" => "extroversao_busca_campos",
            "campos" => $va_campos_select,
            "joins" => $va_joins_select,
            "wheres" => $va_wheres_select,
        ];

        $vo_banco = $this->get_banco();
        $va_resultados = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, $va_order_by, $vs_limit, true, $va_group_by);

        $vo_extroversao_busca_campos = new extroversao_busca_campos;

        foreach ($va_resultados as &$va_resultado)
        {
            if (isset($va_resultado["setor"]) && ($va_resultado["setor"] != ""))
                $va_resultado["setor"] = $vo_extroversao_busca_campos->get_nome_setor_sistema($va_resultado["setor"]);
            else
                $va_resultado["setor"] = "Todos os acervos";

            $vs_valor_tratado = $vo_extroversao_busca_campos->get_label_de_valor($va_resultado["campo"], $va_resultado["valor"]);

            if ($vs_valor_tratado)
                $va_resultado["valor"] = $vs_valor_tratado;

            $va_resultado["campo"] = $vo_extroversao_busca_campos->get_label_campo_extroversao($va_resultado["campo"]);
        }

        return $va_resultados;
    }

    public function montar_ordenacao($po_objeto, $pa_order_by = array(), &$pa_campos_select = array(), &$pa_joins_select = array(), $ps_order = null, $pa_tabelas_adicionadas = array())
    {
        $va_order_by = array();

        if (!count($pa_order_by))
            return $va_order_by;

        $vs_campo_order_by = "";

        $vs_tabela_objeto = $po_objeto->tabela_banco;
        $vs_coluna_chave_primaria = "codigo";

        if (isset($pa_order_by[get_class($po_objeto)]))
        {
            $pa_order_by = array($pa_order_by[get_class($po_objeto)]);
        }

        foreach($pa_order_by as $vs_order_by)
        {
            $va_campo_order_by = explode("_0_", $vs_order_by);
            
            if ($po_objeto->campo_relacionamento_pai == $va_campo_order_by[0] && count($va_campo_order_by) > 1)
            {
                // Ordenação por campos associados ao objeto pai
                ////////////////////////////////////////////////

                $vo_objeto_pai = new $po_objeto->objeto_pai($po_objeto->objeto_pai);

                $vs_campo_tabela_join = $vo_objeto_pai->chave_primaria["coluna_tabela"];

                // Se a tabela do objeto pai foi adicionada no filtro, então o alias dela é
                // necessariamente $vo_objeto_pai->tabela_banco_1

                $vb_adicionar_tabela_pai = true;
                if (($vo_objeto_pai->tabela_banco == $vs_tabela_objeto) || in_array($vo_objeto_pai->tabela_banco, array_keys($pa_tabelas_adicionadas)) && isset($pa_tabelas_adicionadas[$vo_objeto_pai->tabela_banco][$vo_objeto_pai->tabela_banco . "_1"])) {
                    $vb_adicionar_tabela_pai = false;

                    if ($vo_objeto_pai->tabela_banco == $vs_tabela_objeto)
                    {
                        $vs_tabela_pai = $vo_objeto_pai->tabela_banco;
                    }
                    else
                    {
                        $vs_tabela_pai = $vo_objeto_pai->tabela_banco . "_1";
                    }

                } else
                    $vs_tabela_pai = $vo_objeto_pai->tabela_banco;

                if ($vb_adicionar_tabela_pai)
                    $pa_joins_select[$vs_tabela_pai] = " JOIN " . $vs_tabela_pai . " ON " . $vs_tabela_objeto . "." . $po_objeto->campo_relacionamento_pai . " = " . $vs_tabela_pai . "." . $vs_campo_tabela_join;

                if (isset($vo_objeto_pai->atributos[$va_campo_order_by[1]])) {
                    $va_atributo_order_by = $vo_objeto_pai->atributos[$va_campo_order_by[1]];

                    if (is_array($va_atributo_order_by["coluna_tabela"]))
                        $pa_campos_select["ord"] = $vs_tabela_pai . "." . reset($va_atributo_order_by["coluna_tabela"]) . " as ord";
                    else
                        $pa_campos_select["ord"] = $vs_tabela_pai . "." . $va_atributo_order_by["coluna_tabela"] . " as ord";

                    $vs_campo_order_by = $va_campo_order_by[1];
                } elseif (isset($vo_objeto_pai->relacionamentos[$va_campo_order_by[1]])) {
                    $va_relacionamento_order_by = $vo_objeto_pai->relacionamentos[$va_campo_order_by[1]];

                    $vs_tabela_intermediaria = $va_relacionamento_order_by["tabela_intermediaria"];
                    $vs_campo_tabela_join = $va_relacionamento_order_by["chave_exportada"];

                    $vb_adicionar_tabela_intermediaria = true;
                    if (in_array($vs_tabela_intermediaria, array_keys($pa_tabelas_adicionadas)) && isset($pa_tabelas_adicionadas[$vs_tabela_intermediaria][$vs_tabela_intermediaria . "_1"])) {
                        $vb_adicionar_tabela_intermediaria = false;
                        $vs_tabela_intermediaria = $vs_tabela_intermediaria . "_1";
                    }

                    if ($vb_adicionar_tabela_intermediaria)
                        $pa_joins_select[$vs_tabela_intermediaria] = " JOIN " . $vs_tabela_intermediaria . " ON " . $vs_tabela_pai . "." . $vs_coluna_chave_primaria . " = " . $vs_tabela_intermediaria . "." . $vs_campo_tabela_join;

                    $pa_campos_select["ord"] = $vs_tabela_intermediaria . "." . $vo_objeto_pai->relacionamentos[$va_campo_order_by[1]]["campos_relacionamento"][$va_campo_order_by[2]] . " as ord";

                    $vs_campo_order_by = $va_campo_order_by[2];
                }
            } elseif (isset($po_objeto->relacionamentos[$va_campo_order_by[0]])) {
                $va_relacionamento_order_by = $po_objeto->relacionamentos[$va_campo_order_by[0]];

                $vs_tabela_intermediaria = $va_relacionamento_order_by["tabela_intermediaria"];
                $vs_campo_tabela_join = $va_relacionamento_order_by["chave_exportada"];

                $vb_adicionar_tabela_intermediaria = true;
                if (in_array($vs_tabela_intermediaria, array_keys($pa_tabelas_adicionadas)) && isset($pa_tabelas_adicionadas[$vs_tabela_intermediaria][$vs_tabela_intermediaria . "_1"])) {
                    $vb_adicionar_tabela_intermediaria = false;
                    $vs_tabela_intermediaria = $vs_tabela_intermediaria . "_1";
                }

                if ($vb_adicionar_tabela_intermediaria)
                    $pa_joins_select[$vs_tabela_intermediaria] = " JOIN " . $vs_tabela_intermediaria . " ON " . $vs_tabela_objeto . "." . $vs_coluna_chave_primaria . " = " . $vs_tabela_intermediaria . "." . $vs_campo_tabela_join;

                if (isset($po_objeto->relacionamentos[$va_campo_order_by[0]]["campos_relacionamento"][$va_campo_order_by[1]]))
                    $vs_coluna_order_by = $po_objeto->relacionamentos[$va_campo_order_by[0]]["campos_relacionamento"][$va_campo_order_by[1]];
                else
                    $vs_coluna_order_by = $va_campo_order_by[1];

                $pa_campos_select["ord"] = $vs_tabela_intermediaria . "." . $vs_coluna_order_by . " as ord";

                $vs_campo_order_by = $va_campo_order_by[1];
            } 
            elseif (isset($po_objeto->atributos[$vs_order_by]))
            {                
                $va_atributo_order_by = $po_objeto->atributos[$vs_order_by];

                $pa_campos_select["ord"] = $vs_tabela_objeto . "." . $va_atributo_order_by["coluna_tabela"] . " as ord";

                $vs_campo_order_by = $vs_order_by;
            }
            elseif ($vs_order_by == $po_objeto->chave_primaria[0])
            {
                $pa_campos_select["ord"] = $vs_tabela_objeto . "." . $vs_coluna_chave_primaria . " as ord";
                $vs_campo_order_by = $po_objeto->chave_primaria[0];
            }

            if ($vs_campo_order_by && ($vs_campo_order_by != "_rand_"))
            {
                if (!isset($ps_order))
                    $ps_order = "";

                $va_order_by[$vs_campo_order_by] = " ord IS NULL " . $ps_order . ", ord " . $ps_order;
            }
            else
            {
                $va_order_by = "_rand_";
            }
        }

        return $va_order_by;
    }

    public function montar_hierarquia($ps_campo_pai, $pn_codigo_pai, $ps_campo_saida)
    {
        $this->inicializar_variaveis_banco();

        $this->va_campos[] = $this->tabela_banco . "." . $ps_campo_saida;
        $this->va_campos[] = $this->tabela_banco . "." . $ps_campo_pai;

        $this->va_wheres[] = $this->tabela_banco . "." . $this->chave_primaria["coluna_tabela"] . " = (?) ";
        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pn_codigo_pai;

        $va_selects = array();
        $va_selects[] = [
            "tabela" => $this->tabela_banco,
            "campos" => $this->va_campos,
            "wheres" => $this->va_wheres,
        ];

        $vo_banco = $this->get_banco();
        $va_resultado = $vo_banco->consultar($va_selects, $this->va_tipos_parametros, $this->va_parametros);

        if (count($va_resultado) && isset($va_resultado[0][$ps_campo_pai])) 
        {
            $pn_codigo_pai = $va_resultado[0][$ps_campo_pai];

            return $this->montar_hierarquia($ps_campo_pai, $pn_codigo_pai, $ps_campo_saida) . " > " . $va_resultado[0][$ps_campo_saida];
        } 
        else
            return $va_resultado[0][$ps_campo_saida];
    }

    public function ler_codigos_ramo_inferior($pn_codigo_pai, $ps_atributo_pai, $pn_nivel_inicial=1, $pn_nivel_atual=1)
    {
        $va_codigos_ramo_inferior = array();

        $this->inicializar_variaveis_banco();

        $vs_campo_pai = $this->atributos[$ps_atributo_pai]["coluna_tabela"];

        $this->va_campos[] = $this->tabela_banco . ".codigo";
        $this->va_campos[] = $this->tabela_banco . "." . $vs_campo_pai;
        
        $this->va_wheres[] = $this->tabela_banco . "." . $vs_campo_pai . " = (?) ";
        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pn_codigo_pai;

        $va_selects[] = [
            "tabela" => $this->tabela_banco, 
            "campos" => $this->va_campos, 
            "wheres" => $this->va_wheres, 
        ];

        $vo_banco = $this->get_banco();
        $va_registros = $vo_banco->consultar($va_selects, $this->va_tipos_parametros, $this->va_parametros);

        if (count($va_registros))
        {
            foreach($va_registros as $va_resultado)
            {
                if ($pn_nivel_atual >= $pn_nivel_inicial)
                    $va_codigos_ramo_inferior[] = $va_resultado["codigo"];

                $pn_nivel_atual++;

                $va_codigos_ramo_inferior = array_merge($va_codigos_ramo_inferior, $this->ler_codigos_ramo_inferior($va_resultado["codigo"], $ps_atributo_pai, $pn_nivel_inicial, $pn_nivel_atual));
            }
        }
        
        return $va_codigos_ramo_inferior;
    }

    public function ler_codigos_ramo_superior($pn_codigo_filho, $ps_atributo_pai, $pn_nivel_inicial=1)
    {
        $va_codigos_ramo_superior = array();

        $vn_nivel_atual = 1;

        while ($pn_codigo_filho)
        {
            $this->inicializar_variaveis_banco();
            $va_selects = array();

            $vs_campo_pai = $this->atributos[$ps_atributo_pai]["coluna_tabela"];

            $this->va_campos[] = $this->tabela_banco . ".codigo";
            $this->va_campos[] = $this->tabela_banco . "." . $vs_campo_pai;
            
            $this->va_wheres[] = $this->tabela_banco . ".codigo = (?) ";
            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $pn_codigo_filho;

            $va_selects[] = [
                "tabela" => $this->tabela_banco, 
                "campos" => $this->va_campos, 
                "wheres" => $this->va_wheres
            ];

            $vo_banco = $this->get_banco();
            $va_registros = $vo_banco->consultar($va_selects, $this->va_tipos_parametros, $this->va_parametros);

            if (count($va_registros) && isset($va_registros[0][$vs_campo_pai]))
            {
                if ($vn_nivel_atual >= $pn_nivel_inicial)
                    $va_codigos_ramo_superior[] = $va_registros[0][$vs_campo_pai];

                $pn_codigo_filho = $va_registros[0][$vs_campo_pai];
            }
            else
                $pn_codigo_filho = "";

            $vn_nivel_atual++;
        }
        
        return $va_codigos_ramo_superior;
    }

    public function ler_relacionamentos($pn_codigo, $pa_filtros_busca = null, $pn_idioma_codigo = 1)
    {
        $va_resultado = array();
        foreach ($this->relacionamentos as $vs_id_relacionamento => $va_relacionamento) {
            if (isset($va_relacionamento["tabela_relacionamento"]))
                $vs_tabela_join = $va_relacionamento["tabela_relacionamento"];
            else
                $vs_tabela_join = "";

            $va_novo_resultado = array();
            $va_novo_resultado[$vs_id_relacionamento] = $this->ler_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_relacionamento["campos_relacionamento"], $va_relacionamento["tipos_campos_relacionamento"], $vs_tabela_join, $va_relacionamento["objeto"], $pa_filtros_busca, $pn_idioma_codigo);

            if (count($va_novo_resultado[$vs_id_relacionamento]))
                $va_resultado = array_merge($va_resultado, $va_novo_resultado);
        }

        return $va_resultado;
    }

    public function ler_relacionamento($ps_tabela, $pa_chave_primaria, $pn_codigo, $pa_campos_relacionamento, $pa_tipos_campos_relacionamento, $ps_tabela_join = '', $ps_objeto_relacionamento = '', $pa_filtros = array(), $pn_idioma_codigo = null)
    {
        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_order_by = array();

        if (!is_array($pa_chave_primaria))
            $va_chaves[] = $pa_chave_primaria;
        else
            $va_chaves = $pa_chave_primaria;

        $va_resultado = array();
        $contador = 0;

        foreach ($va_chaves as $ps_chave_primaria) 
        {
            $va_campos_select = array();
            $va_joins_select = array();
            $va_wheres_select = array();

            if (is_array($pa_campos_relacionamento)) {
                $va_keys = array_keys($pa_campos_relacionamento);
                $va_campos_relacionamento_temp = $pa_campos_relacionamento;
                $va_tipos_campos_relacionamento_temp = $pa_tipos_campos_relacionamento;
            } else {
                $va_campos_relacionamento_temp[] = $pa_campos_relacionamento;
                $va_tipos_campos_relacionamento_temp[] = $pa_tipos_campos_relacionamento;
            }

            $vo_objetos_campos_relacionamentos = array();

            if ($ps_objeto_relacionamento)
                $vo_objetos_campos_relacionamentos[$va_keys[0]] = $ps_objeto_relacionamento;

            $contador_tipos_dados = 0;
            foreach ($va_campos_relacionamento_temp as $vs_alias => $va_campo_relacionamento) 
            {
                if (is_array($va_campo_relacionamento)) {
                    // Campo do relacionamento pode estar ligado a um objeto
                    ////////////////////////////////////////////////////////

                    if (isset($va_campo_relacionamento["objeto"])) {
                        $vo_objetos_campos_relacionamentos[$vs_alias] = $va_campo_relacionamento["objeto"];
                    }

                    // Campo do relacionamento pode ser sequencial e obrigar ordenação na leitura
                    ////////////////////////////////////////////////////////

                    if (isset($va_campo_relacionamento["valor_sequencial"]))
                        $va_order_by[] = $vs_alias;

                    // Se o campo de relacionamento é composto por duas colunas, é auto-relacionamento
                    // ou a coluna tem um valor padrão de salvamento
                    /////////////////////////////////////////////////////////////////////////////////

                    $vs_campo_relacionamento = "";
                    if (isset($va_campo_relacionamento[0])) {
                        if (is_array($va_campo_relacionamento[0]))
                            $va_campo_relacionamento = $va_campo_relacionamento[0];
                        else {
                            $vs_campo_relacionamento = $va_campo_relacionamento[0];

                            // O segundo elemento do array pode especificar várias coisas
                            //if (!isset($va_campo_relacionamento["atributo"]) && !isset($va_campo_relacionamento["objeto"]))

                            if (isset($va_campo_relacionamento[1]))
                                $pa_filtros[$vs_alias] = $va_campo_relacionamento[1];
                        }
                    }

                    if (($va_tipos_campos_relacionamento_temp[$contador_tipos_dados] != "dt") && ($vs_campo_relacionamento == ""))
                        $vs_campo_relacionamento = $va_campo_relacionamento[$contador];

                    elseif ($vs_campo_relacionamento == "") {
                        $vs_campo_relacionamento = "";
                        foreach ($va_campo_relacionamento as $vs_key_coluna_tabela => $vs_coluna_tabela) {
                            $va_campos_select[] = $ps_tabela . "." . $vs_coluna_tabela . " as " . $vs_alias . "_" . $vs_key_coluna_tabela;
                        }
                    }
                } else
                    $vs_campo_relacionamento = $va_campo_relacionamento;

                if ($vs_campo_relacionamento)
                    $va_campos_select[] = $ps_tabela . "." . $vs_campo_relacionamento . " as " . $vs_alias;

                $contador_tipos_dados++;
            }

            $va_wheres_select[] = $ps_tabela . "." . $ps_chave_primaria . " = (?) ";
            $va_tipos_parametros_select[] = "i";
            $va_parametros_select[] = $pn_codigo;

            foreach ($pa_filtros as $va_parametro_nome => $va_parametro)
            {
                $va_valores_busca = array();
                $vs_operador = "";
                $vs_interrogacoes = " (?) ";

                $vb_tem_valor = $this->montar_valores_busca($va_parametro, $va_valores_busca, $vs_operador, $vs_interrogacoes);

                if ($vb_tem_valor) 
                {
                    if (isset($va_campos_relacionamento_temp[$va_parametro_nome]))
                    {
                        $vs_tabela_filtro = $ps_tabela;

                        if (is_array($va_campos_relacionamento_temp[$va_parametro_nome]))
                            $vs_coluna_filtro = reset($va_campos_relacionamento_temp[$va_parametro_nome]);
                        else
                            $vs_coluna_filtro = $va_campos_relacionamento_temp[$va_parametro_nome];

                        $vn_index_tipo_campo = array_search($va_parametro_nome, array_keys($va_campos_relacionamento_temp));
                        $vs_tipo_dado_campo = $va_tipos_campos_relacionamento_temp[$vn_index_tipo_campo];
                    }
                    elseif ($ps_objeto_relacionamento)
                    {
                        $vo_objeto_filtro = new $ps_objeto_relacionamento('');

                        // O filtro pode ser atributo ou relacionamento do objeto
                        /////////////////////////////////////////////////////////

                        if (isset($vo_objeto_filtro->atributos[$va_parametro_nome]) || isset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]) )
                        {
                            $vs_chave_importada = reset($va_campos_relacionamento_temp);
                            if (is_array($vs_chave_importada))
                                $vs_chave_importada = reset($vs_chave_importada);

                            if (!in_array($vo_objeto_filtro->tabela_banco, array_keys($va_joins_select)))
                                $va_joins_select[$vo_objeto_filtro->tabela_banco] = " JOIN " . $vo_objeto_filtro->tabela_banco . " ON " . $ps_tabela . "." . $vs_chave_importada . " = " . $vo_objeto_filtro->tabela_banco . "." . $vo_objeto_filtro->chave_primaria["coluna_tabela"];

                            if (isset($vo_objeto_filtro->atributos[$va_parametro_nome]))
                            {
                                $vs_tabela_filtro = $vo_objeto_filtro->tabela_banco;
                                $vs_coluna_filtro = $vo_objeto_filtro->atributos[$va_parametro_nome]["coluna_tabela"];
                                $vs_tipo_dado_campo = $vo_objeto_filtro->atributos[$va_parametro_nome]["tipo_dado"];
                            }
                            elseif (isset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]))
                            {
                                if (!in_array($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"], array_keys($va_joins_select)))
                                    $va_joins_select[$vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"]] = " JOIN " . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"] . " ON " . $vo_objeto_filtro->tabela_banco . "." . $vo_objeto_filtro->chave_primaria["coluna_tabela"] . " = " . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"] . "." . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["chave_exportada"];

                                $vs_tabela_filtro = $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"];
                                $vs_coluna_filtro = reset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["campos_relacionamento"]);
                                $vs_tipo_dado_campo = reset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tipos_campos_relacionamento"]);
                            }
                        }
                    }

                    if ($vs_operador == "NOT")
                        $va_wheres_select[] = $vs_operador . " " . $vs_tabela_filtro . "." . $vs_coluna_filtro . " <=> " . $vs_interrogacoes;
                    else
                        $va_wheres_select[] = $vs_tabela_filtro . "." . $vs_coluna_filtro . " " . $vs_operador . $vs_interrogacoes;

                    foreach ($va_valores_busca as $va_valor_busca) 
                    {
                        $va_parametros_select[] = $va_valor_busca;
                        $va_tipos_parametros_select[] = $vs_tipo_dado_campo;
                    }
                }
            }

            if (isset($pn_idioma_codigo) && ($pn_idioma_codigo != "any")) {
                $va_wheres_select[] = $ps_tabela . ".idioma_codigo = (?) ";
                $va_tipos_parametros_select[] = "i";
                $va_parametros_select[] = $pn_idioma_codigo;
            }

            $va_selects[] = [
                "tabela" => $ps_tabela,
                "campos" => $va_campos_select,
                "joins" => $va_joins_select,
                "wheres" => $va_wheres_select,
            ];

            $contador++;
        }

        $vo_banco = $this->get_banco();
        $va_resultados_relacionamentos = array();

        $va_resultados_relacionamentos = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, $va_order_by);

        ////////////////////////////////////////////////////////////////
        // Vamos instanciar o objeto relacionado e ler os seus atributos
        ////////////////////////////////////////////////////////////////

        foreach ($vo_objetos_campos_relacionamentos as $vs_campo_objeto_relacionamento => $vs_objeto_relacionamento) 
        {
            $vo_objeto_relacionamento = new $vs_objeto_relacionamento($vs_objeto_relacionamento);

            foreach ($va_resultados_relacionamentos as &$va_resultado_relacionamento) 
            {
                if (isset($va_resultado_relacionamento[$vs_campo_objeto_relacionamento])) 
                {
                    if (is_array($pa_campos_relacionamento[$vs_campo_objeto_relacionamento])) 
                    {
                        if (isset($pa_campos_relacionamento[$vs_campo_objeto_relacionamento]["atributo"]))
                            $vs_chave_leitura_objeto_relacionamento = $pa_campos_relacionamento[$vs_campo_objeto_relacionamento]["atributo"];
                        else
                            $vs_chave_leitura_objeto_relacionamento = $pa_campos_relacionamento[$vs_campo_objeto_relacionamento][0];
                    } else
                        $vs_chave_leitura_objeto_relacionamento = $pa_campos_relacionamento[$vs_campo_objeto_relacionamento];

                    $va_objeto_relacionamento = $vo_objeto_relacionamento->ler_lista([$vs_chave_leitura_objeto_relacionamento => $va_resultado_relacionamento[$vs_campo_objeto_relacionamento]], "lista", 1, 1);

                    if (count($va_objeto_relacionamento))
                        $va_resultado_relacionamento[$vs_campo_objeto_relacionamento] = $va_objeto_relacionamento[0];
                }
            }
        }

        if (count($va_resultados_relacionamentos))
            $va_resultado = array_merge($va_resultado, $va_resultados_relacionamentos);

        return $va_resultado;
    }

    public function ler_representantes_digitais($pn_codigo, $pn_representante_digital_tipo_codigo = '')
    {

        $this->banco_dados = $this->get_banco();

        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_campos_select = array();
        $va_joins_select = array();
        $va_wheres_select = array();

        $va_campos_select[] = "representante_digital.codigo as representante_digital_codigo";
        $va_campos_select[] = "representante_digital.recurso_sistema_codigo";
        $va_campos_select[] = "representante_digital.registro_codigo";
        $va_campos_select[] = "representante_digital.formato as representante_digital_formato";
        $va_campos_select[] = "representante_digital.tipo_codigo as representante_digital_tipo_codigo";
        $va_campos_select[] = "representante_digital.path as representante_digital_path";
        $va_campos_select[] = "representante_digital.legenda as representante_digital_legenda";
        $va_campos_select[] = "representante_digital.sequencia as sequencia";
        $va_campos_select[] = "representante_digital.publicado_online as representante_digital_publicado_online";

        $va_wheres_select[] = "representante_digital.recurso_sistema_codigo = (?) ";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $this->recurso_sistema_codigo;

        $va_wheres_select[] = "representante_digital.registro_codigo = (?) ";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $pn_codigo;

        if ($pn_representante_digital_tipo_codigo) {
            $va_wheres_select[] = "representante_digital.tipo = (?) ";
            $va_tipos_parametros_select[] = "i";
            $va_parametros_select[] = $pn_representante_digital_tipo_codigo;
        }

        $va_selects[] = [
            "tabela" => "representante_digital",
            "campos" => $va_campos_select,
            "joins" => $va_joins_select,
            "wheres" => $va_wheres_select,
        ];

        $va_resultado = $this->banco_dados->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, ["sequencia"]);

        return $va_resultado;
    }

    public function ler_thumb_item_acervo($pn_item_acervo_codigo)
    {

        $this->banco_dados = $this->get_banco();

        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_campos_select = array();
        $va_joins_select = array();
        $va_wheres_select = array();

        $va_campos_select[] = "representante_digital.path as representante_digital_path";
        $va_campos_select[] = "representante_digital.sequencia as sequencia";

        $va_joins_select["documento"] = " JOIN documento ON representante_digital.registro_codigo = documento.codigo ";

        $va_wheres_select[] = "representante_digital.recurso_sistema_codigo = 1";

        $va_wheres_select[] = "documento.texto_codigo = (?) ";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $pn_item_acervo_codigo;

        $va_wheres_select[] = "representante_digital.publicado_online = 1 ";

        $va_selects[] = [
            "tabela" => "representante_digital",
            "campos" => $va_campos_select,
            "joins" => $va_joins_select,
            "wheres" => $va_wheres_select,
        ];

        $va_resultado = $this->banco_dados->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, ["sequencia"], " LIMIT 0, 1 ");

        if (count($va_resultado))
            return $va_resultado[0]["representante_digital_path"];
        else
            return "";
    }

    public function ler_etapa_fluxo_registro($pn_codigo)
    {

        $this->banco_dados = $this->get_banco();

        $va_tipos_parametros_select = array();
        $va_parametros_select = array();
        $va_campos_select = array();
        $va_joins_select = array();
        $va_wheres_select = array();

        $va_campos_select[] = "registro_etapa_fluxo.etapa_fluxo_codigo";
        $va_campos_select[] = "registro_etapa_fluxo.data";

        $va_wheres_select[] = "registro_etapa_fluxo.recurso_sistema_codigo = (?) ";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $this->recurso_sistema_codigo;

        $va_wheres_select[] = "registro_etapa_fluxo.registro_codigo = (?) ";
        $va_tipos_parametros_select[] = "i";
        $va_parametros_select[] = $pn_codigo;

        $va_order_by = "registro_etapa_fluxo.data DESC";

        $va_selects[] = [
            "tabela" => "registro_etapa_fluxo",
            "campos" => $va_campos_select,
            "joins" => $va_joins_select,
            "wheres" => $va_wheres_select,
        ];

        $va_resultado = $this->banco_dados->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select, $va_order_by, "LIMIT 0,1");

        if (count($va_resultado))
            return $va_resultado[0]["etapa_fluxo_codigo"];
        else
            return "";
    }

    public function verificar_valores_duplicados($pa_valores, $pn_idioma_codigo = 1)
    {
        $va_atributos_objeto = $this->atributos;
        $va_atributos_objeto_pai = array();

        if ($this->objeto_pai) {
            $vs_objeto_pai_id = $this->objeto_pai;
            $vo_objeto_pai = new $vs_objeto_pai_id($vs_objeto_pai_id);

            $va_atributos_objeto_pai = $vo_objeto_pai->get_atributos();
        }

        foreach ($pa_valores as $vs_key_atributo => $vs_valor) {
            $va_atributo = array();
            $vb_atributo_objeto_pai = false;

            if (trim($vs_valor) != "") {
                // Vamos ver se é atributo do próprio objeto
                if (isset($va_atributos_objeto[$vs_key_atributo]) && isset($va_atributos_objeto[$vs_key_atributo]["valor_nao_repete"])) {
                    $va_atributo = $va_atributos_objeto[$vs_key_atributo];
                } elseif (count($va_atributos_objeto_pai) && isset($va_atributos_objeto_pai[$vs_key_atributo]) && isset($va_atributos_objeto_pai[$vs_key_atributo]["valor_nao_repete"])) {
                    $vb_atributo_objeto_pai = true;
                    $va_atributo = $va_atributos_objeto_pai[$vs_key_atributo];
                }

                if (count($va_atributo)) {
                    $va_selects = array();
                    $va_campos_select = array();
                    $va_joins_select = array();
                    $va_wheres_select = array();
                    $va_tipos_parametros_select = array();
                    $va_parametros_select = array();

                    // Adiciona a chave primária ao select à força

                    $vs_tabela_banco = $this->tabela_banco;
                    $vs_coluna_chave_primaria = $this->chave_primaria["coluna_tabela"];

                    $va_campos_select[] = $vs_tabela_banco . "." . $vs_coluna_chave_primaria;

                    if ($pa_valores[$this->chave_primaria[0]]) {
                        $va_wheres_select[] = $vs_tabela_banco . "." . $vs_coluna_chave_primaria . " != (?)";
                        $va_tipos_parametros_select[] = "i";
                        $va_parametros_select[] = $pa_valores[$this->chave_primaria[0]];
                    }

                    if ($vb_atributo_objeto_pai) {
                        $va_joins_select[$vo_objeto_pai->tabela_banco] = " JOIN " . $vo_objeto_pai->tabela_banco . " ON " . $vs_tabela_banco . "." . $this->campo_relacionamento_pai . " = " . $vo_objeto_pai->tabela_banco . "." . $vo_objeto_pai->chave_primaria["coluna_tabela"];
                        $vs_tabela_banco = $vo_objeto_pai->tabela_banco;
                    }

                    $va_wheres_select[] = $vs_tabela_banco . "." . $va_atributo["coluna_tabela"] . " = (?)";
                    $va_tipos_parametros_select[] = $va_atributo["tipo_dado"];
                    $va_parametros_select[] = $vs_valor;

                    if (isset($va_atributo["valor_nao_repete"]["contexto"])) {
                        foreach ($va_atributo["valor_nao_repete"]["contexto"] as $vs_contexto) {
                            if (isset($this->atributos[$vs_contexto]) && isset($pa_valores[$vs_contexto])) {
                                $va_wheres_select[] = $vs_tabela_banco . "." . $this->atributos[$vs_contexto]["coluna_tabela"] . " = (?)";
                                $va_tipos_parametros_select[] = $this->atributos[$vs_contexto]["tipo_dado"];
                                $va_parametros_select[] = $pa_valores[$vs_contexto];
                            }
                        }
                    }

                    $va_selects[] = [
                        "tabela" => $this->tabela_banco,
                        "campos" => $va_campos_select,
                        "joins" => $va_joins_select,
                        "wheres" => $va_wheres_select,
                    ];

                    $vo_banco = $this->get_banco();
                    $va_resultado = $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select);

                    if (count($va_resultado)) {
                        return $vs_key_atributo;
                    }
                }
            }
        }

        return 1;
    }

    public function salvar($pa_valores, $pb_logar_operacao = true, $pn_idioma_codigo = 1, $pb_salvar_objeto_pai = true, $ps_id_objeto_filho = '', $pb_sobrescrever = true)
    {
        $this->banco_dados = $this->get_banco();

        $vb_iniciada_transacao = $this->iniciar_transacao();

        // Se existe objeto pai que precisa ser salvo
        /////////////////////////////////////////////

        if (($this->objeto_pai) && ($pb_salvar_objeto_pai)) 
        {
            $vs_id_objeto_pai = $this->objeto_pai;
            $vo_objeto_pai = new $vs_id_objeto_pai($vs_id_objeto_pai);

            // Se é atualização, o código do objeto pai existe
            // Se ele não é passado pelo form, tem que recuperar do banco
            /////////////////////////////////////////////////////////////

            if (isset($pa_valores[$this->chave_primaria[0]]) && $pa_valores[$this->chave_primaria[0]] && !isset($pa_valores[$vo_objeto_pai->get_chave_primaria()[0]]))
            {
                $vn_codigo = $pa_valores[$this->chave_primaria[0]];
                
                $va_objeto = $this->ler($vn_codigo, "lista");
                $pa_valores[$vo_objeto_pai->get_chave_primaria()[0]] = $va_objeto[$vo_objeto_pai->get_chave_primaria()[0]];
            }

            $pa_valores[$vo_objeto_pai->get_chave_primaria()[0]] = $vo_objeto_pai->salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo, null, get_class($this), $pb_sobrescrever);
        }

        ////////////////////////////////////////////////

        $vb_inserir = true;    
        if (isset($pa_valores[$this->chave_primaria[0]]) && $pa_valores[$this->chave_primaria[0]])
        {
            $vb_inserir = false;
            $vn_codigo = $pa_valores[$this->chave_primaria[0]];
        }

        ////////////////////////////////////////////////

        if ($vb_inserir)
            $vn_codigo = $this->inserir($pa_valores, $ps_id_objeto_filho);
        else
            $this->atualizar($pa_valores, $ps_id_objeto_filho);

        $this->salvar_relacionamentos($pa_valores, $vn_codigo, $pn_idioma_codigo, $vb_inserir, $pb_sobrescrever);

        $this->salvar_etapas_fluxos_registro($pa_valores, $vn_codigo);

        if ($pb_logar_operacao) 
        {
            $vo_log = new Log;

            $va_valores['log_id_registro'] = get_class($this);
            $va_valores['log_codigo_registro'] = $vn_codigo;
            $va_valores['log_usuario_codigo'] = $pa_valores['usuario_logado_codigo'];
            $va_valores['log_data'] = date('Y-m-d H:i:s');

            if (isset($pa_valores["log_alteracoes"][get_class($this)]) && count($pa_valores["log_alteracoes"][get_class($this)]))
            {
                foreach($pa_valores["log_alteracoes"][get_class($this)] as $vn_tipo_operacao_log)
                {
                    $va_valores['log_tipo_operacao_codigo'] = $vn_tipo_operacao_log;
                    $vo_log->salvar($va_valores, false);  
                }
            }
            else
            {
                $va_valores['log_tipo_operacao_codigo'] = ($vb_inserir) ? 1 : 2;
                $vo_log->salvar($va_valores, false);
            }
        }

        if ($vb_iniciada_transacao)
            $this->banco_dados->finalizar_transacao();

        return $vn_codigo;
    }

    public function salvar_log($pn_objeto_codigo, $pn_usuario_codigo, $pn_tipo_operacao_log_codigo)
    {
        $vo_log = new Log;
        $va_valores_log = array();

        $va_valores_log['log_id_registro'] = get_class($this);
        $va_valores_log['log_codigo_registro'] = $pn_objeto_codigo;
        $va_valores_log['log_usuario_codigo'] = $pn_usuario_codigo;
        $va_valores_log['log_data'] = date('Y-m-d H:i:s');
        $va_valores_log['log_tipo_operacao_codigo'] = $pn_tipo_operacao_log_codigo;

        $vo_log->salvar($va_valores_log, false);
    }

    public function excluir_logs($pn_objeto_codigo)
    {

        $this->banco_dados = $this->get_banco();

        $this->inicializar_variaveis_banco();

        $this->va_wheres[] = "log.id_registro = ?";
        $this->va_wheres[] = "log.registro_codigo = ?";

        $this->va_tipos_parametros[] = "s";
        $this->va_parametros[] = get_class($this);

        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pn_objeto_codigo;

        $this->banco_dados->excluir("log", $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);

    }

    public function excluir_etapas_fluxo($pn_objeto_codigo)
    {
        if ($this->recurso_sistema_codigo) {

            $this->banco_dados = $this->get_banco();

            $this->inicializar_variaveis_banco();

            $this->va_wheres[] = "registro_etapa_fluxo.recurso_sistema_codigo = ?";
            $this->va_wheres[] = "registro_etapa_fluxo.registro_codigo = ?";

            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = ($this->recurso_sistema_codigo);

            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $pn_objeto_codigo;

            $this->banco_dados->excluir("registro_etapa_fluxo", $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);
        }
    }

    public function salvar_etapas_fluxos_registro($pa_valores, $pn_codigo)
    {
        if (isset($this->recurso_sistema_codigo) && $this->recurso_sistema_codigo) 
        {
            $vn_recurso_sistema_codigo = $this->recurso_sistema_codigo;

            $vo_fluxo = new fluxo;
            $va_fluxos = $vo_fluxo->ler_lista(["fluxo_recurso_sistema_codigo" => $vn_recurso_sistema_codigo], "ficha");

            foreach ($va_fluxos as $va_fluxo) 
            {
                $vn_etapa_fluxo_corrente_codigo = $this->ler_etapa_fluxo_registro($pn_codigo);

                // Se não veio o valor da etapa do fluxo, é porque o (grupo do) usuário não tem acesso a essa etapa,
                // então o valor deve ser substituído por uma etapa à qual o (grupo do) usuário tem acesso
                //////////////////////////////////////////////////////////////////////////////////////////

                if (!isset($pa_valores["etapa_fluxo_codigo_" . $va_fluxo["fluxo_codigo"]])) 
                {
                    $vn_etapa_fluxo_substitutiva_codigo = $vo_fluxo->ler_etapa_com_acesso_salvar($va_fluxos, $vn_etapa_fluxo_corrente_codigo);

                    if ($vn_etapa_fluxo_substitutiva_codigo)
                        $pa_valores["etapa_fluxo_codigo_" . $va_fluxo["fluxo_codigo"]] = $vn_etapa_fluxo_substitutiva_codigo;
                }

                if (isset($pa_valores["etapa_fluxo_codigo_" . $va_fluxo["fluxo_codigo"]])) 
                {
                    $vn_etapa_fluxo_codigo = (int)$pa_valores["etapa_fluxo_codigo_" . $va_fluxo["fluxo_codigo"]];

                    $this->banco_dados = $this->get_banco();

                    // Só insere nova etapa se houve alteração de etapa
                    ///////////////////////////////////////////////////

                    if ($vn_etapa_fluxo_codigo != $vn_etapa_fluxo_corrente_codigo) 
                    {
                        $this->inicializar_variaveis_banco();

                        $this->va_campos[] = "recurso_sistema_codigo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $vn_recurso_sistema_codigo;

                        $this->va_campos[] = "registro_codigo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $pn_codigo;

                        $this->va_campos[] = "etapa_fluxo_codigo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $vn_etapa_fluxo_codigo;

                        $this->va_campos[] = "data";
                        $this->va_tipos_parametros[] = "s";
                        $this->va_parametros[] = date('Y-m-d H:i:s');

                        $this->va_campos[] = "usuario_codigo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $pa_valores['usuario_logado_codigo'];

                        $this->banco_dados->inserir("registro_etapa_fluxo", $this->va_campos, $this->va_tipos_parametros, $this->va_parametros);

                        foreach ($va_fluxo["fluxo_etapa_fluxo_codigo"] as $va_etapa_fluxo)
                        {
                            if (isset($va_etapa_fluxo["etapa_fluxo_tipo_operacao_log_codigo"]))
                            {
                                $this->salvar_log($pn_codigo, $pa_valores['usuario_logado_codigo'], $va_etapa_fluxo["etapa_fluxo_tipo_operacao_log_codigo"]["tipo_operacao_log_codigo"]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function salvar_representantes_digitais($ps_campo_nome, $pa_valores, $pa_arquivos, $pb_logar_operacao = false)
    {

        // cria os diretórios, caso não existam
        $this->criar_diretorios();

        $vb_salvou_arquivo_disco = false;

        $va_pasta_media = config::get(["pasta_media"]);
        $va_pasta_images = $va_pasta_media["images"];

        if ($this->recurso_sistema_codigo) 
        {
            $vn_objeto_codigo = "";
            $vb_criou_registro = false;

            $this->banco_dados = $this->get_banco();
            $vb_iniciada_transacao = $this->iniciar_transacao();

            // Exclui representantes digitais a partir da lista de códigos enviada
            //////////////////////////////////////////////////////////////////////

            if (isset($pa_valores['rd_codigos_remover_' . $ps_campo_nome]) && $pa_valores['rd_codigos_remover_' . $ps_campo_nome]) 
            {
                $vo_representante_digital = new representante_digital;

                $va_codigos_excluir = explode(",", $pa_valores['rd_codigos_remover_' . $ps_campo_nome]);

                foreach ($va_codigos_excluir as $vn_codigo_excluir)
                {
                    $va_representante_digital = $vo_representante_digital->ler($vn_codigo_excluir);
                    if (count($va_representante_digital))
                    {
                        $this->apagar_arquivo_pasta_media($va_representante_digital['representante_digital_path']);
                    }
                }

                $vo_representante_digital->excluir($pa_valores['rd_codigos_remover_' . $ps_campo_nome]);

                if (isset($pa_valores["representante_digital_tipo_codigo"]) && $pa_valores["representante_digital_tipo_codigo"] == 1)
                    $vn_tipo_operacao_log_codigo = 7;
                else
                    $vn_tipo_operacao_log_codigo = 8;

                $this->salvar_log($pa_valores[$this->chave_primaria[0]], $pa_valores['usuario_logado_codigo'], $vn_tipo_operacao_log_codigo);
            }

            // Atualiza a sequência dos representantes digitais a partir dos códigos enviados
            ////////////////////////////////////////////////////////////////////////////////////////

            $contador_sequencial = 1;

            if (isset($pa_valores[$ps_campo_nome]) && $pa_valores[$ps_campo_nome]) 
            {
                $va_valores_base = explode("|", $pa_valores[$ps_campo_nome]);

                foreach ($va_valores_base as $vn_valor_base) 
                {
                    $this->inicializar_variaveis_banco();

                    $this->va_campos[] = "tipo_codigo";
                    $this->va_tipos_parametros[] = "i";
                    if (isset($pa_valores["representante_digital_tipo_codigo_" . $vn_valor_base]) && $pa_valores["representante_digital_tipo_codigo_" . $vn_valor_base] != "")
                    {
                        $this->va_parametros[] = $pa_valores["representante_digital_tipo_codigo_" . $vn_valor_base];
                    } else {
                        $this->va_parametros[] = null;
                    }

                    $vs_legenda = null;
                    if (isset($pa_valores["representante_digital_legenda_" . $vn_valor_base]) && $pa_valores["representante_digital_legenda_" . $vn_valor_base] != "")
                        $vs_legenda = $pa_valores["representante_digital_legenda_" . $vn_valor_base];

                    $this->va_campos[] = "legenda";
                    $this->va_tipos_parametros[] = "s";
                    $this->va_parametros[] = $vs_legenda;

                    $this->va_campos[] = "sequencia";
                    $this->va_tipos_parametros[] = "i";
                    $this->va_parametros[] = $contador_sequencial;

                    $vb_publicado_online = 0;
                    if (isset($pa_valores["representante_digital_publicado_online_" . $vn_valor_base]))
                        $vb_publicado_online = 1;

                    $this->va_campos[] = "publicado_online";
                    $this->va_tipos_parametros[] = "i";
                    $this->va_parametros[] = $vb_publicado_online;

                    $this->va_wheres[] = "representante_digital.codigo = (?) ";
                    $this->va_tipos_parametros[] = "i";
                    $this->va_parametros[] = $vn_valor_base;

                    $this->banco_dados->atualizar("representante_digital", $this->va_campos, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);

                    $contador_sequencial++;
                }
            }

            // Redimensionamos e salvamos os novos representantes digitais, se existirem, colocando-os na sequência dos anteriores
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $va_arquivos_importados_ids = array();

            foreach ($pa_arquivos as $va_arquivo) 
            {
                if ($va_arquivo["size"] > 0 && $va_arquivo["error"] == 0)
                {
                    // Antes de salvar o arquivo em disco, verifica se o registro catalográfico existe no
                    // banco, isto é, se $pa_valores[$this->chave_primaria[0]] vem preenchido com um código
                    //////////////////////////////////////////////////////////////////////////////////////

                    $vb_salvar_representante_digital_banco = true;
                    $vb_salvar_arquivo_disco = true;

                    if (!isset($pa_valores[$this->chave_primaria[0]])) 
                    {
                        // Verifica se o nome do arquivo vai servir como identificador do documento
                        ///////////////////////////////////////////////////////////////////////////

                        if (isset($pa_valores["arquivos_importados"]) && $pa_valores["arquivos_importados"]) 
                        {
                            $va_pares_arquivos = explode(";", $pa_valores["arquivos_importados"]);

                            foreach ($va_pares_arquivos as $va_arquivo_importado) 
                            {
                                $va_info_arquivo = explode("|", $va_arquivo_importado);
                                if (isset($va_info_arquivo[0]) && isset($va_info_arquivo[1]))
                                {
                                    $va_arquivos_importados_ids[$va_info_arquivo[0]] = $va_info_arquivo[1];
                                }
                            }
                        }

                        // Vamos armazenar um identificador base para o arquivo a partir do nome,
                        //  caso precisemos importar várias "páginas" para o mesmo registro

                        $vs_id_base_arquivo = $va_arquivo["name"];

                        if (strpos(strrev($vs_id_base_arquivo), ".") == 3)
                            $vs_id_base_arquivo = substr($vs_id_base_arquivo, 0, strlen($vs_id_base_arquivo) - 4);

                        // Se o nome do arquivo vem com um separador de paginação/sequência,
                        // recupera o número da página e o nome base do arquivo
                        ////////////////////////////////////////////////////////////////////

                        $contador_sequencial = 1;

                        if (isset($pa_valores["processar_paginacao"]) && ($pa_valores["processar_paginacao"])) 
                        {
                            if (isset($pa_valores["separador_paginacao"]) && ($pa_valores["separador_paginacao"])) {
                                $vs_separador_paginacao = $pa_valores["separador_paginacao"];

                                if (strripos($vs_id_base_arquivo, $vs_separador_paginacao) !== FALSE) 
                                {
                                    $vn_posicao_separador = strripos($vs_id_base_arquivo, $vs_separador_paginacao);

                                    if (isset($pa_valores["largura_paginacao"]) && ($pa_valores["largura_paginacao"]))
                                        $contador_sequencial = intval(substr($vs_id_base_arquivo, ($vn_posicao_separador + strlen($vs_separador_paginacao)), $pa_valores["largura_paginacao"]));
                                    else
                                        $contador_sequencial = intval(substr($vs_id_base_arquivo, ($vn_posicao_separador + strlen($vs_separador_paginacao)), (strlen($vs_id_base_arquivo) - $vn_posicao_separador - 1)));

                                    $vs_id_base_arquivo = substr($vs_id_base_arquivo, 0, $vn_posicao_separador);
                                }
                            } 
                            elseif (isset($pa_valores["largura_paginacao"]) && ($pa_valores["largura_paginacao"])) 
                            {
                                $vn_largura_paginacao = $pa_valores["largura_paginacao"];

                                $contador_sequencial = intval(substr($vs_id_base_arquivo, (strlen($vs_id_base_arquivo) - $vn_largura_paginacao), $vn_largura_paginacao));
                                $vs_id_base_arquivo = substr($vs_id_base_arquivo, 0, strlen($vs_id_base_arquivo) - $vn_largura_paginacao - 1);
                            }
                        }

                        if (in_array($vs_id_base_arquivo, array_keys($va_arquivos_importados_ids)))
                            $vn_objeto_codigo = $va_arquivos_importados_ids[$vs_id_base_arquivo];
                        else 
                        {
                            $vs_atributo_identificador = "";
                            $va_objeto = array();

                            if (class_exists("texto"))
                                $vs_atributo_identificador = "texto_codigo_0_item_acervo_identificador";
                            elseif (class_exists("item_acervo"))
                                $vs_atributo_identificador = "item_acervo_codigo_0_item_acervo_identificador";

                            if ($vs_atributo_identificador)
                                $va_objeto = $this->ler_lista([$vs_atributo_identificador => $vs_id_base_arquivo], "lista", 1, 1);

                            if (count($va_objeto)) 
                            {
                                $vn_objeto_codigo = $va_objeto[0][$this->chave_primaria[0]];

                                $va_representantes_digitais = $this->ler_representantes_digitais($vn_objeto_codigo, 1);

                                $vs_nome_arquivo_destino = md5($this->recurso_sistema_codigo . $vn_objeto_codigo . $va_arquivo["name"]) . ".jpg";

                                foreach ($va_representantes_digitais as $va_representante_digital) {
                                    if (($vs_nome_arquivo_destino == $va_representante_digital["representante_digital_path"]) && ($contador_sequencial == $va_representante_digital["sequencia"])) {
                                        $vb_salvar_representante_digital_banco = false;
                                        break;
                                    }
                                }
                            }
                        }


                        $vs_selecao_nome = $pa_valores["selecao_nome"];

                        $vo_selecao = new selecao('');
                        $va_selecao = $vo_selecao->ler_lista(["selecao_nome" => $vs_selecao_nome], "lista");

                        if (count($va_selecao))
                            $vn_selecao_codigo = $va_selecao[0]["selecao_codigo"];
                        else
                        {
                            $va_valores_selecao["selecao_nome"] = $vs_selecao_nome;
                            $va_valores_selecao["selecao_tipo_codigo"] = 1;
                            $va_valores_selecao["usuario_logado_codigo"] = $pa_valores['usuario_logado_codigo'];
                            $va_valores_selecao["selecao_recurso_sistema_codigo"] = $this->recurso_sistema_codigo;

                            $vn_selecao_codigo = $vo_selecao->salvar($va_valores_selecao);
                        }


                        if ($vn_objeto_codigo)
                        {
                            $pa_valores[$this->chave_primaria[0]] = $vn_objeto_codigo;
                        }
                        elseif (isset($pa_valores["criar_registros"]) && ($pa_valores["criar_registros"]))
                        {

                            if (isset($pa_valores["nome_arquivo_identificador"]) && ($pa_valores["nome_arquivo_identificador"]))
                                $pa_valores["item_acervo_identificador"] = $vs_id_base_arquivo;

                            $pa_valores["item_selecao_codigo"] = $vn_selecao_codigo;
                            $pa_valores[$this->chave_primaria[0]] = $this->salvar($pa_valores, true, 1);

                            $vn_objeto_codigo = $pa_valores[$this->chave_primaria[0]];
                            $vb_criou_registro = true;
                        }
                        else
                        {
                            // Se o registro não existe no banco e não é para criar, não faz nada como esse arquivo
                            ///////////////////////////////////////////////////////////////////////////////////////

                            return false;
                        }

                        $vo_selecao->adicionar_item($vn_selecao_codigo, $vn_objeto_codigo);
                        print $vs_id_base_arquivo . "|" . $vn_objeto_codigo;
                    }

                    //////////////////////////////////////////////////////////////////////////////////////

                    // Melhoria necessária: ler o último número de sequência do banco 
                    // para os próximo arquivo deste registro
                    // (apenas se a paginação não veio no nome do arquivo)
                    /////////////////////////////////////////////////////////////////

                    if (!isset($pa_valores["processar_paginacao"]) || (isset($pa_valores["processar_paginacao"]) && !$pa_valores["processar_paginacao"]) ) 
                        $contador_sequencial = $this->ler_proximo_numero_sequencia_representante_digital($pa_valores[$this->chave_primaria[0]], $pa_valores["representante_digital_tipo_codigo"]);

                    /////////////////////////////////////////////////////////////////
                    ///

                    $vb_salvar_arquivo_original = isset($pa_valores["salvar_arquivo_original"]) ?
                        boolval($pa_valores["salvar_arquivo_original"]) :
                        config::get(["salvar_arquivo_original"]);

                    $va_extensoes_permitidas = config::get(["extensoes_permitidas"]);
                    $media_types = config::get(["media_types"]) ?? [];

                    $vs_novo_nome_arquivo = md5($this->recurso_sistema_codigo . $pa_valores[$this->chave_primaria[0]] . $va_arquivo["name"] . rand());

                    $va_path_arquivo_destino = [
                        "large" => [
                            "path" => $va_pasta_images["large"],
                            "width" => 1440,
                        ],
                        "medium" => [
                            "path" => $va_pasta_images["medium"],
                            "width" => 800,
                        ],
                        "thumb" => [
                            "path" => $va_pasta_images["thumb"],
                            "width" => 360,
                        ]
                    ];

                    $vb_salvou_arquivo_disco = false;

                    $vs_mime_type = mime_content_type($va_arquivo["tmp_name"]);
                    $vs_formato = $media_types[$vs_mime_type]["format"] ?? null;

                    if (mime_content_type($va_arquivo["tmp_name"]) == $va_extensoes_permitidas["pdf"])
                    {
                        $vs_formato = "pdf";

                        if (class_exists('Imagick'))
                        {
                            foreach ($va_path_arquivo_destino as $key => $value)
                            {
                                $size = $value["width"];
                                $path = $value["path"] . $vs_novo_nome_arquivo . ".jpg";

                                $this->gerar_jpg_pdf($va_arquivo["tmp_name"], $size, $path);
                            }
                        }

                        $vs_novo_nome_arquivo = $vs_novo_nome_arquivo . "." . $vs_formato;

                        if ($this->mover_arquivo($va_arquivo["tmp_name"], $va_pasta_images["original"] . $vs_novo_nome_arquivo))
                        {
                            $vb_salvou_arquivo_disco = true;
                        }
                        else
                        {
                            utils::log("erro", "Erro ao mover arquivo: " . $va_arquivo["tmp_name"] . " - " . $va_pasta_images["original"] . $vs_novo_nome_arquivo);
                        }

                    }
                    elseif (@getimagesize($va_arquivo["tmp_name"]))
                    {
                        $vs_formato = "jpg";
                        $vs_novo_nome_arquivo = $vs_novo_nome_arquivo . "." . $vs_formato;

                        $vn_imagens_processadas = 0;

                        foreach ($va_path_arquivo_destino as $key => $value)
                        {
                            $size = $value["width"];
                            $path = $value["path"] . $vs_novo_nome_arquivo;

                            if ($this->processar_imagem($va_arquivo["tmp_name"], $size, $path))
                            {
                                $vn_imagens_processadas++;
                            }
                            else
                            {
                                utils::log("erro", "Erro ao processar imagem: " . $va_arquivo["tmp_name"] . " - " . $size . " - " . $path);
                            }
                        }

                        if ($vb_salvar_arquivo_original)
                        {
                            if ($this->mover_arquivo($va_arquivo["tmp_name"], $va_pasta_images["original"] . $vs_novo_nome_arquivo))
                            {
                                $vn_imagens_processadas++;
                            }
                        }

                        if ($vn_imagens_processadas > 1)
                        {
                            $vb_salvou_arquivo_disco = true;
                        }
                    }
                    elseif (in_array($va_arquivo["type"], array_values($va_extensoes_permitidas)))
                    {
                        foreach ($media_types as $type => $media)
                        {
                            if ($va_arquivo["type"] == $type)
                            {
                                $vs_pasta_media = $va_pasta_media[$media["folder"]];
                                $vs_formato = $media["format"];
                                $vs_novo_nome_arquivo = $vs_novo_nome_arquivo . "." . $vs_formato;

                                $va_path_arquivo_destino = $vs_pasta_media .  $vs_novo_nome_arquivo;

                                if ($this->mover_arquivo($va_arquivo["tmp_name"], $va_path_arquivo_destino))
                                {
                                    $vb_salvou_arquivo_disco = true;
                                    break;
                                }
                                else
                                {
                                    utils::log("erro", "Erro ao mover arquivo: " . $va_arquivo["tmp_name"] . " - " . $va_path_arquivo_destino);
                                }
                            }
                        }
                    }

                    if ($vb_salvou_arquivo_disco && $vb_salvar_representante_digital_banco) 
                    {
                        $this->inicializar_variaveis_banco();

                        $this->va_campos[] = "recurso_sistema_codigo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $this->recurso_sistema_codigo;

                        $this->va_campos[] = "registro_codigo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $pa_valores[$this->chave_primaria[0]];

                        $this->va_campos[] = "tipo";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $pa_valores["representante_digital_tipo_codigo"];

                        $this->va_campos[] = "formato";
                        $this->va_tipos_parametros[] = "s";
                        $this->va_parametros[] = $vs_formato;

                        $this->va_campos[] = "path";
                        $this->va_tipos_parametros[] = "s";
                        $this->va_parametros[] = $vs_novo_nome_arquivo;

                        $this->va_campos[] = "sequencia";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = $contador_sequencial;

                        $this->va_campos[] = "publicado_online";
                        $this->va_tipos_parametros[] = "i";
                        $this->va_parametros[] = 1;

                        $this->va_campos[] = "nome_original";
                        $this->va_tipos_parametros[] = "s";
                        $this->va_parametros[] = $va_arquivo["name"];

                        if (isset($pa_valores["tipo_representante_digital_codigo"]))
                        {
                            $this->va_campos[] = "tipo_codigo";
                            $this->va_tipos_parametros[] = "i";
                            $this->va_parametros[] = $pa_valores["tipo_representante_digital_codigo"];
                        }

                        $this->banco_dados->inserir("representante_digital", $this->va_campos, $this->va_tipos_parametros, $this->va_parametros);

                        if (isset($pa_valores['upload_logged_' . $ps_campo_nome]))
                        {
                            if (!$pa_valores['upload_logged_' . $ps_campo_nome])
                            {
                                if ($pa_valores["representante_digital_tipo_codigo"] == 1)
                                    $vn_tipo_operacao_log_codigo = 3;
                                else
                                    $vn_tipo_operacao_log_codigo = 4;

                                $this->salvar_log($pa_valores[$this->chave_primaria[0]], $pa_valores['usuario_logado_codigo'], $vn_tipo_operacao_log_codigo);
                                print "logged";
                            }
                        }
                        elseif (!in_array($pa_valores[$this->chave_primaria[0]], $va_arquivos_importados_ids))
                        {
                            $this->salvar_log($pa_valores[$this->chave_primaria[0]], $pa_valores['usuario_logado_codigo'], 3);
                        }

                        $contador_sequencial++;
                    }
                }
            }

            if (isset($pa_valores["links"]) && $pa_valores["links"] != '[]')
            {

                $va_links = json_decode($pa_valores["links"], true);

                foreach ($va_links as $vs_link)
                {
                    $this->inicializar_variaveis_banco();

                    $this->va_campos = ["recurso_sistema_codigo", "registro_codigo", "tipo", "formato", "path", "sequencia", "publicado_online"];
                    $this->va_tipos_parametros = ["i", "i", "i", "s", "s", "i", "i"];
                    $this->va_parametros = [$this->recurso_sistema_codigo, $pa_valores[$this->chave_primaria[0]], 1, "link", $vs_link, $contador_sequencial, 1];

                    $this->banco_dados->inserir("representante_digital", $this->va_campos, $this->va_tipos_parametros, $this->va_parametros);

                    $contador_sequencial++;
                }

                $vb_salvou_arquivo_disco = true;
            }

            if ($vb_iniciada_transacao)
                $this->banco_dados->finalizar_transacao();

        }

        if (!$vb_salvou_arquivo_disco) {
            header("HTTP/1.0 500 Internal Server Error");
        }

        return $vb_salvou_arquivo_disco;
    }

    function mover_arquivo($ps_origem, $ps_destino): bool
    {
        if (!move_uploaded_file($ps_origem, $ps_destino))
        {
            if (!rename($ps_origem, $ps_destino))
            {
                utils::log("erro", "Erro ao mover arquivo: " . $ps_origem . " - " . $ps_destino);
                return false;
            }
        }

        return true;
    }


    function apagar_arquivo_pasta_media($ps_arquivo): bool
    {
        $va_pasta_media = config::get(["pasta_media"]);

        foreach ($va_pasta_media as $pasta)
        {
            if (is_array($pasta))
            {
                foreach ($pasta as $vs_pasta_media)
                {
                    $vs_arquivo = $vs_pasta_media . $ps_arquivo;
                    $this->apagar_arquivo($vs_arquivo);
                }
            }
            else
            {
                $vs_arquivo = $pasta . $ps_arquivo;
                $this->apagar_arquivo($vs_arquivo);
            }
        }

        return true;
    }

    function apagar_arquivo($ps_arquivo): bool
    {
        if (file_exists($ps_arquivo))
        {
            if (!unlink($ps_arquivo))
            {
                utils::log("erro", "Erro ao apagar arquivo: " . $ps_arquivo);
                return false;
            }
        }

        if (pathinfo($ps_arquivo, PATHINFO_EXTENSION) == "pdf")
        {
            $vs_arquivo_jpg = str_replace(".pdf", ".jpg", $ps_arquivo);
            $this->apagar_arquivo($vs_arquivo_jpg);
        }

        return true;
    }

    public static function gerar_jpg_pdf($ps_path_arquivo_imagem, $vn_tamanho_maior_lado_imagem, $ps_path_destino): bool
    {
        if (class_exists('Imagick'))
        {
            try
            {
                $image = new Imagick();
                $image->readImage($ps_path_arquivo_imagem . "[0]");
                $image->setImageFormat("jpeg");
                $image->scaleImage($vn_tamanho_maior_lado_imagem, $vn_tamanho_maior_lado_imagem, true);
                $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
                $image->writeImage($ps_path_destino);
                return true;
            }
            catch(Exception $e)
            {
                utils::log("Erro ao gerar imagem jpg a partir de pdf", $e->getMessage() . " - " . $ps_path_arquivo_imagem . " - " . $ps_path_destino);
                return false;
            }
        }
        
        return false;
    }

    private function processar_imagem_imagick($ps_path_arquivo_imagem, $vn_tamanho_maior_lado_imagem, $ps_path_destino): bool
    {
        try {
            $image = new Imagick($ps_path_arquivo_imagem);

            $image->scaleImage($vn_tamanho_maior_lado_imagem, $vn_tamanho_maior_lado_imagem, true);
            $image->writeImage($ps_path_destino);
            return true;
        } catch (Exception $e) {
            utils::log("Erro ao processar imagem", $e->getMessage() . " - " . $ps_path_arquivo_imagem . " - " . $ps_path_destino);
            return false;
        }
    }

    private function processar_imagem($ps_path_arquivo_imagem, $vn_tamanho_maior_lado_imagem, $ps_path_destino): bool
    {
        if (class_exists('Imagick'))
        {
            return $this->processar_imagem_imagick($ps_path_arquivo_imagem, $vn_tamanho_maior_lado_imagem, $ps_path_destino);
        }

        $va_image_size = getimagesize($ps_path_arquivo_imagem);
        $vs_mime = $va_image_size["mime"];

        switch ($vs_mime) 
        {
            case "image/jpeg":
                $source = imagecreatefromjpeg($ps_path_arquivo_imagem);
                break;

            case "image/png":
                $source = imagecreatefrompng($ps_path_arquivo_imagem);
                break;

            case "image/gif":
                $source = imagecreatefromgif($ps_path_arquivo_imagem);
                break;

            default:
                $source = null;
                break;
        }

        if (isset($source)) 
        {
            $vn_width = $va_image_size[0];
            $vn_height = $va_image_size[1];

            $ratio = $vn_width / $vn_height;

            if ($ratio > 1) {
                $new_width = $vn_tamanho_maior_lado_imagem;
                $new_height = intval($vn_tamanho_maior_lado_imagem / $ratio);
            } else {
                $new_width = intval($vn_tamanho_maior_lado_imagem * $ratio);
                $new_height = $vn_tamanho_maior_lado_imagem;
            }

            $thumb = imagescale($source, $new_width, $new_height);

            $vb_output = imagejpeg($thumb, $ps_path_destino);

            if ($vb_output)
            {
                return true;
            }
        }

        utils::log("Erro ao gerar imagem",  $ps_path_arquivo_imagem . " - " . $ps_path_destino);
        return false;
    }

    public function excluir_representantes_digitais($pn_objeto_codigo)
    {

        $vo_representante_digital = new representante_digital;

        $va_representantes_digitais = $this->ler_representantes_digitais($pn_objeto_codigo);

        foreach ($va_representantes_digitais as $va_representante_digital)
        {
            $vs_arquivo = $va_representante_digital['representante_digital_path'];

            if ($this->apagar_arquivo_pasta_media($vs_arquivo))
            {
                $vo_representante_digital->excluir($va_representante_digital["representante_digital_codigo"]);
            }
        }
    }

    public function excluir_relacionamentos_selecoes($pn_objeto_codigo)
    {
        $vo_selecao = new selecao('');
        $va_selecoes = $vo_selecao->ler_lista(["selecao_recurso_sistema_codigo" => $this->recurso_sistema_codigo, "selecao_item_codigo" => $pn_objeto_codigo]);

        foreach ($va_selecoes as $va_selecao) {
            $vo_selecao->remover_item($va_selecao["selecao_codigo"], $pn_objeto_codigo);
        }
    }

    public function importar($pa_valores, $pn_usuario_logado_codigo, $pb_sobrescrever = true)
    {   
        $vo_importacao = new importacao();
        $va_importacao = $vo_importacao->ler_lista(["importacao_recurso_sistema_codigo" => $this->recurso_sistema_codigo], "ficha");

        // Em princípio, a quantidade de valores lida do arquivo de importação 
        // tem que ser a mesma dos campos de importação configurados
        //////////////////////////////////////////////////////////////////////

        //if (!count($va_importacao) || (count($va_importacao[0]["importacao_campo_sistema_codigo"]) != count($pa_valores)))
            //return false;

        // Primeiro teremos que ver se o registro já existe a partir do identificador passado
        /////////////////////////////////////////////////////////////////////////////////////

        $va_objetos_importacao = array();
        $va_valores_importacao = array();
        $va_valores_nao_processados = array();

        // Cada valor lido do arquivo de importação é mapeado para os campos
        // configurados para importação do recurso através da ordem de aparecimento
        ///////////////////////////////////////////////////////////////////////////

        $contador_campos = 0;

        $va_campos_importacao = array();
        $va_valores_origem = array();
        $va_campos_agrupadores = array();
        
        foreach($va_importacao[0]["importacao_campo_sistema_codigo"] as $va_campo_importacao)
        {
            if ($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_tipo_codigo"]["tipo_campo_sistema_codigo"] == 6)
            {
                // Armazena os nomes dos campos agrupadores
                ///////////////////////////////////////////

                $va_campos_agrupadores[] = $va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_nome"];

                $va_campos_importacao["numero_" . $va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_nome"]] = $va_campo_importacao;
            }
            else
            {
                if (isset($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]) && in_array($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"], $va_campos_agrupadores))
                {
                    // Se o campo é subcampo de um campo agrupador (não autocomplete)
                    /////////////////////////////////////////////////////////////////

                    $vn_numero_valores_campo_agrupador = count(explode(";", trim($pa_valores[$contador_campos])));
                    $va_valores_origem["numero_" . $va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]] = $vn_numero_valores_campo_agrupador;
                }

                $vs_campo_importacao_nome = $va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_nome"];

                $va_campos_importacao[$vs_campo_importacao_nome] = $va_campo_importacao;
                $va_valores_origem[$vs_campo_importacao_nome] = trim($pa_valores[$contador_campos]);
                
                $contador_campos++;
            }
        }

        $vb_adicionou_valores = true;

        while (count($va_campos_importacao) && $vb_adicionou_valores)
        {
            $vb_adicionou_valores = false;
            $vs_identificador_registro = "";

            foreach($va_campos_importacao as $vs_key_campo_importacao => $va_campo_importacao)
            {
                $vb_adicionou_valor = false;
                $vs_valor = $va_valores_origem[$vs_key_campo_importacao];
                $vn_contador_sufixo = 0;
                $vs_sufixo_agrupador = "";
                $va_objetos_relacionados_codigos = array();

                if (isset($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]) || ($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_tipo_codigo"]["tipo_campo_sistema_codigo"] == 5))
                {
                    if (isset($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]))
                        $vn_contador_sufixo = 1;

                    $va_valor = explode(";", $vs_valor);
                }
                else
                    $va_valor = array($vs_valor);

                foreach($va_valor as $vs_valor)
                {
                    if ($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_identificador_recurso_sistema"])
                    {
                        // Se é um campo que identifica o registro, tenta ler o código do registro do banco
                        // Verifica se o campo identificador pertence ao objeto pai
                        // (Mas, na verdade tinha que fazer um loop até o objeto não possuir mais pai)
                        
                        if ($this->objeto_pai)
                        {
                            $vo_objeto_pai = new $this->objeto_pai;
                            
                            if (isset($vo_objeto_pai->atributos[$vs_key_campo_importacao]))
                                $va_parametro_leitura[$this->campo_relacionamento_pai . "_0_" . $vs_key_campo_importacao] = $vs_valor;
                        }
                        elseif (isset($this->atributos[$vs_key_campo_importacao]))
                            $va_parametro_leitura[$vs_key_campo_importacao] = $vs_valor;

                        $va_objeto = $this->ler_lista($va_parametro_leitura);

                        if (count($va_objeto)) 
                        {
                            $va_objeto = $va_objeto[0];

                            $va_valores_importacao[$this->chave_primaria[0]] = $va_objeto[$this->chave_primaria[0]];

                            if ($this->objeto_pai)
                                $va_valores_importacao[$this->campo_relacionamento_pai] = $va_objeto[$this->campo_relacionamento_pai];
                        }

                        $vs_identificador_registro = $vs_valor;
                        $va_valores_importacao[$vs_key_campo_importacao] = $vs_valor;
                        $vb_adicionou_valor = true;
                    }
                    elseif ($vs_valor != "")
                    {
                        if ($vn_contador_sufixo)
                            $vs_sufixo_agrupador = "_" . $vn_contador_sufixo;

                        if ($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_tipo_codigo"]["tipo_campo_sistema_codigo"] != 5)
                        {
                            // Se o campo não está associado a um objeto 
                            ////////////////////////////////////////////

                            if ($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_tipo_codigo"]["tipo_campo_sistema_codigo"] != 3)
                            {
                                // Se não é campo data, armazena o valor como foi passado
                                ///////////////////////////////////////////////////////////////////////////////
                            
                                $va_valores_importacao[$vs_key_campo_importacao . $vs_sufixo_agrupador] = $vs_valor;
                                $vb_adicionou_valor = true;
                            }
                            else
                            {
                                // Se é um campo data tem que interpretar a string de entrada
                                /////////////////////////////////////////////////////////////

                                $vo_data = new Periodo;

                                if ($vo_data->tratar_string($vs_valor))
                                {
                                    $va_valores_importacao[$vs_key_campo_importacao . $vs_sufixo_agrupador] = "_data_";

                                    $va_valores_importacao[$vs_key_campo_importacao . "_dia_inicial" . $vs_sufixo_agrupador] = $vo_data->get_dia_inicial();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_mes_inicial" . $vs_sufixo_agrupador] = $vo_data->get_mes_inicial();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_ano_inicial" . $vs_sufixo_agrupador] = $vo_data->get_ano_inicial();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_dia_final" . $vs_sufixo_agrupador] = $vo_data->get_dia_final();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_mes_final" . $vs_sufixo_agrupador] = $vo_data->get_mes_final();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_ano_final" . $vs_sufixo_agrupador] = $vo_data->get_ano_final();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_sem_data" . $vs_sufixo_agrupador] = $vo_data->get_sem_data();
                                    $va_valores_importacao[$vs_key_campo_importacao . "_presumido" . $vs_sufixo_agrupador] = $vo_data->get_presumido();

                                    $vb_adicionou_valor = true;
                                }
                            }
                        }
                        else
                        {
                            // Se o campo é um relacionamento com objeto, precisa ler o código
                            // do objeto ou criar um novo registro para esse objeto a partir do 
                            // valor passado que supostamente o identifica unicamente

                            if ($vs_valor == "0")
                            {
                                // Se o valor passado para o relacionamento é 0 (desconhecido),
                                // então devo passá-lo como ele veio (tratado em salvar_relacionamentos)

                                $va_objetos_relacionados_codigos[] = 0;
                                $vb_adicionou_valor = true;
                            }
                            else
                            {
                                $vn_objeto_relacionado_recurso_sistema_codigo = $va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_codigo"];

                                $vo_campo_sistema = new campo_sistema;
                                $va_campos_sistema_objeto_relacionado = $vo_campo_sistema->ler_lista(["campo_sistema_recurso_sistema_codigo" => $vn_objeto_relacionado_recurso_sistema_codigo]);

                                $vn_objeto_relacionado_codigo = "";
                                $vs_objeto_relacionado_campo_identificador = "";
                                $va_objeto_relacionado_campos_obrigatorios = array();
                                $va_valores_objeto_relacionado = array();

                                foreach ($va_campos_sistema_objeto_relacionado as $va_campo_objeto_relacionado)
                                {
                                    if ($va_campo_objeto_relacionado["campo_sistema_identificador_recurso_sistema"])
                                        $vs_objeto_relacionado_campo_identificador = $va_campo_objeto_relacionado["campo_sistema_nome"];

                                    if ($va_campo_objeto_relacionado["campo_sistema_obrigatorio"])
                                        $va_objeto_relacionado_campos_obrigatorios[] = $va_campo_objeto_relacionado;
                                }

                                if ($vs_objeto_relacionado_campo_identificador)
                                {
                                    $vb_pode_processar = true;

                                    foreach ($va_objeto_relacionado_campos_obrigatorios as $va_campo_obrigatorio)
                                    {
                                        if (isset($va_valores_importacao[$va_campo_obrigatorio["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_id"]]))
                                            $va_valores_objeto_relacionado[$va_campo_obrigatorio["campo_sistema_nome"]] = $va_valores_importacao[$va_campo_obrigatorio["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_id"]];
                                        else
                                            $vb_pode_processar = false;
                                    }

                                    if ($vb_pode_processar)
                                    {
                                        $vs_objeto_relacionado = $va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_id"];
                                        $vo_objeto_relacionado = new $vs_objeto_relacionado;

                                        $va_valores_objeto_relacionado[$vs_objeto_relacionado_campo_identificador] = $vs_valor;

                                        $va_objeto_relacionado = $vo_objeto_relacionado->ler_lista($va_valores_objeto_relacionado, "lista");

                                        if (count($va_objeto_relacionado))
                                        {
                                            // Se o registro já existe no banco de dados, recupera o valor da chave primária
                                            ////////////////////////////////////////////////////////////////////////////////
                                            
                                            $vn_objeto_relacionado_codigo = $va_objeto_relacionado[0][$vo_objeto_relacionado->chave_primaria[0]];
                                            $vb_adicionou_valor = true;
                                        }
                                        else
                                        {
                                            // Cria o registro se ele não existe no banco de dados
                                            //////////////////////////////////////////////////////////////

                                            $va_valores_objeto_relacionado["usuario_logado_codigo"] = $pn_usuario_logado_codigo;

                                            $vn_objeto_relacionado_codigo = $vo_objeto_relacionado->salvar($va_valores_objeto_relacionado);
                                            $vb_adicionou_valor = true;
                                        }

                                        if ($vn_objeto_relacionado_codigo)
                                        {
                                            $va_valores_importacao[$vs_objeto_relacionado] = $vn_objeto_relacionado_codigo;

                                            if ($vn_contador_sufixo)
                                            {
                                                if (isset($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]) && !in_array($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"], $va_campos_agrupadores))
                                                {
                                                    $va_valores_importacao[$vs_key_campo_importacao . "_" . $va_valores_importacao[$va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]]] = $vn_objeto_relacionado_codigo;
                                                }
                                                else
                                                    $va_valores_importacao[$vs_key_campo_importacao . $vs_sufixo_agrupador] = $vn_objeto_relacionado_codigo;
                                            }
                                            else
                                                $va_objetos_relacionados_codigos[] = $vn_objeto_relacionado_codigo;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($vn_contador_sufixo)
                        $vn_contador_sufixo++;
                }

                if (count($va_objetos_relacionados_codigos))
                {
                    if (isset($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]) && !in_array($va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"], $va_campos_agrupadores))
                    {
                        $va_valores_importacao[$vs_key_campo_importacao . "_" . $va_valores_importacao[$va_campo_importacao["importacao_campo_sistema_codigo"]["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]]] = implode("|", $va_objetos_relacionados_codigos);
                    }
                    else
                        $va_valores_importacao[$vs_key_campo_importacao . $vs_sufixo_agrupador] = implode("|", $va_objetos_relacionados_codigos);
                }

                if ($vb_adicionou_valor)
                {
                    unset($va_campos_importacao[$vs_key_campo_importacao]);
                    unset($va_valores_nao_processados[$vs_identificador_registro][$vs_key_campo_importacao]);
                }
                elseif ($vs_valor != "")
                    $va_valores_nao_processados[$vs_identificador_registro][$vs_key_campo_importacao] = $vs_valor;

                $vb_adicionou_valores = $vb_adicionou_valores || $vb_adicionou_valor;

                $contador_campos++;
            }
        }

        if (count($va_valores_nao_processados))
            var_dump($va_valores_nao_processados);

        $va_valores_importacao["usuario_logado_codigo"] = $pn_usuario_logado_codigo;

        return $this->salvar($va_valores_importacao, true, 1, true, "", $pb_sobrescrever);
    }

    public function inserir($pa_valores_form, $ps_id_objeto_filho = '')
    {

        $this->banco_dados = $this->get_banco();

        $vb_iniciada_transacao = $this->iniciar_transacao();

        $this->inicializar_variaveis_banco();

        if (!$this->autoincrement_codigo)
        {
            $vn_novo_codigo = $this->ler_proximo_codigo($this->tabela_banco);
            $this->va_campos[] = $this->chave_primaria["coluna_tabela"];
            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $vn_novo_codigo;
        }

        $this->montar_campos_salvamento($pa_valores_form, $ps_id_objeto_filho);

        $vn_autoincrement_codigo = $this->banco_dados->inserir($this->tabela_banco, $this->va_campos, $this->va_tipos_parametros, $this->va_parametros);

        if ($vb_iniciada_transacao)
            $this->banco_dados->finalizar_transacao();

        return empty($vn_novo_codigo) ? $vn_autoincrement_codigo : $vn_novo_codigo;
    }

    public function atualizar($pa_valores_form, $ps_id_objeto_filho = '')
    {

        $this->banco_dados = $this->get_banco();

        $vb_iniciada_transacao = $this->iniciar_transacao();

        $this->inicializar_variaveis_banco();

        $this->montar_campos_salvamento($pa_valores_form, $ps_id_objeto_filho);

        $this->va_wheres[] = $this->tabela_banco . ".codigo = (?) ";
        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pa_valores_form[$this->chave_primaria[0]];

        if (count($this->va_campos))
            $this->banco_dados->atualizar($this->tabela_banco, $this->va_campos, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);

        if ($vb_iniciada_transacao)
            $this->banco_dados->finalizar_transacao();
    }

    private function montar_campos_salvamento($pa_valores_form = array(), $ps_id_objeto_filho = '')
    {
        foreach ($this->atributos as $vs_key_atributo => $va_atributo) 
        {
            // Recupera o valor de cada atributo vindo do POST
            //////////////////////////////////////////////////

            if (isset($pa_valores_form[$vs_key_atributo])) 
            {
                // Ajustar aqui. Se o valor vier vazio, temos que transfomá-lo em null
                /////////////////////////////////////////////////////////////////////

                if (trim($pa_valores_form[$vs_key_atributo]) != "")
                    $v_valor_salvar = utils::sanitize_string($pa_valores_form[$vs_key_atributo]);
                else
                    $v_valor_salvar = NULL;

                // Tratar checkboxes, que vêm como "b"
                //////////////////////////////////////

                if ($va_atributo["tipo_dado"] == 'b')
                    $v_tipo_parametro = "i";
                else
                    $v_tipo_parametro = $va_atributo["tipo_dado"];

                if (($va_atributo["tipo_dado"] <> 'dt') && (!isset($va_atributo["serial"]) || isset($v_valor_salvar))) {
                    $this->va_campos[] = $va_atributo["coluna_tabela"];
                    $this->va_tipos_parametros[] = $v_tipo_parametro;
                    $this->va_parametros[] = $v_valor_salvar;
                } elseif ($va_atributo["tipo_dado"] == 'dt') {
                    $this->tratar_data($vs_key_atributo, $va_atributo["coluna_tabela"], $pa_valores_form);
                }
            } 
            elseif (isset($va_atributo["valor_padrao"]) && !is_array($va_atributo["valor_padrao"])) 
            {
                // Valor padrão é atribuído direto, sem ler do POST

                // Tratar checkboxes, que vêm como "b"
                if ($va_atributo["tipo_dado"] == 'b')
                    $v_tipo_parametro = "i";
                else
                    $v_tipo_parametro = $va_atributo["tipo_dado"];

                $this->va_campos[] = $va_atributo["coluna_tabela"];
                $this->va_tipos_parametros[] = $v_tipo_parametro;
                $this->va_parametros[] = $va_atributo["valor_padrao"];
            }
        }

        // Aqui vamos montar campos que não vêm diretamente do form
        // mas podem ser construídos por uma função específica

        foreach ($this->atributos as $vs_key_atributo => $va_atributo) 
        {
            if (isset($va_atributo["serial"]) && (isset($pa_valores_form[$vs_key_atributo])) && (!$pa_valores_form[$vs_key_atributo])) 
            {
                $vs_objeto_serial = "";

                if (isset($va_atributo["serial"][get_class($this)]))
                    $vs_objeto_serial = get_class($this);
                elseif ($ps_id_objeto_filho && isset($va_atributo["serial"][$ps_id_objeto_filho]))
                    $vs_objeto_serial = $ps_id_objeto_filho;

                if ($vs_objeto_serial) 
                {
                    $pa_valores_form[$vs_key_atributo] = $this->calcular_proximo_valor_serial($va_atributo, $vs_objeto_serial, $pa_valores_form);

                    $this->va_campos[] = $va_atributo["coluna_tabela"];
                    $this->va_tipos_parametros[] = $va_atributo["tipo_dado"];
                    $this->va_parametros[] = $pa_valores_form[$vs_key_atributo];
                }
            }
        }

        foreach ($this->atributos as $vs_key_atributo => $va_atributo) 
        {
            if (isset($va_atributo["processar"])) 
            {
                $vs_funcao = "";
                if (isset($va_atributo["processar"][0]))
                    $vs_funcao = $va_atributo["processar"][0];

                $va_parametros_funcao = array();
                if (isset($va_atributo["processar"][1]))
                    $va_parametros_funcao = $va_atributo["processar"][1];

                $va_valores_parametros = array();
                $vs_valor_atributo = "";

                if ($vs_funcao) 
                {
                    foreach ($va_parametros_funcao as $vs_parametro_funcao) 
                    {
                        if (isset($pa_valores_form[$vs_parametro_funcao])) 
                        {
                            $va_valores_parametros[] = trim($pa_valores_form[$vs_parametro_funcao]);
                        }
                    }

                    $vs_valor_atributo = $this->$vs_funcao($va_valores_parametros);
                }

                $this->va_campos[] = $va_atributo["coluna_tabela"];
                $this->va_tipos_parametros[] = $va_atributo["tipo_dado"];
                $this->va_parametros[] = $vs_valor_atributo;
            }
        }
    }

    private function calcular_proximo_valor_serial($pa_atributo, $ps_objeto_serial, $pa_valores_form)
    {
        $vs_valor_prefixo = "";
        $va_parametros_seriais = $pa_atributo["serial"][$ps_objeto_serial];

        foreach($va_parametros_seriais as $va_parametros_serial)
        {

            $vs_separador = "";
            if (isset($va_parametros_serial["separador"]))
                $vs_separador = $va_parametros_serial["separador"];

            if (isset($va_parametros_serial["agrupador_registros"]))
                $va_agrupador_registros = $va_parametros_serial["agrupador_registros"];

            if ((count($va_parametros_seriais) == 1) || (isset($va_agrupador_registros) && isset($pa_valores_form[$va_agrupador_registros["atributo"]]) && $pa_valores_form[$va_agrupador_registros["atributo"]]))
            {
                if (isset($va_parametros_serial["prefixo"])) 
                {
                    $va_valores_prefixo = array();

                    foreach ($va_parametros_serial["prefixo"] as $va_parte_prefixo) 
                    {
                        // Vamos ver se o prefixo é um campo recuperável
                        ////////////////////////////////////////////////

                        if (($va_parte_prefixo["tipo"] == "atributo") && isset($pa_valores_form[$va_parte_prefixo["atributo_input_filtro"]]) && ($pa_valores_form[$va_parte_prefixo["atributo_input_filtro"]]))
                        {
                            $vs_objeto_prefixo = $va_parte_prefixo["objeto_prefixo"];
                            $vo_objeto_prefixo = new $vs_objeto_prefixo("");

                            $va_objeto_prefixo = $vo_objeto_prefixo->ler_lista([$va_parte_prefixo["atributo_filtro_objeto_prefixo"] => $pa_valores_form[$va_parte_prefixo["atributo_input_filtro"]]], "lista", 1, 1);

                            if (count($va_objeto_prefixo) && (isset($va_objeto_prefixo[0][$va_parte_prefixo["atributo_prefixo"]])))
                            {
                                $va_valores_prefixo[] = $va_objeto_prefixo[0][$va_parte_prefixo["atributo_prefixo"]];
                            }
                        }
                        elseif ($va_parte_prefixo["tipo"] == "constante")
                        {
                            $vb_adicionar_parte_prefixo = false;

                            if (isset($va_parte_prefixo["condicoes"]))
                            {
                                foreach ($va_parte_prefixo["condicoes"] as $vs_atributo_condicao => $va_valores_condicao)
                                {
                                    if (isset($pa_valores_form[$vs_atributo_condicao]) && $pa_valores_form[$vs_atributo_condicao])
                                    {
                                        if (!is_array($va_valores_condicao))
                                            $va_valores_condicao = array($va_valores_condicao);

                                        $va_valores_atributo_condicao = explode("|", $pa_valores_form[$vs_atributo_condicao]);

                                        foreach ($va_valores_atributo_condicao as $vs_atributo_condicao)
                                        {
                                            if (in_array($vs_atributo_condicao, $va_valores_condicao))
                                                $vb_adicionar_parte_prefixo = true;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $vb_adicionar_parte_prefixo = true;
                            }

                            if ($vb_adicionar_parte_prefixo)
                                $va_valores_prefixo[] = $va_parte_prefixo["valor"];
                        }
                    }

                    if (count($va_valores_prefixo))
                        $vs_valor_prefixo = join($vs_separador, $va_valores_prefixo);
                }

                $va_parametros_consulta = array();

                if (isset($pa_valores_form[$this->get_chave_primaria()[0]]))
                    $va_parametros_consulta[$this->get_chave_primaria()[0]] = [$pa_valores_form[$this->get_chave_primaria()[0]], "!="];

                if (isset($va_parametros_serial["agrupador_registros"])) 
                {
                    $va_agrupador_registros = $va_parametros_serial["agrupador_registros"];

                    if (isset($pa_valores_form[$va_agrupador_registros["atributo"]]) && ($pa_valores_form[$va_agrupador_registros["atributo"]]))
                        $va_parametros_consulta[$va_agrupador_registros["filtro"]] = $pa_valores_form[$va_agrupador_registros["atributo"]];
                }

                if ($vs_valor_prefixo)
                {
                    $va_parametros_consulta[$pa_atributo[0]] = [$vs_valor_prefixo, "LIKERIGHT"];

                    if (isset($va_parametros_serial["tamanho"]))
                        $va_parametros_consulta["LENGTH:".$pa_atributo[0]] = strlen($vs_valor_prefixo) + $va_parametros_serial["tamanho"] + 1;
                }

                if (isset($va_parametros_serial["ordenador"]))
                    $vs_campo_ordenador = $va_parametros_serial["ordenador"];
                else
                    $vs_campo_ordenador = $pa_atributo[0];

                $va_identificadores = $this->ler_lista($va_parametros_consulta, $pa_atributo[0], 1, 1, $vs_campo_ordenador, "DESC");

                if (!count($va_identificadores))
                    $vs_novo_valor_serial = str_pad("1", $va_parametros_serial["tamanho"], "0", STR_PAD_LEFT);
                else 
                {
                    if (!isset($va_parametros_serial["tamanho"]))
                    {
                        $vs_novo_valor_serial = intval(substr($va_identificadores[0][$pa_atributo[0]], strlen($vs_valor_prefixo), strlen($va_identificadores[0][$pa_atributo[0]]) - strlen($vs_valor_prefixo))) + 1;
                    }
                    else
                    {
                        $vs_proximo_numero_serial = intval(substr($va_identificadores[0][$pa_atributo[0]], strlen($va_identificadores[0][$pa_atributo[0]]) - $va_parametros_serial["tamanho"], $va_parametros_serial["tamanho"])) + 1;
                        $vs_novo_valor_serial = str_pad($vs_proximo_numero_serial, $va_parametros_serial["tamanho"], "0", STR_PAD_LEFT);
                    }
                }

                $va_novo_serial = array();
                if ($vs_valor_prefixo)
                    $va_novo_serial[] = $vs_valor_prefixo;

                $va_novo_serial[] = $vs_novo_valor_serial;

                return join($vs_separador, $va_novo_serial);
            }
        }
    }

    public function slugfy($pa_parametros = array())
    {
        // Apesar de receber um array, vou admitir que só está
        // vindo uma string para ser processada

        $vs_slug = "";
        if (count($pa_parametros)) {
            $vs_slug = mb_strtolower($pa_parametros[0], 'UTF-8');

            $va_search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,ã,õ");
            $va_replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,a,o");

            $vs_slug = str_replace($va_search, $va_replace, $vs_slug);
            $vs_slug = trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $vs_slug));
            $vs_slug = preg_replace('|-+|', '-', $vs_slug);
        }

        //return substr(trim($vs_slug), 0, 30);
        return trim($vs_slug);
    }

    private function tratar_data($ps_nome_campo, $pa_atributo, $pa_valores_form)
    {
        $vo_data = new Periodo;

        // Para a data ser válida, é preciso, pelo menos, que o ano
        // inicial venha preenchido (if) ou que a data venha completa (else)
        ///////////////////////////////////////////////////////////////////

        if (isset($pa_valores_form[$ps_nome_campo . "_ano_inicial"])) 
        {
            $vo_data->set_dia_inicial($pa_valores_form[$ps_nome_campo . "_dia_inicial"]);
            $vo_data->set_mes_inicial($pa_valores_form[$ps_nome_campo . "_mes_inicial"]);
            $vo_data->set_ano_inicial($pa_valores_form[$ps_nome_campo . "_ano_inicial"]);

            if (isset($pa_valores_form[$ps_nome_campo . "_ano_final"])) 
            {
                $vo_data->set_dia_final($pa_valores_form[$ps_nome_campo . "_dia_final"]);
                $vo_data->set_mes_final($pa_valores_form[$ps_nome_campo . "_mes_final"]);
                $vo_data->set_ano_final($pa_valores_form[$ps_nome_campo . "_ano_final"]);
            }

            if (isset($pa_valores_form[$ps_nome_campo . "_presumido"]))
                $vo_data->set_presumido($pa_valores_form[$ps_nome_campo . "_presumido"]);
            else
                $vo_data->set_presumido(0);

            if (isset($pa_valores_form[$ps_nome_campo . "_sem_data"]))
                $vo_data->set_sem_data($pa_valores_form[$ps_nome_campo . "_sem_data"]);
            else
                $vo_data->set_sem_data(0);

            if (isset($pa_valores_form[$ps_nome_campo . "_complemento"]))
                $vo_data->set_complemento($pa_valores_form[$ps_nome_campo . "_complemento"]);

            if (isset($pa_valores_form[$ps_nome_campo . "_periodo"]))
                $vo_data->set_periodo_amplo($pa_valores_form[$ps_nome_campo . "_periodo"]);

            $vo_data->consolidar();
        } 
        //elseif (isset($pa_valores_form[$ps_nome_campo]))
        else 
        {
            // Aqui parece que estava prevendo o caso de o campo data na interface ser um
            // única campo de texto. Não sei se isso é muito saudável. Estou desabilitando, por enquanto
            
            if (isset($pa_valores_form[$ps_nome_campo]) && ($pa_valores_form[$ps_nome_campo] != '_data_'))
            {
                $vo_data->set_data_inicial($pa_valores_form[$ps_nome_campo]);
                $vo_data->set_data_final($pa_valores_form[$ps_nome_campo]);
            }

            if (isset($pa_valores_form[$ps_nome_campo . "_sem_data"]))
                $vo_data->set_sem_data($pa_valores_form[$ps_nome_campo . "_sem_data"]);
            else
                $vo_data->set_sem_data(0);
        }

        if ($vo_data->get_data_inicial())
            $vs_data_inicial = $vo_data->get_data_inicial();
        else
            $vs_data_inicial = NULL;

        if (isset($pa_atributo["data_inicial"]))
            $vs_campo_data_inicial = $pa_atributo["data_inicial"];
        else
            $vs_campo_data_inicial = $pa_atributo;

        $this->va_campos[] = $vs_campo_data_inicial;
        $this->va_tipos_parametros[] = "s";
        $this->va_parametros[] = $vs_data_inicial;

        if (isset($pa_atributo["data_final"])) 
        {
            if ($vo_data->get_data_final())
                $vs_data_final = $vo_data->get_data_final();
            else
                $vs_data_final = NULL;

            $this->va_campos[] = $pa_atributo["data_final"];
            $this->va_tipos_parametros[] = "s";
            $this->va_parametros[] = $vs_data_final;
        }

        if ($vo_data->get_presumido())
            $vb_presumido = $vo_data->get_presumido();
        else
            $vb_presumido = 0;

        if (isset($pa_atributo["presumido"])) 
        {
            $this->va_campos[] = $pa_atributo["presumido"];
            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $vb_presumido;
        }

        if ($vo_data->get_sem_data())
            $vb_sem_data = $vo_data->get_sem_data();
        else
            $vb_sem_data = 0;

        if (isset($pa_atributo["sem_data"])) 
        {
            $this->va_campos[] = $pa_atributo["sem_data"];
            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $vb_sem_data;
        }

        if (isset($pa_atributo["complemento"]))
        {
            if ($vo_data->get_complemento())
                $vs_complemento = $vo_data->get_complemento();
            else
                $vs_complemento = NULL;

            $this->va_campos[] = $pa_atributo["complemento"];
            $this->va_tipos_parametros[] = "s";
            $this->va_parametros[] = $vs_complemento;
        }

        if (isset($pa_atributo["periodo"]))
        {
            if ($vo_data->get_periodo_amplo())
                $vs_periodo = $vo_data->get_periodo_amplo();
            else
                $vs_periodo = NULL;

            $this->va_campos[] = $pa_atributo["periodo"];
            $this->va_tipos_parametros[] = "s";
            $this->va_parametros[] = $vs_periodo;
        }
    }

    public function listar_relacionamentos($pn_codigo, $pb_excluir_objeto = true)
    {
        $va_relacionamentos = array();
        $vo_banco = $this->get_banco();

        $va_objeto = $this->ler($pn_codigo);

        if ($this->objeto_pai) {
            $vo_objeto_pai = new $this->objeto_pai;
            $va_relacionamentos = $vo_objeto_pai->listar_relacionamentos($va_objeto[$this->campo_relacionamento_pai], $this->excluir_objeto_pai);
        }

        $this->relacionamentos = array_merge($this->relacionamentos, $this->get_relacionamentos($this->recurso_sistema_codigo));

        foreach ($this->relacionamentos as $vs_id_relacionamento => $va_relacionamento) {
            if (!isset($va_relacionamento['tipo']) || (isset($va_relacionamento['tipo']) && $va_relacionamento['tipo'] != "textual")) {
                if (!is_array($va_relacionamento["chave_exportada"]))
                    $va_chave_exportada = array($va_relacionamento["chave_exportada"]);
                else
                    $va_chave_exportada = $va_relacionamento["chave_exportada"];

                $vn_numero_registros_relacionamento = 0;

                foreach ($va_chave_exportada as $vs_chave_exportada) {
                    $va_selects = array();
                    $va_tipos_parametros_select = array();
                    $va_parametros_select = array();
                    $va_campos_select = array();
                    $va_joins_select = array();
                    $va_wheres_select = array();

                    $va_campos_select[] = " COUNT(*) as Q ";
                    $vs_tabela = $va_relacionamento["tabela_intermediaria"];
                    $chave_exportada = $va_relacionamento["chave_exportada"];

                    $va_wheres_select[] = $vs_tabela . "." . $vs_chave_exportada . " = (?) ";
                    $va_tipos_parametros_select[] = "i";
                    $va_parametros_select[] = $pn_codigo;

                    if (is_array($va_relacionamento["campos_relacionamento"])) {
                        $va_campos_relacionamento = $va_relacionamento["campos_relacionamento"];
                        $va_tipos_campos_relacionamento = $va_relacionamento["tipos_campos_relacionamento"];
                    } else {
                        $va_campos_relacionamento = array($va_relacionamento["campos_relacionamento"]);
                        $va_tipos_campos_relacionamento = array($va_relacionamento["tipos_campos_relacionamento"]);
                    }

                    $va_filtros = array();
                    if (isset($va_relacionamento["filtros"]))
                        $va_filtros = $va_relacionamento["filtros"];

                    foreach ($va_campos_relacionamento as $vs_alias => $va_campo_relacionamento) {
                        if (is_array($va_campo_relacionamento)) {
                            if (isset($va_campo_relacionamento[0])) {
                                if (!is_array($va_campo_relacionamento[0])) {
                                    // O segundo elemento do array pode especificar várias coisas
                                    /////////////////////////////////////////////////////////////

                                    if (isset($va_campo_relacionamento[1]))
                                        $va_filtros[$vs_alias] = $va_campo_relacionamento[1];
                                }
                            }
                        }
                    }

                    foreach ($va_filtros as $va_parametro_nome => $va_parametro) {
                        $va_valores_busca = array();
                        $vs_operador = "";
                        $vs_interrogacoes = " (?) ";

                        $vb_tem_valor = $this->montar_valores_busca($va_parametro, $va_valores_busca, $vs_operador, $vs_interrogacoes);

                        if ($vb_tem_valor) {
                            // Primeiro tem que ver se o parâmetro do filtro é da
                            // tabela intermediária ou da tabela do objeto relacionado
                            //////////////////////////////////////////////////////////

                            if (isset($va_campos_relacionamento[$va_parametro_nome])) {
                                $vs_tabela_filtro = $vs_tabela;

                                if (is_array($va_campos_relacionamento[$va_parametro_nome]))
                                    $vs_coluna_filtro = reset($va_campos_relacionamento[$va_parametro_nome]);
                                else
                                    $vs_coluna_filtro = $va_campos_relacionamento[$va_parametro_nome];

                                $vn_index_tipo_campo = array_search($va_parametro_nome, array_keys($va_campos_relacionamento));
                                $vs_tipo_dado_campo = $va_tipos_campos_relacionamento[$vn_index_tipo_campo];
                            } 
                            elseif (isset($va_relacionamento['objeto']))
                            {   
                                $vo_objeto_filtro = new $va_relacionamento['objeto']('');

                                if (isset($vo_objeto_filtro->atributos[$va_parametro_nome]) || isset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]))
                                {
                                    $vs_chave_importada = reset($va_campos_relacionamento);
                                    if (is_array($vs_chave_importada))
                                        $vs_chave_importada = reset($vs_chave_importada);

                                    if (!in_array($vo_objeto_filtro->tabela_banco, array_keys($va_joins_select)))
                                        $va_joins_select[$vo_objeto_filtro->tabela_banco] = " JOIN " . $vo_objeto_filtro->tabela_banco . " ON " . $vs_tabela . "." . $vs_chave_importada . " = " . $vo_objeto_filtro->tabela_banco . "." . $vo_objeto_filtro->chave_primaria["coluna_tabela"];

                                    if (isset($vo_objeto_filtro->atributos[$va_parametro_nome]))
                                    {
                                        $vs_tabela_filtro = $vo_objeto_filtro->tabela_banco;
                                        $vs_coluna_filtro = $vo_objeto_filtro->atributos[$va_parametro_nome]["coluna_tabela"];
                                        $vs_tipo_dado_campo = $vo_objeto_filtro->atributos[$va_parametro_nome]["tipo_dado"];
                                    }
                                    elseif (isset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]))
                                    {
                                        if (!in_array($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"], array_keys($va_joins_select)))
                                            $va_joins_select[$vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"]] = " JOIN " . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"] . " ON " . $vo_objeto_filtro->tabela_banco . "." . $vo_objeto_filtro->chave_primaria["coluna_tabela"] . " = " . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"] . "." . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["chave_exportada"];

                                        $vs_tabela_filtro = $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"];
                                        $vs_coluna_filtro = reset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["campos_relacionamento"]);
                                        $vs_tipo_dado_campo = reset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tipos_campos_relacionamento"]);
                                    }
                                }
                            }

                            if ($vs_operador == "NOT")
                                $va_wheres_select[] = $vs_operador . " " . $vs_tabela_filtro . "." . $vs_coluna_filtro . " <=> " . $vs_interrogacoes;
                            else
                                $va_wheres_select[] = $vs_tabela_filtro . "." . $vs_coluna_filtro . " " . $vs_operador . $vs_interrogacoes;

                            foreach ($va_valores_busca as $va_valor_busca) {
                                $va_parametros_select[] = $va_valor_busca;
                                $va_tipos_parametros_select[] = $vs_tipo_dado_campo;
                            }
                        }
                    }

                    $va_selects[] = [
                        "tabela" => $vs_tabela,
                        "campos" => $va_campos_select,
                        "joins" => $va_joins_select,
                        "wheres" => $va_wheres_select,
                    ];

                    $vb_registro_filho = false;
                    $vb_pode_excluir = true;

                    if (count($this->registros_filhos) && isset($va_relacionamento['objeto'])) 
                    {
                        if (in_array($va_relacionamento['objeto'], array_keys($this->registros_filhos))) 
                        {
                            $vb_registro_filho = true;

                            if (isset($this->registros_filhos[$va_relacionamento['objeto']]["pode_excluir_pai"]) && !$this->registros_filhos[$va_relacionamento['objeto']]["pode_excluir_pai"] && $pb_excluir_objeto)
                                $vb_pode_excluir = false;
                        }
                    }

                    if (isset($va_relacionamento["impede_exclusao"]))
                        $vb_pode_excluir = $vb_pode_excluir && !$va_relacionamento["impede_exclusao"];

                    $vn_numero_registros_relacionamento = $vn_numero_registros_relacionamento + $vo_banco->consultar($va_selects, $va_tipos_parametros_select, $va_parametros_select)[0]["Q"];
                }

                if ($vn_numero_registros_relacionamento) {
                    if (isset($va_relacionamento['alias']))
                        $vs_alias_relacionamento = $va_relacionamento['alias'];
                    elseif (isset($va_relacionamento['objeto']))
                        $vs_alias_relacionamento = $va_relacionamento['objeto'];
                    else
                        $vs_alias_relacionamento = $vs_id_relacionamento;

                    $va_relacionamentos[$vs_alias_relacionamento] = [$vn_numero_registros_relacionamento, $vb_registro_filho, $vb_pode_excluir];
                }
            }
        }

        return $va_relacionamentos;
    }

    public function excluir($pn_codigo, $pb_manter_objetos_filhos = false, $pa_registro_superior = array())
    {
        $vb_cancelar_exclusao = false;

        $this->banco_dados = $this->get_banco();

        $vb_iniciada_transacao = $this->iniciar_transacao();


        if ($this->objeto_pai && $this->excluir_objeto_pai) {
            $va_objeto = $this->ler($pn_codigo);
            $vn_objeto_pai_codigo = $va_objeto[$this->campo_relacionamento_pai];
        }

        // Tem que apagar primeiro os registros filhos
        //////////////////////////////////////////////

        foreach ($this->registros_filhos as $vs_id_objeto_filho => $va_objeto_filho) 
        {
            if (isset($va_objeto_filho["atributo_relacionamento"]) && ($va_objeto_filho["atributo_relacionamento"])) 
            {
                $vo_objeto_filho = new $vs_id_objeto_filho($vs_id_objeto_filho);
                $va_parametros_selecao = array();
                $vs_atributo_relacionamento = $va_objeto_filho["atributo_relacionamento"];

                if (empty($va_parametros_selecao))
                {
                    $va_parametros_selecao[$vs_atributo_relacionamento] = $pn_codigo;
                }

                $va_registros_filhos = $vo_objeto_filho->ler_lista($va_parametros_selecao, "lista");

                if (count($va_registros_filhos) && isset($va_objeto_filho["pode_excluir_pai"]) && !$va_objeto_filho["pode_excluir_pai"])
                {
                    $vb_cancelar_exclusao = true;
                    break;
                }

                foreach ($va_registros_filhos as $va_registro_filho)
                {
                    $vo_objeto_filho->excluir($va_registro_filho[$vo_objeto_filho->get_chave_primaria()[0]], false, [get_class($this), $pn_codigo]);
                }
            } else {
                $vb_cancelar_exclusao = true;
                break;
            }
        }

        if ($vb_cancelar_exclusao)
            return 0;

        $this->excluir_representantes_digitais($pn_codigo);

        $this->excluir_relacionamentos_selecoes($pn_codigo);

        foreach ($this->relacionamentos as $vs_id_relacionamento => $va_relacionamento) {
            $vs_campo_base_relacionamento = $vs_id_relacionamento;

            if (!isset($va_relacionamento["tipo"]) || ($va_relacionamento["tipo"] != "1n"))
                $this->excluir_relacionamentos($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo);
            else {
                // Se for do tipo 1xn, tem que atualizar o campo da chave exportada
                $this->atualizar_relacionamento($va_relacionamento, $pn_codigo, null);
            }
        }

        $this->excluir_etapas_fluxo($pn_codigo);

        $this->excluir_logs($pn_codigo);

        // Aqui uma necessidade
        // Se o objeto tem pai e usa a mesma tabela do pai, então só preciso excluir o pai
        ////////////////////////////////////////////////////////////////////////////////////

        if ($this->chave_primaria[0] != $this->campo_relacionamento_pai) {
            $this->inicializar_variaveis_banco();

            $va_codigos_excluir = explode(",", $pn_codigo);

            foreach ($va_codigos_excluir as $vn_codigo_excluir) {
                $va_interrogacoes[] = "?";
                $this->va_tipos_parametros[] = "i";
                $this->va_parametros[] = $vn_codigo_excluir;
            }

            $vs_interrogacoes = "(" . join(",", $va_interrogacoes) . ")";

            $this->va_wheres[] = $this->tabela_banco . "." . $this->chave_primaria["coluna_tabela"] . " IN " . $vs_interrogacoes;

            $this->banco_dados->excluir($this->tabela_banco, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);
        }

        // Caso no objeto esteja configurado para excluir o objeto pai
        // (O default é false)
        //////////////////////////////////////////////////////////////

        if ($this->objeto_pai && $this->excluir_objeto_pai) {
            // Em $pa_registro_superior, se estiver preenchido, está
            // armazenado quem mandou excluir o registro corrente
            // Se quem mandou excluir o registro corrente for o próprio
            // objeto pai dele, o objeto pai já está sendo excluído, não
            // precisa mandar excluir novamente

            $vb_excluir_objeto_pai = true;
            if (count($pa_registro_superior)) {
                if (($pa_registro_superior[0] == $this->objeto_pai) && ($pa_registro_superior[1] == $vn_objeto_pai_codigo))
                    $vb_excluir_objeto_pai = false;
            }

            if ($vb_excluir_objeto_pai) {
                $vo_objeto_pai = new $this->objeto_pai;
                $vo_objeto_pai->excluir($vn_objeto_pai_codigo);
            }
        }

        if ($vb_iniciada_transacao)
            $this->banco_dados->finalizar_transacao();
    }

    public function substituir($pn_codigo_origem, $pn_codigo_destino)
    {
        if ($pn_codigo_origem != $pn_codigo_destino) 
        {
            $this->banco_dados = $this->get_banco();

            $vb_iniciada_transacao = $this->iniciar_transacao();

            // Primeiro tem que realizar substituições nos registros filhos
            ///////////////////////////////////////////////////////////////
            
            foreach($this->registros_filhos as $vs_id_objeto_filho => $va_objeto_filho)
            {
                if (isset($va_objeto_filho["atributo_relacionamento"]) && ($va_objeto_filho["atributo_relacionamento"]))
                {
                    $vo_objeto_filho = new $vs_id_objeto_filho($vs_id_objeto_filho);

                    // Só vamos realizar a substituição conjunta de filhos, se o filho (objeto dependente) realmente
                    // for filho no sentido de herança (compartilham os mesmos dados)

                    if ($vo_objeto_filho->objeto_pai == get_class($this))
                    {
                        $va_parametros_selecao = array();
                        $va_parametros_selecao[$va_objeto_filho["atributo_relacionamento"]] = $pn_codigo_origem;

                        $va_registros_filhos_origem = $vo_objeto_filho->ler_lista($va_parametros_selecao, "lista");

                        $va_parametros_selecao = array();
                        $va_parametros_selecao[$va_objeto_filho["atributo_relacionamento"]] = $pn_codigo_destino;

                        $va_registros_filhos_destino = $vo_objeto_filho->ler_lista($va_parametros_selecao, "lista");

                        if (count($va_registros_filhos_origem) && count($va_registros_filhos_destino))
                        {
                            foreach($va_registros_filhos_origem as $va_registro_filho_origem)
                            {
                                $vo_objeto_filho->substituir($va_registro_filho_origem[$vo_objeto_filho->get_chave_primaria()[0]], $va_registros_filhos_destino[0][$vo_objeto_filho->get_chave_primaria()[0]]);
                            }
                        }
                    }
                }
            }

            foreach ($this->relacionamentos as $vs_id_relacionamento => $va_relacionamento) {
                if (!isset($va_relacionamento["tipo"]) || ($va_relacionamento["tipo"] != "textual"))
                    $this->atualizar_relacionamento($va_relacionamento, $pn_codigo_origem, $pn_codigo_destino);
            }

            // Exclui o registro substituído
            ////////////////////////////////

            $this->excluir($pn_codigo_origem);

            if ($vb_iniciada_transacao)
                $this->banco_dados->finalizar_transacao();
        }
    }

    public function atualizar_relacionamento($pa_relacionamento, $pn_codigo_origem, $pn_codigo_destino)
    {
        $vs_tabela_relacionamento = $pa_relacionamento["tabela_intermediaria"];

        // Vamos considerar a possibilidade de a exportação da chave acontecer
        // para duas colunas tabela intermediária (auto-relacionamento nxn)
        //////////////////////////////////////////////////////////////////////

        if (!is_array($pa_relacionamento["chave_exportada"]))
            $va_chave_exportada_temp = array($pa_relacionamento["chave_exportada"]);
        else
            $va_chave_exportada_temp = $pa_relacionamento["chave_exportada"];

        $this->banco_dados = $this->get_banco();

        $vo_conexao = $this->banco_dados->get_conexao_banco();

        foreach($va_chave_exportada_temp as $vs_chave_exportada)
        {
            // Atualização simplificada: considera sempre que o par das chaves primárias dos objetos
            // relacionados não podem se repetir, ignorando eventuais campos complementares da tabela
            // intermediária
            //////////////////////////////////////////////////////////////////

            $vs_campo_relacionamento = reset($pa_relacionamento["campos_relacionamento"]);

            while (is_array($vs_campo_relacionamento))
            {
                $vs_campo_relacionamento = $vs_campo_relacionamento[0];
            }

            if ($this->tabela_banco == $pa_relacionamento["tabela_intermediaria"])
            {
                $vs_select = " UPDATE " . $vs_tabela_relacionamento . " as t_origem
                    set t_origem." . $vs_chave_exportada . " = NULL
                    where t_origem." . $vs_chave_exportada . " = ?
                    and t_origem." . $vs_campo_relacionamento . " = ? ";

                $vo_conexao->executar($vs_select, ["i", "i"], [$pn_codigo_origem, $pn_codigo_destino]);
            }

            $vs_select = " UPDATE " . $vs_tabela_relacionamento . " as t_origem
                left join " . $vs_tabela_relacionamento . " as t_destino on t_origem." . $vs_campo_relacionamento . " = t_destino." . $vs_campo_relacionamento . " AND t_destino." . $vs_chave_exportada . " = ?
                set t_origem." . $vs_chave_exportada . " = ?
                where t_origem." . $vs_chave_exportada . " = ? AND t_destino." . $vs_chave_exportada . " is NULL ";

            $vo_conexao->executar($vs_select, ["i", "i", "i"], [$pn_codigo_destino, $pn_codigo_destino, $pn_codigo_origem]);
        }
    }

    public function salvar_relacionamentos($pa_valores_form, $pn_codigo, $pn_idioma_codigo = 1, $pb_insercao = false, $pb_sobrescrever = true)
    {
        foreach ($this->relacionamentos as $vs_id_relacionamento => $va_relacionamento) 
        {
            $vb_relacionamento_1n = false;
            $vb_relacionamento_textual = false;
            $vb_relacionamento_1x1 = false;

            if (isset($va_relacionamento['tipo'])) 
            {
                if ($va_relacionamento['tipo'] == "1n")
                    $vb_relacionamento_1n = true;

                if ($va_relacionamento['tipo'] == "textual")
                    $vb_relacionamento_textual = true;

                if ($va_relacionamento['tipo'] == "1x1")
                    $vb_relacionamento_1x1 = true;
            }

            $vs_campo_base_relacionamento = $vs_id_relacionamento;

            // Só exclui o relacionamento se o campo vier passado (vazio ou não)
            // e o relacionamento não for 1xn
            ////////////////////////////////////////////////////////////////////

            if ((isset($pa_valores_form[$vs_id_relacionamento]) || isset($pa_valores_form["numero_" . $vs_id_relacionamento])) && !$vb_relacionamento_1n && !$vb_relacionamento_1x1)
            {
                $vs_objeto_relacionamento = "";
                if (isset($va_relacionamento["objeto"]))
                    $vs_objeto_relacionamento = $va_relacionamento["objeto"];

                $va_filtros = array();
                if (isset($va_relacionamento["filtros"]))
                    $va_filtros = $va_relacionamento["filtros"];

                if ($pb_sobrescrever)
                    $this->excluir_relacionamentos($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, null, $va_relacionamento["campos_relacionamento"], $va_relacionamento["tipos_campos_relacionamento"], $vs_objeto_relacionamento, $va_filtros);
            }
            elseif ((isset($pa_valores_form[$vs_id_relacionamento]) || isset($pa_valores_form["numero_" . $vs_id_relacionamento])) && ($vb_relacionamento_1n)) {
                // Se o campo vier passado (vazio ou não)
                // e o relacionamento for 1xn, tem que atualizar
                // o campo (chave estrangeira) da tabela para NULL, em vez de excluir o registro

                $this->inicializar_variaveis_banco();

                $vs_tabela_relacionamento = $va_relacionamento["tabela_intermediaria"];
                $vs_campo_atualizar = $va_relacionamento["chave_exportada"];

                $this->va_campos[] = $vs_tabela_relacionamento . "." . $vs_campo_atualizar;
                $this->va_tipos_parametros[] = "i";
                $this->va_parametros[] = NULL;

                $this->va_tipos_parametros[] = "i";
                $this->va_parametros[] = $pn_codigo;

                $this->va_wheres[] = $vs_tabela_relacionamento . "." . $vs_campo_atualizar . " = ? ";

                $this->banco_dados->atualizar($vs_tabela_relacionamento, $this->va_campos, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);
            }

            if ($vb_relacionamento_1n && isset($pa_valores_form[$vs_id_relacionamento])) 
            {
                // Aqui trata os relacionamentos 1xn
                // Se for do tipo 1xn, tem que atualizar o campo da chave exportada

                $this->atualizar_relacionamento($va_relacionamento, $pn_codigo, null);

                if (trim($pa_valores_form[$vs_id_relacionamento])) 
                {
                    $this->inicializar_variaveis_banco();

                    $vs_tabela_relacionamento = $va_relacionamento["tabela_intermediaria"];
                    $vs_campo_atualizar = $va_relacionamento["chave_exportada"];

                    $this->va_campos[] = $vs_tabela_relacionamento . "." . $vs_campo_atualizar;
                    $this->va_tipos_parametros[] = "i";
                    $this->va_parametros[] = $pn_codigo;

                    $va_valores = explode("|", $pa_valores_form[$vs_id_relacionamento]);
                    $va_interrogracoes = array();

                    foreach ($va_valores as $vs_valor) 
                    {
                        $va_interrogracoes[] = "?";
                        $this->va_tipos_parametros[] = $va_relacionamento["tipos_campos_relacionamento"][0];
                        $this->va_parametros[] = $vs_valor;
                    }

                    $vs_interrogacoes = "(" . join(",", $va_interrogracoes) . ")";

                    $this->va_wheres[] = $vs_tabela_relacionamento . "." . ($va_relacionamento["campos_relacionamento"][$vs_id_relacionamento][0][0]) . " IN " . $vs_interrogacoes;

                    $this->banco_dados->atualizar($vs_tabela_relacionamento, $this->va_campos, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);
                }
            } 
            elseif (isset($pa_valores_form["numero_" . $vs_id_relacionamento]))
            {
                // Aqui trata campos múltiplos não autocomplete
                ///////////////////////////////////////////////

                $contador = 1;
                $vn_numero_valores = $pa_valores_form["numero_" . $vs_id_relacionamento];
                
                while ($contador <= $vn_numero_valores) 
                {
                    $va_valores = array();
                    $vb_tem_valores_preenchidos = false;

                    if (is_array($va_relacionamento[0])) 
                    {
                        $contador_tipos_dados = 0;
                        $va_campos_relacionamento = $va_relacionamento["campos_relacionamento"];
                        $va_tipos_campos_relacionamento = $va_relacionamento["tipos_campos_relacionamento"];

                        foreach ($va_relacionamento[0] as $vs_campo_relacionamento) 
                        {
                            if ($va_relacionamento["tipos_campos_relacionamento"][$contador_tipos_dados] == "dt") 
                            {
                                if (isset($pa_valores_form[$vs_campo_relacionamento . "_ano_inicial" . "_" . $contador]))
                                {
                                    // Se o campo é passado via POST, então tem que entrar neste IF, mas só adiciona para salvar
                                    // se o valor for diferente de vazio

                                    if ($pa_valores_form[$vs_campo_relacionamento . "_ano_inicial" . "_" . $contador])
                                    {
                                        $va_valores[$vs_campo_relacionamento . "_dia_inicial"] = $pa_valores_form[$vs_campo_relacionamento . "_dia_inicial" . "_" . $contador];
                                        $va_valores[$vs_campo_relacionamento . "_mes_inicial"] = $pa_valores_form[$vs_campo_relacionamento . "_mes_inicial" . "_" . $contador];
                                        $va_valores[$vs_campo_relacionamento . "_ano_inicial"] = $pa_valores_form[$vs_campo_relacionamento . "_ano_inicial" . "_" . $contador];

                                        $vb_tem_valores_preenchidos = true;
                                    }

                                    if (isset($pa_valores_form[$vs_campo_relacionamento . "_ano_final" . "_" . $contador]) && $pa_valores_form[$vs_campo_relacionamento . "_ano_final" . "_" . $contador])
                                    {
                                        $va_valores[$vs_campo_relacionamento . "_dia_final"] = $pa_valores_form[$vs_campo_relacionamento . "_dia_final" . "_" . $contador];
                                        $va_valores[$vs_campo_relacionamento . "_mes_final"] = $pa_valores_form[$vs_campo_relacionamento . "_mes_final" . "_" . $contador];
                                        $va_valores[$vs_campo_relacionamento . "_ano_final"] = $pa_valores_form[$vs_campo_relacionamento . "_ano_final" . "_" . $contador];
                                    }
                                }
                                elseif (isset($pa_valores_form[$vs_campo_relacionamento . "_" . $contador]) && ($pa_valores_form[$vs_campo_relacionamento . "_" . $contador] != "_data_"))
                                {
                                    $va_valores[$vs_campo_relacionamento] = utils::sanitize_string($pa_valores_form[$vs_campo_relacionamento . "_" . $contador]);

                                    $vb_tem_valores_preenchidos = true;
                                }

                                if (isset($pa_valores_form[$vs_campo_relacionamento . "_data_presumida" . "_" . $contador]))
                                    $va_valores[$vs_campo_relacionamento . "_data_presumida"] = $pa_valores_form[$vs_campo_relacionamento . "_data_presumida" . "_" . $contador];

                                if (isset($pa_valores_form[$vs_campo_relacionamento . "_sem_data" . "_" . $contador]) && $pa_valores_form[$vs_campo_relacionamento . "_sem_data" . "_" . $contador] != "")
                                {
                                    $va_valores[$vs_campo_relacionamento . "_sem_data"] = $pa_valores_form[$vs_campo_relacionamento . "_sem_data" . "_" . $contador];
                                    $vb_tem_valores_preenchidos = true;
                                }

                                if (isset($pa_valores_form[$vs_campo_relacionamento . "_complemento" . "_" . $contador]) && $pa_valores_form[$vs_campo_relacionamento . "_complemento" . "_" . $contador] != "")
                                {
                                    $va_valores[$vs_campo_relacionamento . "_complemento"] = $pa_valores_form[$vs_campo_relacionamento . "_complemento" . "_" . $contador];
                                    $vb_tem_valores_preenchidos = true;
                                }
                            }
                            elseif (isset($pa_valores_form[$vs_campo_relacionamento . "_" . $contador]) && ($pa_valores_form[$vs_campo_relacionamento . "_" . $contador] != "")) 
                            {
                                $va_valores[$vs_campo_relacionamento] = utils::sanitize_string($pa_valores_form[$vs_campo_relacionamento . "_" . $contador]);
                                $vb_tem_valores_preenchidos = true;
                            } 
                            elseif (isset($pa_valores_form[$vs_campo_relacionamento . "_" . $contador]) && (trim($pa_valores_form[$vs_campo_relacionamento . "_" . $contador]) == ""))
                            {
                                $va_valores[$vs_campo_relacionamento] = NULL;
                            }
                            elseif (isset($va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento]["valor_sequencial"]))
                            {
                                $vn_posicao = array_search($vs_campo_base_relacionamento . '_' . $contador, array_keys($pa_valores_form));
                                $vn_primeiro_campo = array_search($vs_campo_base_relacionamento . '_' . 1, array_keys($pa_valores_form));
                                $vn_sequencia = ($vn_posicao - $vn_primeiro_campo) + 2;

                                $va_valores[$vs_campo_relacionamento] = $vn_sequencia;
                            }

                            else
                            {
                                unset($va_tipos_campos_relacionamento[array_search($vs_campo_relacionamento, array_keys($va_campos_relacionamento))]);
                                $va_tipos_campos_relacionamento = array_values($va_tipos_campos_relacionamento);
                                unset($va_campos_relacionamento[$vs_campo_relacionamento]);                                
                            }

                            $contador_tipos_dados++;
                        }

                        if (!isset($va_relacionamento["tem_idioma"]))
                            $vn_idioma_codigo = null;
                        else
                            $vn_idioma_codigo = $pn_idioma_codigo;

                        if ($vb_tem_valores_preenchidos || $vb_relacionamento_1x1)
                        {
                            $vb_atualizar = false;

                            if ($vb_relacionamento_1x1)
                                $vb_atualizar = $this->verificar_registro_existe($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo);

                            $this->inserir_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_campos_relacionamento, $va_tipos_campos_relacionamento, $va_valores, $vn_idioma_codigo, $vb_atualizar);
                        }

                        $contador++;
                    }
                }
            }
            elseif (isset($pa_valores_form[$vs_id_relacionamento])) 
            {
                // Aqui, trata campos múltiplos autocomplete
                ////////////////////////////////////////////

                if (trim($pa_valores_form[$vs_id_relacionamento]) != "") 
                {
                    $va_valores_base = explode("|", $pa_valores_form[$vs_id_relacionamento]);
                    $va_valores_base_complementar = $va_valores_base;

                    // Caso exista um campo do tipo sequencial, precisamos de uma variável para incrementar o valor
                    ///////////////////////////////////////////////////////////////////////////////////////////////

                    $contador_valor_sequencial = 1;

                    foreach ($va_valores_base as $v_valor_base) 
                    {
                        // Para o caso específico (e raro) de o relacionamento com o mesmo objeto
                        // ter sido adicionado mais de uma vez com o sufixo "_new"
                        // (empréstimos na ficha de consulente, por exemplo)

                        $vn_valor_base_tratado = $v_valor_base;
                        if (strpos($v_valor_base, "_new") !== FALSE)
                            $vn_valor_base_tratado = substr($v_valor_base, 0, strlen($v_valor_base) - 4);
                        
                        elseif ($v_valor_base == "0")
                        {
                            // Caso em que o campo de relacionamento permite entrada de relacionamentos nulos (valor desconhecido)
                            $vn_valor_base_tratado = NULL;
                        }

                        // Se for permitido adicionar o mesmo valor mais de uma vez,
                        // os códigos vêm com um numerador separado por "_", que deve ser descartado
                        ////////////////////////////////////////////////////////////////////////////
                        
                        if ($v_valor_base && strpos($vn_valor_base_tratado, "_"))
                        {
                            $vn_valor_base_tratado = explode("_", $vn_valor_base_tratado)[0];
                        }

                        /////////////////////////////////////////////////////////////////////////

                        $va_valores = array();
                        $va_valores[$vs_id_relacionamento] = $vn_valor_base_tratado;

                        array_shift($va_valores_base_complementar);

                        // $va_relacionamento[0] nos diz quantos campos a tabela de relacionamento indexa além da chave exportada
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////

                        if (is_array($va_relacionamento[0])) 
                        {
                            $contador = 0;
                            foreach ($va_relacionamento[0] as $vs_campo_relacionamento) 
                            {
                                // $vs_valor_relacionamento vai armazenar algum valor padrao que
                                // vem na própria especificação do campo no relacionameto

                                $vs_valor_relacionamento = "";
                                if (is_array($va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento])) {
                                    if (isset($va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento][1]) && (!is_array($va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento][1])))
                                        $vs_valor_relacionamento = $va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento][1];
                                }

                                if ($contador > 0) {
                                    if ($va_relacionamento["tipos_campos_relacionamento"][$contador] == "dt") 
                                    {
                                        if (isset($pa_valores_form[$vs_campo_relacionamento . "_ano_inicial" . "_" . $v_valor_base])) 
                                        {
                                            $va_valores[$vs_campo_relacionamento . "_dia_inicial"] = $pa_valores_form[$vs_campo_relacionamento . "_dia_inicial" . "_" . $v_valor_base];
                                            $va_valores[$vs_campo_relacionamento . "_mes_inicial"] = $pa_valores_form[$vs_campo_relacionamento . "_mes_inicial" . "_" . $v_valor_base];
                                            $va_valores[$vs_campo_relacionamento . "_ano_inicial"] = $pa_valores_form[$vs_campo_relacionamento . "_ano_inicial" . "_" . $v_valor_base];

                                            if (isset($pa_valores_form[$vs_campo_relacionamento . "_ano_final" . "_" . $v_valor_base])) {
                                                $va_valores[$vs_campo_relacionamento . "_dia_final"] = $pa_valores_form[$vs_campo_relacionamento . "_dia_final" . "_" . $v_valor_base];
                                                $va_valores[$vs_campo_relacionamento . "_mes_final"] = $pa_valores_form[$vs_campo_relacionamento . "_mes_final" . "_" . $v_valor_base];
                                                $va_valores[$vs_campo_relacionamento . "_ano_final"] = $pa_valores_form[$vs_campo_relacionamento . "_ano_final" . "_" . $v_valor_base];
                                            }

                                            if (isset($pa_valores_form[$vs_campo_relacionamento . "_data_presumida" . "_" . $v_valor_base]))
                                                $va_valores[$vs_campo_relacionamento . "_data_presumida"] = $pa_valores_form[$vs_campo_relacionamento . "_data_presumida" . "_" . $v_valor_base];

                                            if (isset($pa_valores_form[$vs_campo_relacionamento . "_sem_data" . "_" . $v_valor_base]))
                                                $va_valores[$vs_campo_relacionamento . "_sem_data"] = $pa_valores_form[$vs_campo_relacionamento . "_sem_data" . "_" . $v_valor_base];

                                            if (isset($pa_valores_form[$vs_campo_relacionamento . "_complemento" . "_" . $v_valor_base]))
                                                $va_valores[$vs_campo_relacionamento . "_complemento"] = $pa_valores_form[$vs_campo_relacionamento . "_complemento" . "_" . $v_valor_base];
                                        } 
                                        elseif (isset($pa_valores_form[$vs_campo_relacionamento . "_" . $v_valor_base]))
                                            $va_valores[$vs_campo_relacionamento] = utils::sanitize_string($pa_valores_form[$vs_campo_relacionamento . "_" . $v_valor_base]);
                                    } 
                                    elseif (isset($va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento]["valor_sequencial"]))
                                        $va_valores[$vs_campo_relacionamento] = $contador_valor_sequencial;

                                    elseif (isset($pa_valores_form[$vs_campo_relacionamento . "_" . $v_valor_base]) && ($pa_valores_form[$vs_campo_relacionamento . "_" . $v_valor_base] != ""))
                                        $va_valores[$vs_campo_relacionamento] = utils::sanitize_string($pa_valores_form[$vs_campo_relacionamento . "_" . $v_valor_base]);

                                    elseif ($vs_valor_relacionamento != "")
                                        $va_valores[$vs_campo_relacionamento] = utils::sanitize_string($vs_valor_relacionamento);

                                    else
                                        $va_valores[$vs_campo_relacionamento] = NULL;
                                }

                                $contador++;
                            }
                        }

                        $this->inserir_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_relacionamento["campos_relacionamento"], $va_relacionamento["tipos_campos_relacionamento"], $va_valores);

                        // Se é um auto relacionamento e deve-se criar
                        // relacionamentos entre todos os registros
                        ///////////////////////////////////////////

                        if (isset($va_relacionamento["auto"]) && $va_relacionamento["auto"]) {
                            foreach ($va_valores_base_complementar as $vn_valor_base_complementar) {
                                $va_valores[$vs_id_relacionamento] = $vn_valor_base_complementar;
                                $this->inserir_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $v_valor_base, $va_relacionamento["campos_relacionamento"], $va_relacionamento["tipos_campos_relacionamento"], $va_valores);
                            }
                        }

                        $contador_valor_sequencial++;
                    }
                }
            } elseif ($vb_relacionamento_textual) {
                // Caso sejam dados textuais de um objeto que têm de ser
                // armazenados com a informação do idioma de catalogação
                ////////////////////////////////////////////////////////

                $va_valores = array();
                $va_campos = array();
                $va_tipos_campos = array();

                $va_keys_valores_form = array_keys($pa_valores_form);

                if (!is_array($va_relacionamento[0]))
                    $va_relacionamento[0] = array($va_relacionamento[0]);

                $contador = 0;
                foreach ($va_relacionamento[0] as $vs_campo_relacionamento) {
                    foreach ($va_keys_valores_form as $vs_key_valor_form) {
                        $va_partes_key_valor_form = explode("_0_", $vs_key_valor_form);

                        if (count($va_partes_key_valor_form) && ($va_partes_key_valor_form[0] == $vs_id_relacionamento) && ($va_partes_key_valor_form[1] == $vs_campo_relacionamento)) {
                            $va_campos[$vs_campo_relacionamento] = $va_relacionamento["campos_relacionamento"][$vs_campo_relacionamento];
                            $va_tipos_campos[] = $va_relacionamento["tipos_campos_relacionamento"][$contador];

                            if ($pa_valores_form[$vs_key_valor_form] != "")
                            {
                                $va_valores[$vs_campo_relacionamento] = utils::sanitize_string($pa_valores_form[$vs_key_valor_form]);
                            }
                            else
                            {
                                $va_valores[$vs_campo_relacionamento] = NULL;
                            }
                        }
                    }

                    $contador++;
                }

                if ($pb_insercao)
                    $this->inserir_relacionamento($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_campos, $va_tipos_campos, $va_valores, $pn_idioma_codigo);
                elseif (count($va_valores))
                    $this->atualizar_dados_textuais($va_relacionamento["tabela_intermediaria"], $va_relacionamento["chave_exportada"], $pn_codigo, $va_campos, $va_tipos_campos, $va_valores, $pn_idioma_codigo);
            }
        }
    }

    public function atualizar_dados_textuais($ps_tabela, $ps_chave_primaria, $pn_codigo, $pa_campos, $pa_tipos_campos, $pa_valores, $pn_idioma_codigo = null)
    {

        $this->banco_dados = $this->get_banco();

        $this->inicializar_variaveis_banco();

        if (!is_array($pa_campos)) {
            $va_campos_temp[$pa_campos] = $pa_campos;
            $va_tipos_campos_temp[] = $pa_tipos_campos;
        } else {
            $va_campos_temp = $pa_campos;
            $va_tipos_campos_temp = $pa_tipos_campos;
        }

        $contador = 0;
        foreach ($va_campos_temp as $vs_key_campo => $vs_campo) {
            $vs_tipo_campo = $va_tipos_campos_temp[$contador];
            $vs_valor_campo = $pa_valores[$vs_key_campo];

            $this->va_campos[] = $vs_campo;
            $this->va_tipos_parametros[] = $vs_tipo_campo;
            $this->va_parametros[] = $vs_valor_campo;
        }

        $this->va_wheres[] = $ps_tabela . "." . $ps_chave_primaria . " = (?) ";
        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pn_codigo;

        $this->va_wheres[] = $ps_tabela . ".idioma_codigo = (?) ";
        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pn_idioma_codigo;

        $this->banco_dados->atualizar($ps_tabela, $this->va_campos, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);
    }

    public function excluir_relacionamentos($ps_tabela, $pa_chave_primaria, $pn_codigo, $pn_idioma_codigo = null, $pa_campos_relacionamento = array(), $pa_tipos_campos_relacionamento = array(), $ps_objeto_relacionamento = "", $pa_filtros = array())
    {

        $this->banco_dados = $this->get_banco();
        $vb_iniciada_transacao = $this->iniciar_transacao();

        if (!is_array($pa_chave_primaria))
            $va_chaves[] = $pa_chave_primaria;
        else
            $va_chaves = $pa_chave_primaria;

        if (is_array($pa_campos_relacionamento)) {
            $va_campos_relacionamento_temp = $pa_campos_relacionamento;
            $va_tipos_campos_relacionamento_temp = $pa_tipos_campos_relacionamento;
        } else {
            $va_campos_relacionamento_temp[] = $pa_campos_relacionamento;
            $va_tipos_campos_relacionamento_temp[] = $pa_tipos_campos_relacionamento;
        }

        foreach ($va_campos_relacionamento_temp as $vs_alias => $va_campo_relacionamento) {
            if (is_array($va_campo_relacionamento)) {
                if (isset($va_campo_relacionamento[0])) {
                    if (!is_array($va_campo_relacionamento[0])) {
                        // O segundo elemento do array pode especificar várias coisas
                        if (isset($va_campo_relacionamento[1]))
                            $pa_filtros[$vs_alias] = $va_campo_relacionamento[1];
                    }
                }
            }
        }

        foreach ($va_chaves as $ps_chave_primaria) 
        {
            $va_tipos_parametros = array();
            $va_parametros = array();
            $va_joins = array();
            $va_wheres = array();

            $va_wheres[] = $ps_tabela . "." . $ps_chave_primaria . " = (?) ";
            $va_tipos_parametros[] = "i";
            $va_parametros[] = $pn_codigo;

            if (isset($pn_idioma_codigo)) {
                $va_wheres[] = $ps_tabela . ".idioma_codigo = (?) ";
                $va_tipos_parametros[] = "i";
                $va_parametros[] = $pn_idioma_codigo;
            }

            foreach ($pa_filtros as $va_parametro_nome => $va_parametro)
            {
                $va_valores_busca = array();
                $vs_operador = "";
                $vs_interrogacoes = " (?) ";

                $vb_tem_valor = $this->montar_valores_busca($va_parametro, $va_valores_busca, $vs_operador, $vs_interrogacoes);

                if ($vb_tem_valor) 
                {
                    if (isset($va_campos_relacionamento_temp[$va_parametro_nome]))
                    {
                        $vs_tabela_filtro = $ps_tabela;

                        if (is_array($va_campos_relacionamento_temp[$va_parametro_nome]))
                            $vs_coluna_filtro = reset($va_campos_relacionamento_temp[$va_parametro_nome]);
                        else
                            $vs_coluna_filtro = $va_campos_relacionamento_temp[$va_parametro_nome];

                        $vn_index_tipo_campo = array_search($va_parametro_nome, array_keys($va_campos_relacionamento_temp));
                        $vs_tipo_dado_campo = $va_tipos_campos_relacionamento_temp[$vn_index_tipo_campo];
                    }
                    elseif ($ps_objeto_relacionamento)
                    {
                        $vo_objeto_filtro = new $ps_objeto_relacionamento('');

                        // O filtro pode ser atributo ou relacionamento do objeto
                        /////////////////////////////////////////////////////////

                        if (isset($vo_objeto_filtro->atributos[$va_parametro_nome]) || isset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]) )
                        {
                            $vs_chave_importada = reset($va_campos_relacionamento_temp);
                            if (is_array($vs_chave_importada))
                                $vs_chave_importada = reset($vs_chave_importada);

                            if (!in_array($vo_objeto_filtro->tabela_banco, array_keys($va_joins)))
                                $va_joins[$vo_objeto_filtro->tabela_banco] = " JOIN " . $vo_objeto_filtro->tabela_banco . " ON " . $ps_tabela . "." . $vs_chave_importada . " = " . $vo_objeto_filtro->tabela_banco . "." . $vo_objeto_filtro->chave_primaria["coluna_tabela"];

                            if (isset($vo_objeto_filtro->atributos[$va_parametro_nome]))
                            {
                                $vs_tabela_filtro = $vo_objeto_filtro->tabela_banco;
                                $vs_coluna_filtro = $vo_objeto_filtro->atributos[$va_parametro_nome]["coluna_tabela"];
                                $vs_tipo_dado_campo = $vo_objeto_filtro->atributos[$va_parametro_nome]["tipo_dado"];
                            }
                            elseif (isset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]))
                            {
                                if (!in_array($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"], array_keys($va_joins)))
                                    $va_joins[$vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"]] = " JOIN " . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"] . " ON " . $vo_objeto_filtro->tabela_banco . "." . $vo_objeto_filtro->chave_primaria["coluna_tabela"] . " = " . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"] . "." . $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["chave_exportada"];

                                $vs_tabela_filtro = $vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tabela_intermediaria"];
                                $vs_coluna_filtro = reset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["campos_relacionamento"]);
                                $vs_tipo_dado_campo = reset($vo_objeto_filtro->relacionamentos[$va_parametro_nome]["tipos_campos_relacionamento"]);
                            }
                        }
                    }

                    if ($vs_operador == "NOT")
                        $va_wheres[] = $vs_operador . " " . $vs_tabela_filtro . "." . $vs_coluna_filtro . " <=> " . $vs_interrogacoes;
                    else
                        $va_wheres[] = $vs_tabela_filtro . "." . $vs_coluna_filtro . " " . $vs_operador . $vs_interrogacoes;

                    foreach ($va_valores_busca as $va_valor_busca) 
                    {
                        $va_parametros[] = $va_valor_busca;
                        $va_tipos_parametros[] = $vs_tipo_dado_campo;
                    }
                }
            }           

            $this->banco_dados->excluir($ps_tabela, $va_wheres, $va_tipos_parametros, $va_parametros, $va_joins);
        }

        if ($vb_iniciada_transacao)
            $this->banco_dados->finalizar_transacao();
    }

    public function inserir_relacionamento($ps_tabela, $pa_chave_primaria, $pn_codigo, $pa_campos, $pa_tipos_campos, $pa_valores, $pn_idioma_codigo = null, $vb_atualizar = false)
    {
        $va_campos_relacionamento_inverso = array();
        $va_tipos_parametros_relacionamento_inverso = array();
        $va_parametros_relacionamento_inverso = array();
        
        $vs_chave_inversa = "";
        $vs_campo_inverso = "";

        $this->banco_dados = $this->get_banco();
        $vb_iniciada_transacao = $this->iniciar_transacao();

        if (is_array($pa_chave_primaria))
        {
            $ps_chave_primaria = $pa_chave_primaria[0];
            $vs_chave_inversa = $pa_chave_primaria[1];
        }
        else
            $ps_chave_primaria = $pa_chave_primaria;

        $this->inicializar_variaveis_banco();

        $this->va_campos[] = $ps_chave_primaria;
        $this->va_tipos_parametros[] = "i";
        $this->va_parametros[] = $pn_codigo;

        if ($vs_chave_inversa)
        {
            $va_campos_relacionamento_inverso[] = $vs_chave_inversa;
            $va_tipos_parametros_relacionamento_inverso[] = "i";
            $va_parametros_relacionamento_inverso[] = $pn_codigo;
        }

        if (!is_array($pa_campos)) {
            $va_campos_temp[$pa_campos] = $pa_campos;
            $va_tipos_campos_temp[] = $pa_tipos_campos;
        } else {
            $va_campos_temp = $pa_campos;
            $va_tipos_campos_temp = $pa_tipos_campos;
        }

        $contador = 0;
        $vb_adicionou_campo_inverso = false;

        foreach ($va_campos_temp as $vs_key_campo => $va_campo) 
        {
            if (is_array($va_campo)) 
            {
                $vs_campo = reset($va_campo);

                // Caso seja auto-relacionamento
                ////////////////////////////////

                if (is_array($vs_campo))
                {
                    $vs_campo_inverso = $vs_campo[1];
                    $vs_campo = reset($vs_campo);
                }
            } 
            else
                $vs_campo = $va_campo;

            if ($va_tipos_campos_temp[$contador] != "dt")
            {
                // Trata os checkboxes
                //////////////////////

                if ($va_tipos_campos_temp[$contador] == "b")
                    $vs_tipo_campo = "i";
                else
                    $vs_tipo_campo = $va_tipos_campos_temp[$contador];

                $vs_valor_campo = $pa_valores[$vs_key_campo];

                $this->va_campos[] = $vs_campo;
                $this->va_tipos_parametros[] = $vs_tipo_campo;
                $this->va_parametros[] = $vs_valor_campo;

                if ($vs_chave_inversa)
                {
                    if (!$vb_adicionou_campo_inverso)
                        $vs_campo = $vs_campo_inverso;

                    $va_campos_relacionamento_inverso[] = $vs_campo;
                    $va_tipos_parametros_relacionamento_inverso[] = $vs_tipo_campo;
                    $va_parametros_relacionamento_inverso[] = $vs_valor_campo;

                    $vb_adicionou_campo_inverso = true;
                }
            } 
            else
                $this->tratar_data($vs_key_campo, $va_campo, $pa_valores);

            $contador++;
        }

        if (isset($pn_idioma_codigo)) 
        {
            $this->va_campos[] = "idioma_codigo";
            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $pn_idioma_codigo;
        }

        if (!$vb_atualizar)
        {
            $this->banco_dados->inserir($ps_tabela, $this->va_campos, $this->va_tipos_parametros, $this->va_parametros, "IGNORE");

            if (count($va_campos_relacionamento_inverso))
                $this->banco_dados->inserir($ps_tabela, $va_campos_relacionamento_inverso, $va_tipos_parametros_relacionamento_inverso, $va_parametros_relacionamento_inverso, "IGNORE");
        }
        else
        {
            $this->va_wheres[] = $ps_tabela . "." . $ps_chave_primaria . " = (?)";
            $this->va_tipos_parametros[] = "i";
            $this->va_parametros[] = $pn_codigo;

            $this->banco_dados->atualizar($ps_tabela, $this->va_campos, $this->va_wheres, $this->va_tipos_parametros, $this->va_parametros);
        }

        if ($vb_iniciada_transacao)
            $this->banco_dados->finalizar_transacao();
    }

    public function validar_acesso_registro($pn_objeto_codigo, $pa_parametros_acesso)
    {
        if (!count($this->controlador_acesso))
            return true;

        $va_acessos_por_controlador = array();
    
        foreach ($this->controlador_acesso as $vs_key_controlador => $vs_atributo_controlador)
        {
            if (trim($pa_parametros_acesso[$vs_key_controlador]) == "")
                $va_acessos_por_controlador[$vs_key_controlador] = false;
            else
                $va_acessos_por_controlador[$vs_key_controlador] = true;
        }

        if (isset($pa_parametros_acesso["_combinacao_"]) && $pa_parametros_acesso["_combinacao_"] == "OR")
            $vb_acesso_invalido_registro = !in_array(true, $va_acessos_por_controlador);
        else
            $vb_acesso_invalido_registro = in_array(false, $va_acessos_por_controlador);

        if ($vb_acesso_invalido_registro)
            return false;

        $va_filtros[$this->get_chave_primaria()[0]] = $pn_objeto_codigo;

        foreach ($this->controlador_acesso as $vs_parametro_controlador => $vs_atributo_controlador)
        {
            if (isset($pa_parametros_acesso[$vs_parametro_controlador]))
            {
                if ($pa_parametros_acesso[$vs_parametro_controlador] == "_ALL_") continue;

                if ($vs_parametro_controlador == $this->get_chave_primaria()[0])
                {
                    if (!in_array($pn_objeto_codigo, explode("|", $pa_parametros_acesso[$vs_parametro_controlador])))
                        return false;
                }
                else
                    $va_filtros[$vs_atributo_controlador] = $pa_parametros_acesso[$vs_parametro_controlador];
            }
        }

        $va_registro = $this->ler_lista($va_filtros, "lista");

        if (!count($va_registro))
            return false;

        return true;
    }

    public function validar_edicao_registro($pa_valores, $pa_parametros_acesso)
    {    
        if (!count($this->controlador_acesso))
            return true;

        $va_acessos_por_controlador = array();
    
        foreach ($this->controlador_acesso as $vs_key_controlador => $vs_atributo_controlador)
        {
            if (trim($pa_parametros_acesso[$vs_key_controlador]) == "")
                $va_acessos_por_controlador[$vs_key_controlador] = false;
            else
                $va_acessos_por_controlador[$vs_key_controlador] = true;
        }

        if (isset($pa_parametros_acesso["_combinacao_"]) && $pa_parametros_acesso["_combinacao_"] == "OR")
            $vb_acesso_invalido_registro = !in_array(true, $va_acessos_por_controlador);
        else
            $vb_acesso_invalido_registro = in_array(false, $va_acessos_por_controlador);

        if ($vb_acesso_invalido_registro)
            return false;

        foreach ($this->controlador_acesso as $vs_parametro_controlador => $vs_atributo_controlador)
        {
            if (isset($pa_parametros_acesso[$vs_parametro_controlador]) && trim($pa_parametros_acesso[$vs_key_controlador]) != "")
            {
                if ($pa_parametros_acesso[$vs_parametro_controlador] == "_ALL_") continue;

                $va_atributo_controlador = explode("_0_", $vs_atributo_controlador);

                if (count($va_atributo_controlador) > 1) 
                    $vs_atributo_controlador =  $va_atributo_controlador[1];

                if (isset($pa_valores[$vs_atributo_controlador]) && trim($pa_valores[$vs_atributo_controlador] != ""))
                {
                    $va_valores_parametros_acesso = explode("|", $pa_parametros_acesso[$vs_parametro_controlador]);
                    $va_valores_form = explode("|", $pa_valores[$vs_atributo_controlador]);

                    foreach ($va_valores_form as $vs_valor)
                    {
                        if (!in_array($vs_valor, $va_valores_parametros_acesso))
                            return false;
                    }
                }
            }
        }

        return true;
    }


    public function get_banco(): Banco
    {
        return Banco::get_instance();
    }

    public function iniciar_transacao(): bool
    {
        $this->banco_dados = $this->get_banco();
        $vb_iniciada_transacao = false;
        if (!$this->banco_dados->iniciou_transacao()) {

            $this->banco_dados->abrir_conexao_banco();
            $this->banco_dados->iniciar_transacao();
            $vb_iniciada_transacao = true;
        }
        return $vb_iniciada_transacao;
    }

    public function finalizar_transacao()
    {
        $this->banco_dados->finalizar_transacao();
    }

    private function criar_diretorios() : void
    {
        $va_folder_media = config::get(["pasta_media"]);

        foreach ($va_folder_media as $folder) {
            if (is_array($folder)) {
                foreach ($folder as $sub_folder) {
                    if (!is_dir($sub_folder)) {
                        mkdir($sub_folder, 0777, true);
                    }
                }
            }
            else if (is_string($folder) && !is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
        }
    }


}

?>