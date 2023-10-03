<?php

class assunto extends objeto_base
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
        return "assunto";
    }

    public function inicializar_chave_primaria()
    {
        return [
            'assunto_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['assunto_nome'] = [
            'assunto_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['assunto_descricao'] = [
            'assunto_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['assunto_item_acervo_codigo'] = [
            ['assunto_item_acervo_codigo'],
            'tabela_intermediaria' => 'item_acervo_assunto',
            'chave_exportada' => 'assunto_codigo',
            'campos_relacionamento' => ['assunto_item_acervo_codigo' => 'item_acervo_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens do acervo'
        ];

        $va_relacionamentos['assunto_agrupamento_codigo'] = [
            ['assunto_agrupamento_codigo'],
            'tabela_intermediaria' => 'agrupamento_assunto',
            'chave_exportada' => 'assunto_codigo',
            'campos_relacionamento' => ['assunto_agrupamento_codigo' => 'agrupamento_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'agrupamento',
            'objeto' => 'agrupamento',
            'alias' => 'agrupamentos relacionados'
        ];

        $va_relacionamentos['assunto_acervo_codigo'] = [
            ['assunto_acervo_codigo'],
            'tabela_intermediaria' => 'acervo_assunto',
            'chave_exportada' => 'assunto_codigo',
            'campos_relacionamento' => ['assunto_acervo_codigo' => 'acervo_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'alias' => 'acervos relacionados'
        ];

        $va_relacionamentos['assunto_colecao_codigo'] = [
            ['assunto_colecao_codigo'],
            'tabela_intermediaria' => 'colecao_assunto',
            'chave_exportada' => 'assunto_codigo',
            'campos_relacionamento' => ['assunto_colecao_codigo' => 'colecao_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'colecao',
            'objeto' => 'colecao',
            'alias' => 'coleções relacionadas'
        ];

        $va_relacionamentos['assunto_serie_codigo'] = [
            ['assunto_serie_codigo'],
            'tabela_intermediaria' => 'serie_assunto',
            'chave_exportada' => 'assunto_codigo',
            'campos_relacionamento' => ['assunto_serie_codigo' => 'serie_codigo'],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'serie',
            'objeto' => 'serie',
            'alias' => 'séries relacionadas'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["assunto_nome"] = [
            "html_text_input",
            "nome" => "assunto_nome",
            "label" => "Nome", "foco" => true
        ];

        $va_campos_edicao["assunto_descricao"] = [
            "html_text_input",
            "nome" => "assunto_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["assunto_nome"] = [
            "html_text_input",
            "nome" => "assunto_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["assunto_codigo"] = ["nome" => "assunto_codigo", "exibir" => false];
        $va_campos_visualizacao["assunto_nome"] = ["nome" => "assunto_nome"];
        $va_campos_visualizacao["assunto_descricao"] = ["nome" => "assunto_descricao"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["assunto_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["assunto_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "assunto_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "assunto_nome" => ["label" => "Nome", "main_field" => true
            ]
        ];
    }

}

?>