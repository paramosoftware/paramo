<?php

class area_conhecimento extends objeto_base
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
        return "area_conhecimento";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['area_conhecimento_codigo'] = [
            'area_conhecimento_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['area_conhecimento_nome'] = [
            'area_conhecimento_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['area_conhecimento_livro_codigo'] = [
            ['area_conhecimento_livro_codigo'],
            'tabela_intermediaria' => 'livro_area_conhecimento',
            'chave_exportada' => 'area_conhecimento_codigo',
            'campos_relacionamento' => ['area_conhecimento_livro_codigo' => 'livro_codigo'],
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

        $va_campos_edicao["area_conhecimento_nome"] = [
            "html_text_input",
            "nome" => "area_conhecimento_nome",
            "label" => "Nome", "foco" => true
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["area_conhecimento_nome"] = [
            "html_text_input",
            "nome" => "area_conhecimento_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["area_conhecimento_codigo"] = ["nome" => "area_conhecimento_codigo", "exibir" => false];
        $va_campos_visualizacao["area_conhecimento_nome"] = ["nome" => "area_conhecimento_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["area_conhecimento_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["area_conhecimento_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "area_conhecimento_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "area_conhecimento_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>