<?php

class editora extends entidade
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->objeto_pai = "entidade";
        $this->campo_relacionamento_pai = "entidade_codigo";

        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
        return "editora";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['editora_codigo'] = [
            'editora_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['entidade_codigo'] = [
            'entidade_codigo',
            'coluna_tabela' => 'entidade_codigo',
            'tipo_dado' => 'i'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['editora_livro_codigo'] = [
            ['editora_livro_codigo'],
            'tabela_intermediaria' => 'livro_editora',
            'chave_exportada' => 'editora_codigo',
            'campos_relacionamento' => ['editora_livro_codigo' => 'livro_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'livro',
            'objeto' => 'livro',
            'alias' => 'livros'
        ];

        $va_relacionamentos['editora_localidade_codigo'] = [
            ['editora_localidade_codigo'],
            'tabela_intermediaria' => 'editora_localidade',
            'chave_exportada' => 'editora_codigo',
            'campos_relacionamento' => ['editora_localidade_codigo' => 'localidade_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'localidade',
            'objeto' => 'localidade',
            'alias' => 'localidades'
        ];

        return $va_relacionamentos;
    }

    public function get_campo_autocomplete($ps_campo_nome, $ps_campo_codigo = '')
    {
        return [
            "html_combo_input",
            "nome" => $ps_campo_nome,
            "label" => "Selecionar",
            "objeto" => "editora",
            "atributos" => [
                $ps_campo_codigo == '' ? "editora_codigo" : $ps_campo_codigo,
                "entidade_nome" => ["hierarquia" => "entidade_principal_codigo", "sentido" => "inverso"]
            ],
            "dependencia" =>
                [
                    "campo" => "entidade_codigo_0_entidade_nome",
                    "atributo" => "entidade_codigo_0_entidade_nome"
                ]
        ];
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ['entidade_nome', 'entidade_codigo'],
            "label" => "Nome",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "foco" => true
        ];

        $va_campos_edicao["editora_localidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["editora_localidade", "editora_localidade_codigo"],
            "label" => "Local",
            "objeto" => "localidade",
            "atributos" => ["localidade_codigo", "localidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "localidade_nome",
            "permitir_cadastro" => true,
            "campo_salvar" => "localidade_nome",
            "visualizacao" => "lista",
        ];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["editora_codigo"] = ["nome" => "editora_codigo", "exibir" => false];
        $va_campos_visualizacao["entidade_codigo"] = ["nome" => "entidade_codigo"];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, parent::get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["entidade_nome" => "Nome"];

        $va_campos_visualizacao["editora_localidade_codigo"] = [
            "nome" => "editora_localidade_codigo",
            "formato" => ["campo" => "localidade_nome", "link" => ["objeto" => "localidade", "codigo" => "localidade_codigo"]]
        ];

        $va_campos_visualizacao_nav = array_merge($va_campos_visualizacao, parent::get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_nav;
        $this->visualizacoes["navegacao"]["order_by"] = ["entidade_nome" => "Nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>