<?php

class html_navegacao_paginas extends html_input
{

public function build($pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();
    $va_parametros_filtros_consulta = $pa_valores_form;

    require dirname(__FILE__) . "/../../../app/components//barra_paginacao.php";
}

}