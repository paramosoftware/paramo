<?php

class tipo_material extends objeto_base
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
        return "tipo_material";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_material_codigo'] = [
            'tipo_material_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_material_nome'] = [
            'tipo_material_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['tipo_material_descricao'] = [
            'tipo_material_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_material_biblioteca_codigo'] = [
            [
                'tipo_material_biblioteca_codigo'
            ],
            'tabela_intermediaria' => 'acervo_tipo_material',
            'chave_exportada' => 'tipo_material_codigo',
            'campos_relacionamento' => [
                'tipo_material_biblioteca_codigo' => 'acervo_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'biblioteca',
            'objeto' => 'biblioteca',
            'alias' => 'bibliotecas'
        ];

        $va_relacionamentos['tipo_material_colecao_codigo'] = [
            [
                'tipo_material_colecao_codigo'
            ],
            'tabela_intermediaria' => 'colecao_tipo_material',
            'chave_exportada' => 'tipo_material_codigo',
            'campos_relacionamento' => [
                'tipo_material_colecao_codigo' => 'colecao_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'colecao',
            'objeto' => 'colecao',
            'alias' => 'coleções'
        ];

        $va_relacionamentos['tipo_material_livro_codigo'] = [
            ['tipo_material_livro_codigo'],
            'tabela_intermediaria' => 'livro',
            'chave_exportada' => 'tipo_material_codigo',
            'campos_relacionamento' => [
                'tipo_material_livro_codigo' => [
                    ['codigo'],
                    "atributo" => "livro_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'livro',
            'objeto' => 'livro',
            'tipo' => '1n',
            'alias' => "livros"
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["tipo_material_nome"] = [
            "html_text_input",
            "nome" => "tipo_material_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["tipo_material_descricao"] = [
            "html_text_input",
            "nome" => "tipo_material_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_material_nome"] = [
            "html_text_input",
            "nome" => "tipo_material_nome",
            "label" => "Nome e descrição",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["tipo_material_descricao"] = [
            "html_text_input",
            "nome" => "tipo_material_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["tipo_material_codigo"] = ["nome" => "tipo_material_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_material_nome"] = ["nome" => "tipo_material_nome"];
        $va_campos_visualizacao["tipo_material_descricao"] = ["nome" => "tipo_material_descricao"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_material_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_material_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "tipo_material_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "tipo_material_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>