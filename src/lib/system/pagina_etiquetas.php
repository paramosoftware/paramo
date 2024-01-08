<?php

class pagina_etiquetas extends objeto_base
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
        return "pagina_etiquetas";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['pagina_etiquetas_codigo'] = [
            'pagina_etiquetas_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['pagina_etiquetas_nome'] = [
            'pagina_etiquetas_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['pagina_etiquetas_formato_codigo'] = [
            'pagina_etiquetas_formato_codigo',
            'coluna_tabela' => 'formato_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'formato_pagina'
        ];

        $va_atributos['pagina_etiquetas_margem_superior'] = [
            'pagina_etiquetas_margem_superior',
            'coluna_tabela' => 'margem_superior',
            'tipo_dado' => 'd'
        ];

        $va_atributos['pagina_etiquetas_margem_esquerda'] = [
            'pagina_etiquetas_margem_esquerda',
            'coluna_tabela' => 'margem_esquerda',
            'tipo_dado' => 'd'
        ];

        $va_atributos['pagina_etiquetas_altura_etiqueta'] = [
            'pagina_etiquetas_altura_etiqueta',
            'coluna_tabela' => 'altura_etiqueta',
            'tipo_dado' => 'd'
        ];

        $va_atributos['pagina_etiquetas_largura_etiqueta'] = [
            'pagina_etiquetas_largura_etiqueta',
            'coluna_tabela' => 'largura_etiqueta',
            'tipo_dado' => 'd'
        ];

        $va_atributos['pagina_etiquetas_intervalo_etiquetas'] = [
            'pagina_etiquetas_intervalo_etiquetas',
            'coluna_tabela' => 'intervalo_etiquetas',
            'tipo_dado' => 'd'
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

        $va_campos_edicao["pagina_etiquetas_nome"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_nome",
            "label" => "Nome",
            "foco" => true
        ];

        $va_campos_edicao["pagina_etiquetas_formato_codigo"] = [
            "html_combo_input",
            "nome" => "pagina_etiquetas_formato_codigo",
            "label" => "Formato",
            "objeto" => "formato_pagina",
            "atributo" => "formato_pagina_codigo",
            "atributos" => ["formato_pagina_codigo", "formato_pagina_nome"],
            "sem_valor" => false,
        ];

        $va_campos_edicao["pagina_etiquetas_margem_superior"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_margem_superior",
            "label" => "Margem superior (mm)",
        ];

        $va_campos_edicao["pagina_etiquetas_margem_esquerda"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_margem_esquerda",
            "label" => "Margem esquerda (mm)"
        ];

        $va_campos_edicao["pagina_etiquetas_altura_etiqueta"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_altura_etiqueta",
            "label" => "Altura da etiqueta (mm)",
            "obrigatorio" => true
        ];

        $va_campos_edicao["pagina_etiquetas_largura_etiqueta"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_largura_etiqueta",
            "label" => "Largura da etiqueta (mm)",
            "obrigatorio" => true
        ];

        $va_campos_edicao["pagina_etiquetas_intervalo_etiquetas"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_intervalo_etiquetas",
            "label" => "Intervalo horizontal entre as etiquetas (mm)",
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["pagina_etiquetas_nome"] = [
            "html_text_input",
            "nome" => "pagina_etiquetas_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "foco" => true
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["pagina_etiquetas_codigo"] = ["nome" => "pagina_etiquetas_codigo", "exibir" => false];
        $va_campos_visualizacao["pagina_etiquetas_nome"] = ["nome" => "pagina_etiquetas_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["pagina_etiquetas_nome" => "Nome"];

        $va_campos_visualizacao["pagina_etiquetas_formato_codigo"] = ["nome" => "pagina_etiquetas_formato_codigo"];

        $va_campos_visualizacao["pagina_etiquetas_margem_superior"] = ["nome" => "pagina_etiquetas_margem_superior"];
        $va_campos_visualizacao["pagina_etiquetas_margem_esquerda"] = ["nome" => "pagina_etiquetas_margem_esquerda"];
        $va_campos_visualizacao["pagina_etiquetas_altura_etiqueta"] = ["nome" => "pagina_etiquetas_altura_etiqueta"];
        $va_campos_visualizacao["pagina_etiquetas_largura_etiqueta"] = ["nome" => "pagina_etiquetas_largura_etiqueta"];
        $va_campos_visualizacao["pagina_etiquetas_intervalo_etiquetas"] = ["nome" => "pagina_etiquetas_intervalo_etiquetas"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["pagina_etiquetas_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "pagina_etiquetas_nome" => ["label" => "Nome", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "pagina_etiquetas_nome" => ["label" => "Nome", "main_field" => true],
        ];
    }

}

?>