<?php

class log_evento extends objeto_base
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
        return "log_evento";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['log_evento_codigo'] = [
            'log_evento_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['log_evento_nome'] = [
            'log_evento_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['log_evento_data'] = [
            'log_evento_data',
            'coluna_tabela' => 'data',
            'tipo_dado' => 's'
        ];

        $va_atributos['log_evento_sucesso'] = [
            'log_evento_sucesso',
            'coluna_tabela' => 'sucesso',
            'tipo_dado' => 'b'
        ];

        $va_atributos['log_evento_mensagem'] = [
            'log_evento_mensagem',
            'coluna_tabela' => 'mensagem',
            'tipo_dado' => 's'
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
        $va_campos_visualizacao["log_evento_codigo"] = ["nome" => "log_evento_codigo"];
        $va_campos_visualizacao["log_evento_nome"] = ["nome" => "log_evento_nome"];
        $va_campos_visualizacao["log_evento_data"] = ["nome" => "log_evento_data"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["log_evento_data"];

        $va_campos_visualizacao["log_evento_sucesso"] = ["nome" => "log_evento_sucesso"];
        $va_campos_visualizacao["log_evento_mensagem"] = ["nome" => "log_evento_mensagem"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["log_data"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}