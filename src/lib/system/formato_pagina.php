<?php

class formato_pagina extends objeto_base
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
    return "formato_pagina";
}

public function inicializar_chave_primaria()
{
    return $va_chave_primaria['formato_pagina_codigo'] = [
        'formato_pagina_codigo', 
        'coluna_tabela' => 'Codigo',
        'tipo_dado' => 'i'
    ];
}

public function inicializar_atributos()
{
    $va_atributos = array();

    $va_atributos['formato_pagina_nome'] = [
        'formato_pagina_nome', 
        'coluna_tabela' => 'nome', 
        'tipo_dado' => 's'
    ];

    $va_atributos['formato_pagina_altura'] = [
        'formato_pagina_altura', 
        'coluna_tabela' => 'altura', 
        'tipo_dado' => 'd'
    ];

    $va_atributos['formato_pagina_largura'] = [
        'formato_pagina_largura', 
        'coluna_tabela' => 'largura', 
        'tipo_dado' => 'd'
    ];

    return $va_atributos;
}

public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
{
    $va_relacionamentos = array();

    $va_relacionamentos['formato_pagina_pagina_etiquetas_codigo'] = [ 
        ['formato_pagina_pagina_etiquetas_codigo'], 
        'tabela_intermediaria' => 'pagina_etiquetas', 
        'chave_exportada' => 'formato_pagina_codigo', 
        'campos_relacionamento' => [
            'formato_pagina_pagina_etiquetas_codigo' => [
                ['codigo'], 
                "atributo" => "pagina_etiquetas_codigo"
            ]
        ], 
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'pagina_etiquetas',
        'objeto' => 'pagina_etiquetas',
        'tipo' => '1n',
        'alias' => "páginas de etiquetas"
    ];
    
    return $va_relacionamentos;
}

public function inicializar_campos_edicao()
{
    $va_campos_edicao = array();

    $va_campos_edicao["formato_pagina_nome"] = [
        "html_text_input", 
        "nome" => "formato_pagina_nome", 
        "label" => "Nome", 
        "foco" => true,
        "obrigatorio" => true
    ];

    $va_campos_edicao["formato_pagina_altura"] = [
        "html_text_input", 
        "nome" => "formato_pagina_altura", 
        "label" => "Altura (mm)",
        "obrigatorio" => true
    ];

    $va_campos_edicao["formato_pagina_largura"] = [
        "html_text_input", 
        "nome" => "formato_pagina_largura", 
        "label" => "Largura (mm)",
        "obrigatorio" => true
    ];

    return $va_campos_edicao;
}

public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
{
    $va_filtros_navegacao = array();

    $va_filtros_navegacao["formato_pagina_nome"] = [
        "html_text_input",
        "nome" => "formato_pagina_nome",
        "label" => "Nome",
        "operador_filtro" => "LIKE",
        "foco" => true
    ];

    return $va_filtros_navegacao;
}

public function inicializar_visualizacoes()
{
    $va_campos_visualizacao = array();
    $va_campos_visualizacao["formato_pagina_codigo"] = ["nome" => "formato_pagina_codigo", "exibir" => false];
    $va_campos_visualizacao["formato_pagina_nome"] = ["nome" => "formato_pagina_nome"];

    $va_campos_visualizacao["formato_pagina_altura"] = ["nome" => "formato_pagina_altura"];
    $va_campos_visualizacao["formato_pagina_largura"] = ["nome" => "formato_pagina_largura"];

    $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["lista"]["order_by"] = ["formato_pagina_nome" => "Nome"];

    $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["navegacao"]["order_by"] = ["formato_pagina_nome" => "Nome"];
    $this->visualizacoes["navegacao"]["ordem_campos"] = [
        "formato_pagina_nome" => ["label" => "Nome", "main_field" => true],
    ];

    $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["ficha"]["ordem_campos"] = [
        "formato_pagina_nome" => ["label" => "Nome", "main_field" => true],
        "formato_pagina_altura" => "Altura",
        "formato_pagina_largura" => "Largura"
    ];
}

}

?>