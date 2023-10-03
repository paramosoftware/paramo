<?php

class html_preview_representantes_digitais extends html_input
{

public function build($pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();

    $va_valor_campo = array();
    if (isset($pa_valores_form[$pa_parametros_campo["nome"]]))
        $va_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"]];

    require dirname(__FILE__)."/../../../app/functions/campo_preview_representantes_digitais.php";
}

}