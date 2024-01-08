<?php

class localidade extends objeto_base
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
    return "localidade";
}

public function inicializar_chave_primaria()
{
    return $va_chave_primaria['localidade_codigo'] = [
        'localidade_codigo', 
        'coluna_tabela' => 'Codigo',
        'tipo_dado' => 'i'
    ];
}

public function inicializar_atributos()
{
    $va_atributos = array();

    $va_atributos['localidade_nome'] = [
        'localidade_nome', 
        'coluna_tabela' => 'Nome', 
        'tipo_dado' => 's'
    ];

    return $va_atributos;
}

public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
{
    $va_relacionamentos = array();

    $va_relacionamentos['localidade_editora_codigo'] = [
        ['localidade_editora_codigo'],
        'tabela_intermediaria' => 'editora_localidade', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => ['localidade_editora_codigo' => 'editora_codigo'], 
        'tipos_campos_relacionamento' => ['i'], 
        'tabela_relacionamento' => 'editora', 
        'objeto' => 'editora',
        'alias' => 'editoras'
    ];    

    $va_relacionamentos['localidade_entidade_codigo'] = [
        ['localidade_entidade_codigo'],
        'tabela_intermediaria' => 'entidade_localidade', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => ['localidade_entidade_codigo' => 'entidade_codigo'], 
        'tipos_campos_relacionamento' => ['i'], 
        'tabela_relacionamento' => 'entidade',
        'objeto' => 'entidade',
        'alias' => 'entidades'
    ];

    $va_relacionamentos['localidade_item_acervo_codigo'] = [
        ['localidade_item_acervo_codigo'],
        'tabela_intermediaria' => 'item_acervo_localidade', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => ['localidade_item_acervo_codigo' => 'item_acervo_codigo'], 
        'tipos_campos_relacionamento' => ['i'], 
        'tabela_relacionamento' => 'item_acervo', 
        'objeto' => 'item_acervo',
        'alias' => 'itens de acervos'
    ];

    $va_relacionamentos['localidade_nascimento_entidade_codigo'] = [
        'localidade_nascimento_entidade_codigo',
        'tabela_intermediaria' => 'entidade', 
        'chave_exportada' => 'local_nascimento_codigo', 
        'campos_relacionamento' => [
            'localidade_nascimento_entidade_codigo' => [
                ['codigo'],
                "atributo" => "entidade_codigo"
            ]
        ],
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'entidade',
        'objeto' => 'entidade',
        'tipo' => '1n',
        'alias' => 'entidades (local de nascimento)'
    ];

    $va_relacionamentos['localidade_morte_entidade_codigo'] = [
        'localidade_morte_entidade_codigo',
        'tabela_intermediaria' => 'entidade', 
        'chave_exportada' => 'local_morte_codigo', 
        'campos_relacionamento' => [
            'localidade_morte_entidade_codigo' => [
                ['codigo'],
                "atributo" => "entidade_codigo"
            ]
        ],
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'entidade',
        'objeto' => 'entidade',
        'tipo' => '1n',
        'alias' => 'entidades (local de morte)'
    ];
    
    $va_relacionamentos['localidade_evento_codigo'] = [
        'localidade_evento_codigo',
        'tabela_intermediaria' => 'evento', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => [
            'localidade_evento_codigo' => [
                ['codigo'],
                "atributo" => "evento_codigo"
            ]
        ],
        'tipos_campos_relacionamento' => ['i'],
        'objeto' => 'evento',
        'tipo' => '1n',
        'alias' => 'eventos'
    ];

    $va_relacionamentos['localidade_acervo_codigo'] = [ 
        ['localidade_acervo_codigo'], 
        'tabela_intermediaria' => 'acervo', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => [
            'localidade_acervo_codigo' => [
                ['codigo'], 
                "atributo" => "acervo_codigo"
            ]
        ], 
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'acervo', 
        'objeto' => 'acervo',
        'tipo' => '1n',
        'alias' => "acervos"
    ];

    $va_relacionamentos['localidade_endereco_entidade'] = [
        [
            'endereco_entidade_codigo',
            'endereco_logradouro',
            'endereco_bairro',
        ], 
        'tabela_intermediaria' => 'entidade_endereco', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => [
            'endereco_entidade_codigo' => [
                ['entidade_codigo'], 
                'objeto' => 'entidade'
            ],
            'endereco_logradouro' => 'logradouro',
            'endereco_bairro' => 'bairro',
        ],
        'tipos_campos_relacionamento' => ['i', 's', 's'], 
        'alias' => 'endereços de entidades'
    ];

    $va_relacionamentos['localidade_endereco_usuario'] = [
        [
            'endereco_usuario_codigo',
            'endereco_logradouro',
            'endereco_bairro',
        ], 
        'tabela_intermediaria' => 'usuario_endereco', 
        'chave_exportada' => 'localidade_codigo', 
        'campos_relacionamento' => [
            'endereco_usuario_codigo' => [
                ['usuario_codigo'], 
                'objeto' => 'usuario'
            ],
            'endereco_logradouro' => 'logradouro',
            'endereco_bairro' => 'bairro',
        ],
        'tipos_campos_relacionamento' => ['i', 's', 's'], 
        'alias' => 'endereços de usuários'
    ];

    return $va_relacionamentos;
}

