<?php

class estado_organizacao extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));
        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
        return "estado_organizacao_acervo";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['estado_organizacao_codigo'] = [
            'estado_organizacao_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['estado_organizacao_nome'] = [
            'estado_organizacao_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['estado_organizacao_acervo_codigo'] = [
            ['estado_organizacao_acervo_codigo'],
            'tabela_intermediaria' => 'acervo',
            'chave_exportada' => 'estado_organizacao_codigo',
            'campos_relacionamento' => [
                'estado_organizacao_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'tipo' => '1n',
            'alias' => "acervos"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["estado_organizacao_codigo"] = [
            "nome" => "estado_organizacao_codigo"
        ];
        $va_campos_visualizacao["estado_organizacao_nome"] = [
            "nome" => "estado_organizacao_nome"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = [
            "estado_organizacao_nome" => "Nome"
        ];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "estado_organizacao_nome" => ["label" => "Nome", "main_field" => true]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "estado_organizacao_nome" => ["label" => "Nome", "main_field" => true]
        ];
    }

}

?>