<?php

class representante_digital extends objeto_base
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
        return "representante_digital";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['representante_digital_codigo'] = [
            'representante_digital_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['representante_digital_path'] = [
            'representante_digital_path',
            'coluna_tabela' => 'path',
            'tipo_dado' => 's'
        ];

        $va_atributos['representante_digital_registro_codigo'] = [
            'representante_digital_registro_codigo',
            'coluna_tabela' => 'registro_codigo',
            'tipo_dado' => 'i'
        ];

        $va_atributos['representante_digital_tipo_codigo'] = [
            'representante_digital_tipo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
        ];

        $va_atributos['representante_digital_legenda'] = [
            'representante_digital_legenda',
            'coluna_tabela' => 'legenda',
            'tipo_dado' => 's'
        ];

        $va_atributos['representante_digital_sequencia'] = [
            'representante_digital_sequencia',
            'coluna_tabela' => 'sequencia',
            'tipo_dado' => 'i'
        ];

        $va_atributos['representante_digital_publicado_online'] = [
            'representante_digital_publicado_online',
            'coluna_tabela' => 'publicado_online',
            'tipo_dado' => 'b'
        ];

        $va_atributos['representante_digital_formato'] = [
            'representante_digital_formato',
            'coluna_tabela' => 'formato',
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

        $va_campos_visualizacao["representante_digital_codigo"] = ["nome" => "representante_digital_codigo", "exibir" => false];
        $va_campos_visualizacao["representante_digital_path"] = ["nome" => "representante_digital_path"];
        $va_campos_visualizacao["representante_digital_registro_codigo"] = ["nome" => "representante_digital_registro_codigo", "exibir" => false];
        $va_campos_visualizacao["representante_digital_tipo_codigo"] = ["nome" => "representante_digital_tipo_codigo", "exibir" => false];
        $va_campos_visualizacao["representante_digital_legenda"] = ["nome" => "representante_digital_legenda"];
        $va_campos_visualizacao["representante_digital_sequencia"] = ["nome" => "representante_digital_sequencia"];
        $va_campos_visualizacao["representante_digital_publicado_online"] = ["nome" => "representante_digital_publicado_online"];
        $va_campos_visualizacao["representante_digital_formato"] = ["nome" => "representante_digital_formato"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        //$this->visualizacoes["lista"]["order_by"] = "ano";

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        //$this->visualizacoes["ficha"]["order_by"] = "ano";
    }

}

?>