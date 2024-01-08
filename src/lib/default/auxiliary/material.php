<?php

class material extends objeto_base
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
        return "material";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['material_codigo'] = [
            'material_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['material_nome'] = [
            'material_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['material_descricao'] = [
            'material_descricao',
            'coluna_tabela' => 'descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['material_objeto_codigo'] = [
            'material_objeto_codigo',
            'tabela_intermediaria' => 'objeto_material',
            'chave_exportada' => 'material_codigo',
            'campos_relacionamento' => [
                'material_objeto_codigo' => 'objeto_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'objeto',
            'objeto' => 'objeto',
            'alias' => 'objetos'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["material_nome"] = [
            "html_text_input",
            "nome" => "material_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["material_descricao"] = [
            "html_text_input",
            "nome" => "material_descricao",
            "label" => "Descrição",
            "numero_linhas" => 8
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["material_nome"] = [
            "html_text_input",
            "nome" => "material_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["material_descricao"] = [
            "html_text_input",
            "nome" => "material_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["material_codigo"] = ["nome" => "material_codigo", "exibir" => false];
        $va_campos_visualizacao["material_nome"] = ["nome" => "material_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["material_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["material_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "material_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $va_campos_visualizacao["material_descricao"] = ["nome" => "material_descricao"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "material_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>