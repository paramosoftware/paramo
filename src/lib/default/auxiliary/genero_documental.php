<?php

class genero_documental extends objeto_base
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
        return "genero_documental";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['genero_documental_codigo'] = [
            'genero_documental_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['genero_documental_nome'] = [
            'genero_documental_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['genero_documental_descricao'] = [
            'genero_documental_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['genero_documental_documento_codigo'] = [
            ['genero_documental_documento_codigo'],
            'tabela_intermediaria' => 'documento',
            'chave_exportada' => 'genero_documental_codigo',
            'campos_relacionamento' => [
                'genero_documental_documento_codigo' => [
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

        $va_campos_edicao["genero_documental_nome"] = [
            "html_text_input",
            "nome" => "genero_documental_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["genero_documental_descricao"] = [
            "html_text_input",
            "nome" => "genero_documental_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["genero_documental_nome"] = [
            "html_text_input",
            "nome" => "genero_documental_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["genero_documental_descricao"] = [
            "html_text_input",
            "nome" => "genero_documental_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["genero_documental_codigo"] = ["nome" => "genero_documental_codigo", "exibir" => false];
        $va_campos_visualizacao["genero_documental_nome"] = ["nome" => "genero_documental_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["genero_documental_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["genero_documental_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "genero_documental_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["genero_documental_descricao"] = ["nome" => "genero_documental_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "genero_documental_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>