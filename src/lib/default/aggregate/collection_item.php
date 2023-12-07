<?php

class collection_item extends item_acervo
{

function __construct() 
{
    $this->objetos = ["documento", "livro", "objeto", "entrevista"];
    $this->inicializar_visualizacoes();

    $this->chave_primaria = $this->inicializar_chave_primaria();

}

public function inicializar_tabela_banco()
{
    return "";
}

public function inicializar_chave_primaria()
{
    return $va_chave_primaria['item_acervo_codigo'] = [
        'item_acervo_codigo', 
    ];
}

public function inicializar_atributos()
{
    $va_atributos = array();
    return $va_atributos;
}

public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
{
    $va_relacionamentos = array();
    return $va_relacionamentos;
}

public function inicializar_visualizacoes()
{
    parent::inicializar_visualizacoes();
    $this->visualizacoes["mista"]["campos"] = parent::get_campos_visualizacao("navegacao");
    
    $this->visualizacoes["lista"]["campos"]["texto_codigo"] = ["nome" => "texto_codigo", "exibir" => false];
    $this->visualizacoes["lista"]["campos"]["item_acervo_identificador"] = ["nome" => "item_acervo_identificador"];
    //$this->visualizacoes["lista"]["order_by"] = ["item_acervo_codigo" => ""];

    $this->visualizacoes["carrossel"]["campos"]["item_acervo_codigo"] = ["nome" => "item_acervo_codigo", "exibir" => false];
    $this->visualizacoes["carrossel"]["campos"]["item_acervo_identificador"] = ["nome" => "item_acervo_identificador"];
    $this->visualizacoes["carrossel"]["campos"]["texto_codigo"] = ["nome" => "texto_codigo", "exibir" => false];
    $this->visualizacoes["carrossel"]["campos"]["texto_data"] = ["nome" => "texto_data", "formato" => ["data" => "completo"]];
    
    $this->visualizacoes["carrossel"]["campos"]["item_acervo_acervo_codigo_0_acervo_setor_sistema_codigo_0_setor_sistema_codigo"] = [
        "nome" => "item_acervo_acervo_codigo_0_acervo_setor_sistema_codigo_0_setor_sistema_codigo",
        "exibir" => false
    ];

    $this->visualizacoes["carrossel"]["campos"]["item_acervo_acervo_codigo_0_acervo_setor_sistema_codigo_0_setor_sistema_nome"] = [
        "nome" => "item_acervo_acervo_codigo_0_acervo_setor_sistema_codigo_0_setor_sistema_nome"
    ];

    $this->visualizacoes["carrossel"]["campos"]["representante_digital_codigo"] = [
        "nome" => "representante_digital_codigo",
        "formato" => ["campo" => "representante_digital_path"],
        "exibir" => false
    ];
    
    $this->visualizacoes["carrossel"]["ordem_campos"] = [
        "item_acervo_acervo_codigo_0_acervo_setor_sistema_codigo_0_setor_sistema_codigo" => "setor_codigo",
        "item_acervo_acervo_codigo_0_acervo_setor_sistema_codigo_0_setor_sistema_nome" => "Setor",
        "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
        "texto_dados_textuais_0_texto_titulo" => ["label" => "Título", "main_field" => true],
        "item_acervo_acervo_codigo" => "Acervo",
        "texto_entidade_codigo" => "Autor",
        "texto_data" => "Data",
        "texto_codigo" => "texto_codigo",
        "representante_digital_codigo" => "representante_digital_codigo"
    ];

    $this->visualizacoes["public-aggregate"]["ordem_campos"] =
    [
        "texto_dados_textuais_0_item_acervo_titulo" => ["label" => "Título", "main_field" => true],
        "item_acervo_identificador" => ["label" => "Identificador", "id_field" => true],
    ];
}

}

?>