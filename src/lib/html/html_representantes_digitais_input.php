<?php
#[\AllowDynamicProperties]
class html_representantes_digitais_input extends html_input
{

public function build($pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();

    $vo_objeto = new $vs_tela;
    $vs_chave_primaria_objeto = $vo_objeto->get_chave_primaria()[0];

    $va_valor_campo = array();
    if (isset($pa_valores_form[$pa_parametros_campo["nome"]]))
        $va_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"]];

    if (isset($pa_valores_form[$vs_tela . "_link_externo"]) && trim($pa_valores_form[$vs_tela . "_link_externo"]))
        $va_valor_campo[] = array("link_externo" => $pa_valores_form[$vs_tela . "_link_externo"]);

    $vn_objeto_codigo = "";
    if ($pa_valores_form[$vs_tela . "_codigo"])
        $vn_objeto_codigo = $pa_valores_form[$vs_tela . "_codigo"];

    require dirname(__FILE__)."/../../../app/components/campo_representantes_digitais.php";
}

}