public function inicializar_campos_edicao()
{
    $va_campos_edicao = array();

    $va_campos_edicao["localidade_nome"] = [
        "html_text_input", 
        "nome" => "localidade_nome", 
        "label" => "Nome", 
        "foco" => true
    ];

    $va_campos_edicao["localidade_item_acervo_codigo"] = [
        "html_autocomplete",
        "nome" => ['localidade_item_acervo', 'localidade_item_acervo_codigo'],
        "label" => "Itens relacionados", 
        "objeto" => "item_acervo", 
        "atributos" => ["item_acervo_codigo", "item_acervo_identificador"],
        "multiplos_valores" => true, 
        "procurar_por" => "item_acervo_identificador",
        "visualizacao" => "lista"
    ];

    $va_campos_edicao["localidade_entidade_codigo"] = [
        "html_autocomplete",
        "nome" => ['localidade_entidade', 'localidade_entidade_codigo'],
        "label" => "Entidades relacionadas", 
        "objeto" => "entidade", 
        "atributos" => ["entidade_codigo", "entidade_nome"],
        "multiplos_valores" => true, 
        "procurar_por" => "entidade_nome", 
        "visualizacao" => "lista"
    ];

    return $va_campos_edicao;
}

public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
{
    $va_filtros_navegacao = array();

    $va_filtros_navegacao["localidade_nome"] = [
        "html_text_input",
        "nome" => "localidade_nome",
        "label" => "Nome",
        "operador_filtro" => "LIKE"
    ];

    return $va_filtros_navegacao;
}

public function inicializar_visualizacoes()
{
    $va_campos_visualizacao = array();
    parent::inicializar_visualizacoes();

    $va_campos_visualizacao["localidade_codigo"] = ["nome" => "localidade_codigo", "exibir" => false];
    $va_campos_visualizacao["localidade_nome"] = ["nome" => "localidade_nome"];

    $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    $this->visualizacoes["lista"]["order_by"] = ["localidade_nome" => "Nome"];

    $this->visualizacoes["navegacao"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));
    $this->visualizacoes["navegacao"]["order_by"] = ["localidade_nome" => "Nome"];
    $this->visualizacoes["navegacao"]["ordem_campos"] = [
        "localidade_nome" => ["label" => "Nome", "main_field" => true],
    ];

    $va_campos_visualizacao["localidade_item_acervo_codigo"] = [
        "nome" => "localidade_item_acervo_codigo", 
        "formato" => ["campo" => "item_acervo_titulo"]
    ];

    $va_campos_visualizacao["localidade_entidade_codigo"] = [
        "nome" => "localidade_entidade_codigo", 
        "formato" => ["campo" => "entidade_nome"]
    ];

    $this->visualizacoes["ficha"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));
    $this->visualizacoes["ficha"]["ordem_campos"] = [
        "localidade_nome" => ["label" => "Nome", "main_field" => true],
    ];
}

}

?>