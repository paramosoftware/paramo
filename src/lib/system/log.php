<?php

class log extends objeto_base
{

    function __construct()
    {
        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();
        $this->autoincrement_codigo = true;
    }

    public function inicializar_tabela_banco()
    {
        return "log";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['log_codigo'] = [
            'log_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['log_id_registro'] = [
            'log_id_registro',
            'coluna_tabela' => 'id_registro',
            'tipo_dado' => 's'
        ];

        $va_atributos['log_codigo_registro'] = [
            'log_codigo_registro',
            'coluna_tabela' => 'registro_codigo',
            'tipo_dado' => 's'
        ];

        $va_atributos['log_usuario_codigo'] = [
            'log_usuario_codigo',
            'coluna_tabela' => 'usuario_codigo',
            'tipo_dado' => 'i'
        ];

        $va_atributos['log_data'] = [
            'log_data',
            'coluna_tabela' => 'data',
            'tipo_dado' => 's'
        ];

        $va_atributos['log_tipo_operacao_codigo'] = [
            'log_tipo_operacao_codigo',
            'coluna_tabela' => 'tipo_operacao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_operacao_log'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();
        return $va_relacionamentos;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["log_codigo"] = ["nome" => "log_codigo", "exibir" => false];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["log_data"];

        $va_campos_visualizacao["log_id_registro"] = ["nome" => "log_id_registro"];
        $va_campos_visualizacao["log_codigo_registro"] = ["nome" => "log_codigo_registro"];
        $va_campos_visualizacao["log_usuario_codigo"] = ["nome" => "log_usuario_codigo"];
        $va_campos_visualizacao["log_data"] = ["nome" => "log_data"];
        $va_campos_visualizacao["log_tipo_operacao_codigo"] = ["nome" => "log_tipo_operacao_codigo"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["log_data"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>