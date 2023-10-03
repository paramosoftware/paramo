<?php

class incorporacao extends objeto_base
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
        return "incorporacao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['incorporacao_codigo'] = [
            'incorporacao_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['incorporacao_tipo_codigo'] = [
            'incorporacao_tipo_codigo',
            'coluna_tabela' => 'tipo_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'tipo_incorporacao'
        ];

        $va_atributos['incorporacao_data'] = [
            'incorporacao_data',
            'coluna_tabela' => ['data_inicial' => 'data'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['incorporacao_data_retorno_prevista'] = [
            'incorporacao_data_retorno_prevista',
            'coluna_tabela' => ['data_inicial' => 'data_prevista_retorno'],
            'tipo_dado' => 'dt'
        ];

        $va_atributos['incorporacao_descricao'] = [
            'incorporacao_data',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        $va_atributos['incorporacao_observacoes'] = [
            'incorporacao_data',
            'coluna_tabela' => 'observacoes',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['incorporacao_entidade_codigo'] = [
            [
                'incorporacao_entidade_codigo',
                'incorporacao_entidade_funcao_codigo'
            ],
            'tabela_intermediaria' => 'incorporacao_entidade',
            'chave_exportada' => 'incorporacao_codigo',
            'campos_relacionamento' => [
                'incorporacao_entidade_codigo' => 'entidade_codigo',
                'incorporacao_entidade_funcao_codigo' => 'funcao_codigo'
            ],
            'tipos_campos_relacionamento' => ['i', 'i'],
            'tabela_relacionamento' => 'entidade',
            'objeto' => 'entidade',
            'alias' => 'entidades'
        ];

        $va_relacionamentos['incorporacao_item_acervo_codigo'] = [
            [
                'incorporacao_item_acervo_codigo'
            ],
            'tabela_intermediaria' => 'incorporacao_item_acervo',
            'chave_exportada' => 'incorporacao_codigo',
            'campos_relacionamento' => [
                'incorporacao_item_acervo_codigo' => 'item_acervo_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens de acervo'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["incorporacao_tipo_codigo"] = [
            "html_combo_input",
            "nome" => "incorporacao_tipo_codigo",
            "label" => "Tipo",
            "objeto" => "tipo_incorporacao",
            "atributo" => "tipo_incorporacao_codigo",
            "atributos" => ["tipo_incorporacao_codigo", "tipo_incorporacao_nome"],
            "sem_valor" => false,
            "foco" => true
        ];

        $va_campos_edicao["incorporacao_data"] = [
            "html_date_input",
            "nome" => "incorporacao_data",
            "label" => "Data",
            "formato" => "dia"
        ];

        $va_campos_edicao["incorporacao_entidade_codigo"] = [
            "html_autocomplete",
            "nome" => ["incorporacao_entidade", "incorporacao_entidade_codigo"],
            "label" => "Fornecedor",
            "objeto" => "entidade",
            "atributos" => ["entidade_codigo", "entidade_nome"],
            "multiplos_valores" => true,
            "procurar_por" => "entidade_nome",
            "visualizacao" => "lista",
            "permitir_cadastro" => true,
            "campo_salvar" => "entidade_nome"
        ];

        $va_campos_edicao["incorporacao_descricao"] = [
            "html_text_input",
            "nome" => "incorporacao_descricao",
            "label" => "Detalhamento",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["incorporacao_observacoes"] = [
            "html_text_input",
            "nome" => "incorporacao_observacoes",
            "label" => "Observações",
            "numero_linhas" => 8
        ];

        $va_campos_edicao["incorporacao_item_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["incorporacao_item_acervo", "incorporacao_item_acervo_codigo"],
            "label" => "Itens do acervo",
            "objeto" => "item_acervo",
            "atributos" => ["item_acervo_codigo", "item_acervo_identificador"],
            "multiplos_valores" => true,
            "procurar_por" => "item_acervo_identificador",
            "visualizacao" => "lista"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["incorporacao_codigo"] = ["nome" => "incorporacao_codigo", "exibir" => false];

        $va_campos_visualizacao["incorporacao_tipo_codigo"] = [
            "nome" => "incorporacao_tipo_codigo",
            "formato" => ["campo" => "tipo_incorporacao_nome"]
        ];

        $va_campos_visualizacao["incorporacao_data"] = ["nome" => "incorporacao_data"];

        $va_campos_visualizacao["incorporacao_entidade_codigo"] = [
            "nome" => "incorporacao_entidade_codigo",
            "formato" => ["campo" => "entidade_nome"]
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;

        $va_campos_visualizacao["incorporacao_descricao"] = ["nome" => "incorporacao_descricao"];
        $va_campos_visualizacao["incorporacao_observacoes"] = ["nome" => "incorporacao_observacoes"];

        $va_campos_visualizacao["incorporacao_entidade_codigo"] = [
            "nome" => "incorporacao_entidade_codigo",
            "formato" => ["campo" => "entidade_nome"]
        ];

        $va_campos_visualizacao["incorporacao_item_acervo_codigo"] = [
            "nome" => "incorporacao_item_acervo_codigo",
            "formato" => ["campo" => "item_acervo_identificador"]
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

}

?>