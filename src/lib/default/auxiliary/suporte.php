<?php

class suporte extends objeto_base
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
        return "suporte";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['suporte_codigo'] = [
            'suporte_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['suporte_nome'] = [
            'suporte_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['suporte_descricao'] = [
            'suporte_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['suporte_item_acervo_codigo'] = [
            'suporte_item_acervo_codigo',
            'tabela_intermediaria' => 'item_acervo_suporte',
            'chave_exportada' => 'suporte_codigo',
            'campos_relacionamento' => ['suporte_item_acervo_codigo' => 'item_acervo_codigo'],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens do acervo'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["suporte_nome"] = [
            "html_text_input",
            "nome" => "suporte_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["suporte_descricao"] = [
            "html_text_input",
            "nome" => "suporte_descricao",
            "label" => "Definição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["suporte_nome"] = ["html_text_input", "nome" => "suporte_nome", "label" => "Nome", "operador_filtro" => "LIKE"];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["suporte_codigo"] = [
            "nome" => "suporte_codigo",
            "exibir" => false
        ];
        $va_campos_visualizacao["suporte_nome"] = [
            "nome" => "suporte_nome"
        ];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["suporte_nome" => "nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["suporte_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "suporte_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["suporte_descricao"] = ["nome" => "suporte_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "suporte_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>