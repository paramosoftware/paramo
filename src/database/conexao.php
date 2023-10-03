<?php
class Conexao
{
    private $db_extension;
    private $host;
    private $database;
    private $user;
    private $password;

    public function __construct()
    {
        $this->db_extension = config::get(["db_extension"]) ?? "mysqli";
        $this->host = config::get(["db_host"]);
        $this->database = config::get(["db_name"]);
        $this->user = config::get(["db_user"]);
        $this->password = config::get(["db_password"]);
    }

    public function get_conexao()
    {
        if ($this->db_extension == "pdo") {
            return new conexao_pdo($this->host, $this->database, $this->user, $this->password);
        } else {
            return new conexao_mysqli($this->host, $this->database, $this->user, $this->password);
        }
    }

}

?>