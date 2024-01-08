<?php

class tecnica_registro extends objeto_base
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
        return "tecnica_registro";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tecnica_registro_codigo'] = [
            'tecnica_registro_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tecnica_registro_nome'] = [
            'tecnica_registro_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tecnica_registro_documento_codigo'] = [
            ['tecnica_registro_documento_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'tecnica_registro_codigo',
            'campos_relacionamento' => [
                'tecnica_registro_documento_codigo' => [
                    ['codigo'],
                    "atributo" => "documento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'tipo' => '1n',
            'alias' => 'documentos'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tecnica_registro_nome"] = [
            "html_text_input",
            "nome" => "tecnica_registro_nome",
            "label" => "nome", "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tecnica_registro_nome"] = [
            "html_text_input",
            "nome" => "tecnica_registro_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tecnica_registro_codigo"] = ["nome" => "tecnica_registro_codigo"];
        $va_campos_visualizacao["tecnica_registro_nome"] = ["nome" => "tecnica_registro_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tecnica_registro_nome" => "nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tecnica_registro_nome" => "nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>