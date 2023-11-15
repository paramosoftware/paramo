<?php

class colecao extends objeto_base
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
        return "colecao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['colecao_codigo'] = [
            'colecao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['colecao_nome'] = [
            'colecao_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['colecao_descricao'] = [
            'colecao_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['colecao_acervo_codigo'] = [
            'colecao_acervo_codigo',
            'coluna_tabela' => 'acervo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'acervo'
        ];

        $va_atributos['colecao_autoria_codigo'] = [
            'colecao_autoria_codigo',
            'coluna_tabela' => 'entidade_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'entidade'
        ];

        $va_atributos['colecao_quantidade_itens'] = [
            'colecao_quantidade_itens',
            'coluna_tabela' => 'quantidade_itens',
            'tipo_dado' => 'i'
        ];

        return $va_atributos;

    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['colecao_assunto_codigo'] = [
            [
                'colecao_assunto_codigo'
            ],
            'tabela_intermediaria' => 'colecao_assunto',
            'chave_exportada' => 'colecao_codigo',
            'campos_relacionamento' => [
                'colecao_assunto_codigo' => 'assunto_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'assunto',
            'objeto' => 'assunto',
            'alias' => 'assuntos'
        ];

        $va_relacionamentos['colecao_entidade_codigo'] = [
            [
                'colecao_entidade_codigo'
            ],
            'tabela_intermediaria' => 'colecao_entidade',
            'chave_exportada' => 'colecao_codigo',
            'campos_relacionamento' => [
                'colecao_entidade_codigo' => 'entidade_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'autoridades'
        ];

        $va_relacionamentos['colecao_livro_codigo'] = [
            [
                'colecao_livro_codigo'
            ],
            'tabela_intermediaria' => 'livro_colecao',
            'chave_exportada' => 'colecao_codigo',
            'campos_relacionamento' => [
                'colecao_livro_codigo' => 'livro_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'livro',
            'objeto' => 'livro',
            'alias' => 'livros'
        ];

        $va_relacionamentos['colecao_tipo_material_codigo'] = [
            [
                'colecao_tipo_material_codigo'
            ],
            'tabela_intermediaria' => 'colecao_tipo_material',
            'chave_exportada' => 'colecao_codigo',
            'campos_relacionamento' => [
                'colecao_tipo_material_codigo' => 'tipo_material_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'tipo_material',
            'objeto' => 'tipo_material',
            'alias' => 'tipos de materiais'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["colecao_nome"] = [
            "html_text_input",
            "nome" => "colecao_nome",
            "label" => "Nome"
        ];

        $va_campos_edicao["colecao_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "colecao_acervo_codigo",
            "label" => "Biblioteca",
            "objeto" => "acervo",
            "atributos" => ["acervo_codigo", "entidade_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => false,
            "dependencia" => [
                [
                    "campo" => "acervo_codigo",
                    "atributo" => "acervo_codigo",
                    "obrigatoria" => true
                ]
            ]
        ];

        $va_campos_edicao["colecao_autoria_codigo"] = [
            "html_autocomplete",
            "nome" => ['colecao_autoria', 'colecao_autoria_codigo'],
            "label" => "Autoria",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["colecao_tipo_material_codigo"] = [
            "html_autocomplete",
            "nome" => ["colecao_tipo_material", "colecao_tipo_material_codigo"],
            "label" => "Tipos de materiais",
            "objeto" => "tipo_material",
            "atributos" => ["tipo_material_codigo", "tipo_material_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "tipo_material_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "tipo_material_nome"
        ];

        $va_campos_edicao["colecao_descricao"] = [
            "html_text_input",
            "nome" => "colecao_descricao",
            "label" => "Descrição",
            "numero_linhas" => 5
        ];

        $va_campos_edicao["colecao_entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["colecao_entidade", "colecao_entidade_codigo"],
            "label" => "Relacionamentos com autoridades",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["colecao_assunto_codigo"] = [
            "html_autocomplete",
            "nome" => ["colecao_assunto", "colecao_assunto_codigo"],
            "label" => "Relacionamentos com assuntos",
            "objeto" => "assunto",
            "atributos" => ["assunto_codigo", "assunto_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "assunto_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "assunto_nome"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["colecao_acervo_codigo"] = [
            "html_combo_input",
            "nome" => "colecao_acervo_codigo",
            "label" => "Biblioteca",
            "objeto" => "biblioteca",
            "atributos" => ["acervo_codigo", "entidade_nome"],
            "atributo" => "acervo_codigo",
            "sem_valor" => true,
            "operador_filtro" => "=",
            "dependencia" => [
                [
                    "tipo" => "interface",
                    "campo" => "acervo_codigo",
                    "atributo" => "acervo_codigo",
                    "obrigatoria" => true
                ]
            ],
            "css-class" => "form-select"
        ];

        $va_filtros_navegacao["colecao_nome"] = [
            "html_text_input",
            "nome" => "colecao_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["colecao_codigo"] = ["nome" => "colecao_codigo", "exibir" => false];
        $va_campos_visualizacao["colecao_nome"] = ["nome" => "colecao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["colecao_nome" => "Nome"];

        $va_campos_visualizacao["colecao_acervo_codigo"] = [
            "nome" => "colecao_acervo_codigo",
            "formato" => [
                "campo" => "entidade_nome"
            ]
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["colecao_nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "colecao_nome" => ["label" => "Nome", "main_field" => true],
            "colecao_acervo_codigo" => "Biblioteca"
        ];

        $va_campos_visualizacao["colecao_descricao"] = [
            "nome" => "colecao_descricao"
        ];

        $va_campos_visualizacao["colecao_autoria_codigo"] = ["nome" => "colecao_autoria_codigo"];
        $va_campos_visualizacao["colecao_entidade_codigo"] = ["nome" => "colecao_entidade_codigo"];
        $va_campos_visualizacao["colecao_assunto_codigo"] = ["nome" => "colecao_assunto_codigo"];
        $va_campos_visualizacao["colecao_tipos_material_codigo"] = ["nome" => "colecao_tipos_materiais"];
        $va_campos_visualizacao["colecao_descricao"] = ["nome" => "colecao_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "colecao_nome" => ["label" => "Nome", "main_field" => true]
        ];
    }

}