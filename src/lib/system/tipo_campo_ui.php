<?php

class tipo_campo_ui extends objeto_base
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
        return "tipo_campo_ui";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_campo_ui_codigo'] = [
            'tipo_campo_ui_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_campo_ui_nome'] = [
            'tipo_campo_ui_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['tipo_campo_ui_classe'] = [
            'tipo_campo_ui_classe',
            'coluna_tabela' => 'classe',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_campo_ui_campo_sistema_codigo'] = [
            ['tipo_campo_ui_campo_sistema_codigo'],
            'tabela_intermediaria' => 'campo_sistema',
            'chave_exportada' => 'tipo_campo_ui_codigo',
            'campos_relacionamento' => [
                'tipo_campo_ui_campo_sistema_codigo' => [
                    ['codigo'],
                    "atributo" => "campo_sistema_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'campo_sistema',
            'objeto' => 'campo_sistema',
            'tipo' => '1n',
            'alias' => "campos de sistema"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_campo_ui_codigo"] = ["nome" => "tipo_campo_ui_codigo"];
        $va_campos_visualizacao["tipo_campo_ui_nome"] = ["nome" => "tipo_campo_ui_nome"];
        $va_campos_visualizacao["tipo_campo_ui_classe"] = ["nome" => "tipo_campo_ui_classe"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }


}

?>