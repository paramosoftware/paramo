<?php
#[\AllowDynamicProperties]
class html_representantes_digitais_input extends html_input
{

public function build($pa_valores_form=array(), $pa_parametros_campo=array(), $pa_recursos_sistema_permissao_edicao=array())
{
    $vs_tela = $this->get_tela();

    $vo_objeto = new $vs_tela;
    $vs_chave_primaria_objeto = $vo_objeto->get_chave_primaria()[0];

    $va_valor_campo = array();
    if (isset($pa_valores_form[$pa_parametros_campo["nome"]]))
        $va_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"]];

    $vn_objeto_codigo = "";

    # Em alguns formulários o código chega como o array do registro lido
    # (ex. ["agrupamento_codigo" => 1, ...]); o componente imprime esse valor
    # no JavaScript, então precisa ser sempre um escalar.
    if (!empty($pa_valores_form[$vs_tela . "_codigo"]))
    {
        $vm_objeto_codigo = $pa_valores_form[$vs_tela . "_codigo"];

        if (is_array($vm_objeto_codigo))
            $vm_objeto_codigo = $vm_objeto_codigo[$vs_chave_primaria_objeto] ?? $vm_objeto_codigo[$vs_tela . "_codigo"] ?? "";

        $vn_objeto_codigo = $vm_objeto_codigo;
    }

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
    
    $vs_obj_edicao = $pa_parametros_campo["objeto"] ?? $pa_valores_form["_objeto"];
    $vb_pode_acessar_tudo = in_array("_all_", $pa_recursos_sistema_permissao_edicao);
    $vb_pode_acessar_objeto = in_array($vs_obj_edicao, $pa_recursos_sistema_permissao_edicao);
    $vb_pode_editar = $vb_pode_acessar_tudo || $vb_pode_acessar_objeto;

    require dirname(__FILE__)."/../../../app/components/campo_representantes_digitais.php";
}

}