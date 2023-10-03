<?php
#[\AllowDynamicProperties]
class html_multi_itens_input extends html_input
{

public function build(&$pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    $va_valor_campo = array();
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]))
        $va_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo];

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    require dirname(__FILE__) . "/../../../app/components/campo_multi_itens.php";
}

}