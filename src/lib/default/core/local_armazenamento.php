<?php

class local_armazenamento extends objeto_base
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
    return "local_armazenamento";
}

public function inicializar_chave_primaria()
{
    return $va_chave_primaria['local_armazenamento_codigo'] = [
        'local_armazenamento_codigo', 
        'coluna_tabela' => 'codigo',
        'tipo_dado' => 'i'
    ];
}

public function inicializar_atributos()
{
    $va_atributos = array();

    $va_atributos['local_armazenamento_nome'] = [
        'local_armazenamento_nome', 
        'coluna_tabela' => 'nome', 
        'tipo_dado' => 's'
    ];

    $va_atributos['local_armazenamento_descricao'] = [
        'local_armazenamento_descricao', 
        'coluna_tabela' => 'descricao', 
        'tipo_dado' => 's'
    ];

    $va_atributos['local_armazenamento_instituicao_codigo'] = [
        'local_armazenamento_instituicao_codigo', 
        'coluna_tabela' => 'instituicao_codigo', 
        'tipo_dado' => 'i',
        'objeto' => 'instituicao'
    ];

    return $va_atributos;
}

public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
{
    $va_relacionamentos = array();

    $va_relacionamentos['local_armazenamento_documento_codigo'] = [ 
        ['local_armazenamento_documento_codigo'], 
        'tabela_intermediaria' => 'documento', 
        'chave_exportada' => 'local_armazenamento_codigo', 
        'campos_relacionamento' => [
            'local_armazenamento_documento_codigo' => [
                ['codigo'],
                "atributo" => "documento_codigo"
            ]
        ],
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'documento',
        'objeto' => 'documento',
        'tipo' => '1n',
        'alias' => 'documentos'
    ];

    return $va_relacionamentos;
}

public function inicializar_campos_edicao()
{
    $va_campos_edicao = array();

    $va_campos_edicao["local_armazenamento_instituicao_codigo"] = [
        "html_combo_input", 
        "nome" => "local_armazenamento_instituicao_codigo", 
        "label" =>"Instituição", 
        "objeto" => "instituicao",
        "atributos" => ["instituicao_codigo", "instituicao_nome"],
        "atributo" => "instituicao_codigo",
        "atributo_obrigatorio" => true,
        "sem_valor" => false, 
        "foco" => true
    ];

    $va_campos_edicao["local_armazenamento_nome"] = [
        "html_text_input",
        "nome" => "local_armazenamento_nome",
        "label" => "Nome"
    ];
    
    $va_campos_edicao["local_armazenamento_descricao"] = [
        "html_text_input", 
        "nome" => "local_armazenamento_descricao", 
        "label" => "Descrição", 
        "numero_linhas" => 8
    ];

    return $va_campos_edicao;
}

public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
{
    $va_filtros_navegacao = array();

    $va_filtros_navegacao["local_armazenamento_nome"] = [
        "html_text_input",
        "nome" => "local_armazenamento_nome",
        "label" => "Nome",
        "operador_filtro" => "LIKE"
    ];

    return $va_filtros_navegacao;
}

public function inicializar_visualizacoes()
{
    $va_campos_visualizacao = array();
    $va_campos_visualizacao["local_armazenamento_codigo"] = ["nome" => "local_armazenamento_codigo", "exibir" => false];
    $va_campos_visualizacao["local_armazenamento_nome"] = ["nome" => "local_armazenamento_nome"];
    $va_campos_visualizacao["local_armazenamento_instituicao_codigo"] = ["nome" => "local_armazenamento_instituicao_codigo"];
        
    $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["lista"]["order_by"] = ["local_armazenamento_nome" => "Nome"];

    $va_campos_visualizacao["local_armazenamento_descricao"] = ["nome" => "local_armazenamento_nome"];

    $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["navegacao"]["order_by"] = ["local_armazenamento_nome" => "Nome"];
    $this->visualizacoes["navegacao"]["ordem_campos"] = [
        "local_armazenamento_nome" => ["label" => "Nome", "main_field" => true],
    ];

    $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["ficha"]["ordem_campos"] = [
        "local_armazenamento_nome" => ["label" => "Nome", "main_field" => true],
        "local_armazenamento_descricao" => ["label" => "Descrição", "descriptive_field" => true]
    ];
}


}

?>