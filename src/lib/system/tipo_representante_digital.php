<?php

class tipo_representante_digital extends objeto_base
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
        return "tipo_representante_digital";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_representante_digital_codigo'] = [
            'tipo_representante_digital_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_representante_digital_nome'] = [
            'tipo_representante_digital_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_representante_digital_representante_digital_codigo'] = [
            ['tipo_representante_digital_representante_digital_codigo'],
            'tabela_intermediaria' => 'representante_digital',
            'chave_exportada' => 'tipo_codigo',
            'campos_relacionamento' => [
                'tipo_representante_digital_representante_digital_codigo' => [
                    ['codigo'],
                    "atributo" => "representante_digital_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'representante_digital',
            'objeto' => 'representante_digital',
            'tipo' => '1n',
            'alias' => "representantes digitais"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_representante_digital_nome"] = [
            "html_text_input",
            "nome" => "tipo_representante_digital_nome",
            "label" => "Nome",
            "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_representante_digital_codigo"] = ["nome" => "tipo_representante_digital_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_representante_digital_nome"] = ["nome" => "tipo_representante_digital_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_representante_digital_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_representante_digital_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_representante_digital_nome" => ["label" => "Nome", "main_field" => true]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_representante_digital_nome" => ["label" => "Nome", "main_field" => true]
        ];
    }

}

?>