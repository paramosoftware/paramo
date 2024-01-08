<?php

class forma extends objeto_base
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
        return "forma";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['forma_codigo'] = [
            'forma_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['forma_nome'] = [
            'forma_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['forma_descricao'] = [
            'forma_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['forma_documento_codigo'] = [
            'forma_documento_codigo',
            'tabela_intermediaria' => 'documento_forma',
            'chave_exportada' => 'forma_codigo',
            'campos_relacionamento' => ['forma_documento_codigo' => 'documento_codigo'],
            'tipos_campos_relacionamento' => 'i',
            'tabela_relacionamento' => 'documento',
            'objeto' => 'documento',
            'alias' => 'documentos'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["forma_nome"] = [
            "html_text_input",
            "nome" => "forma_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["forma_descricao"] = [
            "html_text_input",
            "nome" => "forma_descricao",
            "label" => "Definição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["forma_nome"] = [
            "html_text_input",
            "nome" => "forma_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["forma_codigo"] = ["nome" => "forma_codigo", "exibir" => false];
        $va_campos_visualizacao["forma_nome"] = ["nome" => "forma_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["forma_nome" => "nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["forma_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "forma_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["forma_descricao"] = ["nome" => "forma_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "forma_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>