<?php

class formato_material extends objeto_base
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
        return "formato_material";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['formato_material_codigo'] = [
            'formato_material_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['formato_material_nome'] = [
            'formato_material_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['formato_material_descricao'] = [
            'formato_material_descricao',
            'coluna_tabela' => 'Descricao',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['formato_material_livro_codigo'] = [
            ['formato_material_livro_codigo'],
            'tabela_intermediaria' => 'livro_formato_material',
            'chave_exportada' => 'formato_material_codigo',
            'campos_relacionamento' => [
                'formato_material_livro_codigo' => 'livro_codigo'
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'livro',
            'objeto' => 'livro',
            'alias' => 'livros'
        ];

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["formato_material_nome"] = ["html_text_input", "nome" => "formato_material_nome", "label" => "Nome", "foco" => true];
        $va_campos_edicao["formato_material_descricao"] = ["html_text_input", "nome" => "formato_material_descricao", "label" => "Definição", "numero_linhas" => 8];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["formato_material_nome"] = [
            "html_text_input",
            "nome" => "formato_material_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        $va_filtros_navegacao["formato_material_descricao"] = [
            "html_text_input",
            "nome" => "formato_material_descricao",
            "label" => "Descrição",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["formato_material_codigo"] = ["nome" => "formato_material_codigo", "exibir" => false];
        $va_campos_visualizacao["formato_material_nome"] = ["nome" => "formato_material_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["formato_material_nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["formato_material_nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "formato_material_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "formato_material_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>