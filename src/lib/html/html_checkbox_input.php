<?php
#[\AllowDynamicProperties]
class html_checkbox_input extends html_input
{

public function build(&$pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();
    $vs_ui_element = $this->get_ui_element();

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);
    
    $vb_campo_tem_valor = false;
    $vb_valor_campo = 0;
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]))
    {
        $vb_campo_tem_valor = true;
        $vb_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo];
    }

    $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo] = $vb_valor_campo;

    if ( isset($pa_parametros_campo["valor_padrao"]) && (!$vb_campo_tem_valor) )
        $vb_valor_campo = $pa_parametros_campo["valor_padrao"];

    require dirname(__FILE__) . "/../../../app/components/campo_checkbox.php";
}

}