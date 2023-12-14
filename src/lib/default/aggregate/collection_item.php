<?php

class collection_item extends item_acervo
{

function __construct() 
{
    $this->objetos = ["documento", "livro", "objeto", "entrevista"];
    $this->inicializar_visualizacoes();
}

public function inicializar_visualizacoes()
{
    parent::inicializar_visualizacoes();
}

}

?>