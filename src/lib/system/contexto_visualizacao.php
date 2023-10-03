<?php

class contexto_visualizacao extends objeto_base
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
        return "contexto_visualizacao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['contexto_visualizacao_codigo'] = [
            'contexto_visualizacao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['contexto_visualizacao_nome'] = [
            'contexto_visualizacao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's',
            'identificador' => true
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['contexto_visualizacao_documento_codigo'] = [
            [
                'contexto_visualizacao_documento_codigo'
            ],
            'tabela_intermediaria' => 'documento_contexto_visualizacao',
            'chave_exportada' => 'contexto_visualizacao_codigo',
            'campos_relacionamento' => ['contexto_visualizacao_documento_codigo' => 'documento_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'alias' => 'documentos'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["contexto_visualizacao_nome"] = ["html_text_input", "nome" => "contexto_visualizacao_nome", "label" => "Nome", "foco" => true];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["contexto_visualizacao_codigo"] = ["nome" => "contexto_visualizacao_codigo", "exibir" => false];
        $va_campos_visualizacao["contexto_visualizacao_nome"] = ["nome" => "contexto_visualizacao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["contexto_visualizacao_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["contexto_visualizacao_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "contexto_visualizacao_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "contexto_visualizacao_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>