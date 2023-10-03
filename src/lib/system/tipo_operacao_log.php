<?php

class tipo_operacao_log extends objeto_base
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
        return "tipo_operacao_log";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_operacao_log_codigo'] = [
            'tipo_operacao_log_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_operacao_log_nome'] = [
            'tipo_operacao_log_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();
        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_operacao_log_nome"] = [
            "html_text_input",
            "nome" => "tipo_operacao_log_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["tipo_operacao_log_descricao"] = [
            "html_text_input",
            "nome" => "tipo_operacao_log_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_operacao_log_codigo"] = ["nome" => "tipo_operacao_log_codigo"];
        $va_campos_visualizacao["tipo_operacao_log_nome"] = ["nome" => "tipo_operacao_log_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_operacao_log_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_operacao_log_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>