<?php

class unidade_armazenamento extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->hierarquico = true;
        $this->campo_hierarquico = "unidade_armazenamento_unidade_armazenamento_superior_codigo";

        $this->registros_filhos["unidade_armazenamento"] = [
            "atributo_relacionamento" => "unidade_armazenamento_unidade_armazenamento_superior_codigo",
            "pode_excluir_pai" => true,
            "exibir_ficha_pai" => true
        ];
    }

    public function inicializar_tabela_banco()
    {
        return "unidade_armazenamento";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'unidade_armazenamento_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['unidade_armazenamento_nome'] = [
            'unidade_armazenamento_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['unidade_armazenamento_descricao'] = [
            'unidade_armazenamento_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['unidade_armazenamento_instituicao_codigo'] = [
            'unidade_armazenamento_instituicao_codigo',
            'coluna_tabela' => 'instituicao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'instituicao'
        ];

        $va_atributos['unidade_armazenamento_unidade_armazenamento_superior_codigo'] = [
            'unidade_armazenamento_unidade_armazenamento_superior_codigo',
            'coluna_tabela' => 'unidade_armazenamento_superior_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'unidade_armazenamento'
        ];


        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['unidade_armazenamento_item_acervo_codigo'] = [
            ['unidade_armazenamento_item_acervo_codigo'],
            'tabela_intermediaria' => 'item_acervo',
            'chave_exportada' => 'unidade_armazenamento_codigo',
            'campos_relacionamento' => [
                'unidade_armazenamento_item_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "item_acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'tipo' => '1n',
            'alias' => 'itens do acervo'
        ];

        $va_relacionamentos['unidade_armazenamento_unidade_armazenamento_inferior_codigo'] = [
            ['unidade_armazenamento_unidade_armazenamento_inferior_codigo'],
            'tabela_intermediaria' => 'unidade_armazenamento',
            'chave_exportada' => 'unidade_armazenamento_superior_codigo',
            'campos_relacionamento' => [
                'unidade_armazenamento_unidade_armazenamento_inferior_codigo' => [
                    ['codigo'],
                    "atributo" => "unidade_armazenamento_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'unidade_armazenamento',
            'objeto' => 'unidade_armazenamento',
            'tipo' => '1n',
            'alias' => 'locais de guarda inferiores'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '')
    {
        $va_campos_edicao = array();

        $va_campos_edicao["unidade_armazenamento_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "unidade_armazenamento_instituicao_codigo",
            "label" =>"Instituição",
            "objeto" => "instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "atributo_obrigatorio" => true,
            "sem_valor" => false,
            "foco" => true
        ];

        $va_campos_edicao["unidade_armazenamento_unidade_armazenamento_superior_codigo"] = [
            "html_combo_input",
            "nome" => "unidade_armazenamento_unidade_armazenamento_superior_codigo",
            "label" => "Local de guarda superior",
            "objeto" => "unidade_armazenamento",
            "atributos" => [
                "unidade_armazenamento_codigo",
                "unidade_armazenamento_nome" => ["hierarquia" => "unidade_armazenamento_unidade_armazenamento_superior_codigo"]
            ],
            "atributo" => "unidade_armazenamento_codigo",
            "sem_valor" => true,
            "filtro" => [
                [
                    "valor" => $pn_objeto_codigo,
                    "atributo" => "unidade_armazenamento_codigo",
                    "operador" => "!="
                ]
            ]
        ];

        $va_campos_edicao["unidade_armazenamento_nome"] = ["html_text_input", "nome" => "unidade_armazenamento_nome", "label" => "Nome"];

        $va_campos_edicao["unidade_armazenamento_descricao"] = [
            "html_text_input",
            "nome" => "unidade_armazenamento_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["unidade_armazenamento_nome"] = [
            "html_text_input",
            "nome" => "unidade_armazenamento_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["unidade_armazenamento_codigo"] = ["nome" => "unidade_armazenamento_codigo", "exibir" => false];

        $va_campos_visualizacao["unidade_armazenamento_nome"] = ["nome" => "unidade_armazenamento_nome"];

        $va_campos_visualizacao["unidade_armazenamento_instituicao_codigo"] = [
            "nome" => "unidade_armazenamento_instituicao_codigo",
            "formato" => ["campo" => "instituicao_nome"]
        ];

        $va_campos_visualizacao["unidade_armazenamento_unidade_armazenamento_superior_codigo"] = [
            "nome" => "unidade_armazenamento_unidade_armazenamento_superior_codigo",
            "formato" => [
                "campo" => "unidade_armazenamento_nome",
                "hierarquia" => "unidade_armazenamento_unidade_armazenamento_superior_codigo",
                "separador" => " > "
            ],
            "label" => "Local de guarda superior"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["unidade_armazenamento_nome" => "Nome"];

        $va_campos_visualizacao["unidade_armazenamento_descricao"] = ["nome" => "unidade_armazenamento_descricao"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["unidade_armazenamento_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "unidade_armazenamento_nome" => ["label" => "Nome", "main_field" => true],
        ];



        $va_campos_visualizacao["unidade_armazenamento_unidade_armazenamento_inferior_codigo"] = [
            "nome" => "unidade_armazenamento_unidade_armazenamento_inferior_codigo",
            "formato" => [
                "campo" => "unidade_armazenamento_nome"
            ]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "unidade_armazenamento_nome" => ["label" => "Nome", "main_field" => true],
            "unidade_armazenamento_descricao" => ["label" => "Descrição", "descriptive_field" => true],
            "unidade_armazenamento_unidade_armazenamento_superior_codigo" => "Local de guarda superior",
            "unidade_armazenamento_unidade_armazenamento_inferior_codigo" => "Locais de guarda inferiores"
        ];
    }


}

?>