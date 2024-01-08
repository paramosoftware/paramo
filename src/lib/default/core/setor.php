<?php

class setor extends objeto_base
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
        return "setor_instituicao";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['setor_codigo'] = [
            'setor_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['setor_nome'] = [
            'setor_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['setor_instituicao_codigo'] = [
            'setor_instituicao_codigo',
            'coluna_tabela' => 'instituicao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'instituicao'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();
        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["setor_nome"] = [
            "html_text_input",
            "nome" => "setor_nome",
            "label" => "Nome",
            "Setor",
            "foco" => true]
        ;

        $va_campos_edicao["setor_instituicao_codigo"] = [
            "html_combo_input",
            "nome" => "setor_instituicao_codigo",
            "label" => "Instituição",
            "objeto" => "Instituicao",
            "atributos" => ["instituicao_codigo", "instituicao_nome"],
            "atributo" => "instituicao_codigo",
            "sem_valor" => false
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["setor_nome"] = [
            "html_text_input",
            "nome" => "setor_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();

        $va_campos_visualizacao["setor_codigo"] = ["nome" => "setor_codigo", "exibir" => false];
        $va_campos_visualizacao["setor_nome"] = ["nome" => "setor_nome"];

        $va_campos_visualizacao_lista = $va_campos_visualizacao;

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["setor_nome" => "Nome"];

        $va_campos_visualizacao["setor_instituicao_codigo"] = ["nome" => "setor_instituicao_codigo",
            "formato" => ["campo" => "instituicao_nome"]
        ];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["setor_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "setor_nome" => ["label" => "Título", "main_field" => true],
            "setor_instituicao_codigo" => "Instituição",
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "setor_nome" => ["label" => "Título", "main_field" => true],
            "setor_instituicao_codigo" => "Instituição",
        ];

        $this->visualizacoes["publica"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["publica"]["order_by"] = ["setor_nome" => ""];
        $this->visualizacoes["publica"]["ordem_campos"] = [
            "setor_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>