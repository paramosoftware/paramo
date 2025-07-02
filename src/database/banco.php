<?php

class Banco
{
    private static $instance = null;
    private $transacao_aberta = false;
    private $conexao_banco;
    private $salvar = true;

    private function __construct()
    {
        $this->abrir_conexao_banco();
    }

    private function __clone()
    {
    }

    public static function get_instance(): Banco
    {
        if (self::$instance == null) {
            self::$instance = new Banco();
        }
        return self::$instance;
    }


    public function consultar($pa_selects, $pa_tipos_valores = null, $pa_valores = null, $pa_order_by = null, $ps_limit = null, $pb_distinct = true, $pa_group_by = null, $vs_ordem = null): array
    {
        $va_selects = array();
        foreach ($pa_selects as $va_select) {
            $vs_select = "SELECT ";

            if ($pb_distinct)
                $vs_select = $vs_select . "DISTINCT";

            $vs_select = $vs_select . " " . join(", ", $va_select["campos"])
                . " FROM " . $va_select["tabela"];

            if (isset($va_select["joins"])) {
                if (count($va_select["joins"]))
                    $vs_select = $vs_select . " " . join(" ", $va_select["joins"]);
            }

            if (isset($va_select["wheres"])) 
            {
                if (count($va_select["wheres"])) 
                {
                    $vs_select .= " WHERE ";

                    if (!isset($va_select["concatenadores"]) || !count($va_select["concatenadores"]))
                        $vs_select .= join(" AND ", $va_select["wheres"]);
                    else
                    {
                        $vs_wheres = "";
                        $vn_contador = 0;
                        $vs_parenteses_inicio_where = "";

                        foreach ($va_select["wheres"] as $vs_where)
                        {
                            $vs_concatenador = "AND";

                            if (isset($va_select["concatenadores"][$vn_contador]))
                                $vs_concatenador = $va_select["concatenadores"][$vn_contador];

                            if ($vn_contador == 0) 
                            {
                                if ($vs_concatenador != "NOT")
                                    $vs_concatenador = "";
                            }
                            elseif ($vs_concatenador == "NOT")
                                $vs_concatenador = "AND NOT";
                            elseif ($vs_concatenador == "_SEM_VALOR_")
                                $vs_concatenador = "AND";
                                    
                            $vs_wheres .= $vs_concatenador . " " . $vs_where . ") ";
                            $vs_parenteses_inicio_where .= "(";                            

                            $vn_contador++;
                        }

                        $vs_select .= $vs_parenteses_inicio_where . $vs_wheres;
                        //var_dump($vs_select, $pa_valores);
                    }
                }
            }

            $va_selects[] = $vs_select;
        }

        $vs_select = join(" UNION ", $va_selects);

        if (isset($pa_group_by)) {
            if (count($pa_group_by)) {
                $vs_select = $vs_select . " GROUP BY "
                    . join(", ", $pa_group_by);
            }
        }

        if (isset($pa_order_by)) {
            if (!is_array($pa_order_by) && ($pa_order_by))
                $va_order_by = array($pa_order_by);
            else
                $va_order_by = $pa_order_by;

            if (isset($va_order_by[0]) && $va_order_by[0] == "_rand_")
                $vs_select = $vs_select . " ORDER BY RAND()";

            elseif (count($va_order_by) && join(", ", $va_order_by)) {
                $vs_select = $vs_select . " ORDER BY "
                    . join(", ", $va_order_by);
            }
        }

        if (isset($ps_limit))
            $vs_select = $vs_select . " " . $ps_limit;

        return $this->conexao_banco->consultar($vs_select, $pa_tipos_valores, $pa_valores);
    }

    public function inserir($ps_table, $pa_columns, $pa_tipos_valores, $pa_valores, $vs_insert_ignore = '')
    {
        $vs_select = "INSERT " . $vs_insert_ignore . " INTO "
            . $ps_table
            . " (" . join(", ", $pa_columns) . ")"
            . " VALUES "
            . "(" . implode(", ", array_fill(0, count($pa_columns), "?")) . ")";

        if ($this->salvar) {
            $this->conexao_banco->executar($vs_select, $pa_tipos_valores, $pa_valores);
            return $this->conexao_banco->get_last_inserted_id();
        }
        else
            var_dump($vs_select, $pa_tipos_valores, $pa_valores);
    }

    public function atualizar($ps_table, $pa_columns, $pa_wheres = null, $pa_tipos_parametros = null, $pa_parametros = null)
    {
        $vs_select = "UPDATE "
            . $ps_table
            . " SET ";

        $vn_contador_colunas = 0;
        foreach ($pa_columns as $vs_column) {
            $vs_select = $vs_select . $vs_column . " = ?";

            if ($vn_contador_colunas < count($pa_columns) - 1)
                $vs_select = $vs_select . ", ";

            $vn_contador_colunas++;
        }

        if (isset($pa_wheres)) {
            if (count($pa_wheres)) {
                $vs_select = $vs_select . " WHERE "
                    . join(" AND ", $pa_wheres);
            }
        }

        if ($this->salvar)
            $this->conexao_banco->executar($vs_select, $pa_tipos_parametros, $pa_parametros);
        else
            var_dump($vs_select, $pa_tipos_parametros, $pa_parametros);

    }

    public function excluir($ps_table, $pa_wheres = null, $pa_tipos_parametros = null, $pa_parametros = null, $pa_joins = null)
    {
        $vs_delete = " DELETE ";

        if (!isset($pa_joins) || (count($pa_joins) == 0))
            $vs_delete .= " FROM ";
        
        $vs_delete .= $ps_table;

        if (isset($pa_joins)) {
            if (count($pa_joins)) {
                $vs_delete .= " FROM "
                    . $ps_table;

                $vs_delete = $vs_delete . " " . join(" ", $pa_joins);
            }
        }

        if (isset($pa_wheres)) {
            if (count($pa_wheres)) {
                $vs_delete = $vs_delete . " WHERE "
                    . join(" AND ", $pa_wheres);
            }
        }

        if ($this->salvar)
            $this->conexao_banco->executar($vs_delete, $pa_tipos_parametros, $pa_parametros);
        else
            var_dump($vs_delete, $pa_tipos_parametros, $pa_parametros);
    }

    public function executar_sql($ps_sql)
    {
        $this->conexao_banco->executar_sql($ps_sql);
    }

    function iniciar_transacao()
    {
        $this->conexao_banco->iniciar_transacao();
        $this->transacao_aberta = true;
    }

    function finalizar_transacao()
    {
        $this->conexao_banco->finalizar_transacao();
        $this->conexao_banco->desconectar();
        $this->transacao_aberta = false;
    }

    function desconectar_banco()
    {
        $this->conexao_banco->desconectar();
    }

    public function iniciou_transacao(): bool
    {
        return $this->transacao_aberta;
    }

    public function get_conexao_banco()
    {
        return $this->conexao_banco;
    }

    public function abrir_conexao_banco()
    {
        $conexao = new Conexao();
        $this->conexao_banco = $conexao->get_conexao();
    }

}

?>