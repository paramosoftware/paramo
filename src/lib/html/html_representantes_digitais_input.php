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

    $vn_objeto_codigo = "";
    if ($pa_valores_form[$vs_tela . "_codigo"])
        $vn_objeto_codigo = $pa_valores_form[$vs_tela . "_codigo"];

    // Vou tentar recuperar aqui o label identificador do objeto
    ////////////////////////////////////////////////////////////

    $va_campos_objeto = $vo_objeto->get_visualizacao("navegacao");
    $vs_label_objeto = "";

    if (isset($va_campos_objeto["ordem_campos"]))
    {
        foreach ($va_campos_objeto["ordem_campos"] as $vs_key_campo => $va_campo)
        {
            if (isset($va_campo["main_field"]))
            {
                $vs_label_objeto = ler_valor1($vs_key_campo, $pa_valores_form, $va_campo);
            }
        }
    }

    require dirname(__FILE__)."/../../../app/components/campo_representantes_digitais.php";
}

}