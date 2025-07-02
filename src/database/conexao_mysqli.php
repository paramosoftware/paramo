<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class conexao_mysqli
{
    private $host;
    private $database;
    private $user;
    private $password;
    private $error = false;
    private $con;

    public function __construct($host, $database, $user, $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->con = $this->criar_conexao();
    }

    private function criar_conexao(): ?mysqli
    {
        try {
            $conexao = new mysqli($this->host, $this->user, $this->password, $this->database);
            $conexao->set_charset("utf8");
            $conexao->autocommit(false);
        } catch (Exception $e) {
            $this->error = true;
            $vs_message = $e->getMessage() . " - " . $e->getTraceAsString();
            session::log_and_redirect_error("Não foi possível conectar ao banco de dados", $vs_message, true);
        }

        return $conexao;
    }

    function consultar($ps_sql, $pa_tipos_parametros = null, $pa_parametros = null): array
    {
        $va_resultado = array();

        $vo_banco = Banco::get_instance();
        $vb_transacao_iniciada = $vo_banco->iniciou_transacao();

        if ($vb_transacao_iniciada)
        {
            $vo_conexao = $vo_banco->get_conexao_banco()->con;
        }
        else
        {
            $vo_conexao = $this->criar_conexao();
        }

        try {
            $vo_stmt = $vo_conexao->prepare($ps_sql);

            if (isset($pa_tipos_parametros) && isset($pa_parametros)) {
                if (count($pa_tipos_parametros) && count($pa_parametros)) {
                    // O tipo de parâmetro "b" é de controle interno: substituir por "i"
                    $va_tipos_parametros = array_map(function ($val) {
                        return str_replace("b", "i", $val);
                    }, $pa_tipos_parametros);
                    $vo_stmt->bind_param(join("", $va_tipos_parametros), ...$pa_parametros);
                }
            }
            $vo_stmt->execute();
            $result = $vo_stmt->get_result();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $va_resultado[] = $row;
            }
            $vo_stmt->close();
        } catch (Exception $e) {
            $this->error = true;
            $vo_banco->finalizar_transacao();
            $vs_message = $e->getMessage() . " - " . $ps_sql . " - " . implode(",", $pa_parametros) . " - " . $e->getTraceAsString();
            session::log_and_redirect_error("Erro ao executar query no banco de dados", $vs_message, true);
        }

        if (!$vb_transacao_iniciada) {
            $vo_conexao->close();
        }

        return $va_resultado;
    }

    function executar($ps_sql, $pa_tipos_parametros, $pa_parametros)
    {
        try {
            $stmt = $this->con->prepare($ps_sql);
            $stmt->bind_param(join("", $pa_tipos_parametros), ...$pa_parametros);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            $this->error = true;
            $vo_banco = Banco::get_instance();
            $vo_banco->finalizar_transacao();
            $vs_message = $e->getMessage() . " - " . $ps_sql . " - " . implode(",", $pa_parametros) . " - " . $e->getTraceAsString();
            session::log_and_redirect_error("Erro ao executar query no banco de dados", $vs_message, true);
        }
    }

    function executar_sql($ps_sql)
    {
        try {
            $stmt = $this->con->prepare($ps_sql);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            $this->error = true;
            $vo_banco = Banco::get_instance();
            $vo_banco->finalizar_transacao();
            $vs_message = $e->getMessage() . " - " . $ps_sql . " - " . $e->getTraceAsString();
            session::log_and_redirect_error("Erro ao executar query no banco de dados", $vs_message, true);
        }
    }

    function iniciar_transacao()
    {
        $this->con->begin_transaction();
    }

    function finalizar_transacao()
    {
        if ($this->error)
        {
            $this->con->rollback();
        }
        else
        {
            $this->con->commit();
        }
    }

    function desconectar()
    {
        $this->con->close();
    }

    function get_last_inserted_id()
    {
        return $this->con->insert_id;
    }

}