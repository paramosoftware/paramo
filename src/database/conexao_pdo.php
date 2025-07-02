<?php
class conexao_pdo
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


    private function criar_conexao(): ?PDO
    {
        try {
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            $conexao = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password, $options);
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexao->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        } catch (PDOException $e) {
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

            if (isset($pa_tipos_parametros) && isset($pa_parametros))
            {
                if (count($pa_tipos_parametros) && count($pa_parametros)) {
                    $va_tipos_parametros = $this->converter_pdo_types($pa_tipos_parametros);
                    for ($i = 0; $i < count($va_tipos_parametros); $i++) {
                        $vo_stmt->bindParam($i + 1, $pa_parametros[$i], $va_tipos_parametros[$i]);
                    }
                }
            }
            $vo_stmt->execute();
            while ($row = $vo_stmt->fetch(PDO::FETCH_ASSOC)) {
                $va_resultado[] = $row;
            }
            $vo_stmt->closeCursor();
        } catch (PDOException $e) {
            $this->error = true;
            $vo_banco->finalizar_transacao();
            $vs_message = $e->getMessage() . " - " . $ps_sql . " - " . implode(",", $pa_parametros) . " - " . $e->getTraceAsString();
            session::log_and_redirect_error("Erro ao executar query no banco de dados", $vs_message, true);
        }

        if (!$vb_transacao_iniciada) {
            $vo_conexao = null;
        }

        return $va_resultado;
    }

    function executar($ps_sql, $pa_tipos_parametros, $pa_parametros)
    {
        try {
            $stmt = $this->con->prepare($ps_sql);
            $va_tipos_parametros = $this->converter_pdo_types($pa_tipos_parametros);
            for ($i = 0; $i < count($va_tipos_parametros); $i++) {
                $stmt->bindParam($i + 1, $pa_parametros[$i], $va_tipos_parametros[$i]);
            }
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            $this->error = true;
            $vo_banco = Banco::get_instance();
            $vo_banco->finalizar_transacao();
            $vs_message = $e->getMessage() . " - " . $ps_sql . " - " . implode(",", $pa_parametros) . " - " . $e->getTraceAsString();
            session::log_and_redirect_error("Erro ao executar query no banco de dados", $vs_message, true);
        }
    }


    function iniciar_transacao()
    {
        $this->con->beginTransaction();
    }

    function finalizar_transacao()
    {
        if ($this->error && $this->con->inTransaction())
        {
            $this->con->rollBack();
        }
        else if ($this->con->inTransaction())
        {
            $this->con->commit();
        }
    }

    function desconectar()
    {
        $this->con = null;
    }

    private function converter_pdo_types($pa_tipos_parametros): array
    {
        return array_map(function ($val) {
            switch ($val) {
                case "i":
                    return PDO::PARAM_INT;
                case "b":
                    return PDO::PARAM_BOOL;
                default:
                    return PDO::PARAM_STR;
            }
        }, $pa_tipos_parametros);

    }

    function get_last_inserted_id()
    {
        return $this->con->lastInsertId();
    }

}

?>