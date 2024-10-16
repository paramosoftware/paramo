<?php

#[\AllowDynamicProperties]
class html_autocomplete extends html_input
{

public function build(&$pa_valores_form=null, $pa_parametros_campo=array(), $pa_recursos_sistema_permissao_edicao=array())
{
    $vs_tela = $this->get_tela();
    $vs_modo = $this->get_modo_form();
    $va_valor_campo = $pa_valores_form;

    $vb_marcar_sem_valor = false;
    $vb_marcar_com_valor = false;

    if (isset($pa_valores_form[$pa_parametros_campo["nome"][1] . "_sem_valor"]))
        $vb_marcar_sem_valor = true;
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"][1] . "_com_valor"]))
        $vb_marcar_com_valor = true;
    
    if ($vb_marcar_sem_valor || $vb_marcar_com_valor)
        $pa_parametros_campo["desabilitar"] = true;

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    if (isset($pa_parametros_campo["exibir_quando_preenchido"]))
        $vb_pode_exibir = $vb_pode_exibir 
            && ((isset($va_valor_campo[$pa_parametros_campo["nome"][1]]) && $va_valor_campo[$pa_parametros_campo["nome"][1]] != "") || $vb_marcar_sem_valor || $vb_marcar_com_valor)
            && $pa_parametros_campo["exibir_quando_preenchido"];

    $vb_pode_editar = 0;
    if (in_array("_all_", $pa_recursos_sistema_permissao_edicao) || (isset($pa_parametros_campo["objeto"]) && in_array($pa_parametros_campo["objeto"], $pa_recursos_sistema_permissao_edicao)))
        $vb_pode_editar = 1;

    require dirname(__FILE__) . "/../../../app/components/campo_autocomplete.php";

    if ($vn_valor_campo_codigo != "")
    {
        if (isset($pa_parametros_campo["conectar"]))
        {
            foreach($pa_parametros_campo["conectar"] as $v_conectar)
            {
                if (!isset($pa_valores_form[$v_conectar["atributo"]]) || is_array($pa_valores_form[$v_conectar["atributo"]]))
                    $pa_valores_form[$v_conectar["atributo"]] = $vn_valor_campo_codigo;
            }
        }

        //$pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo] = $vs_valor_campo;
    }
}

public function validar_valores($pa_valores_form=array(), $pa_parametros_campo=array())
{
    if (isset($pa_valores_form[$pa_parametros_campo["nome"][1]]))
    {
        $vs_valor_campo = trim($pa_valores_form[$pa_parametros_campo["nome"][1]]);

        if ( isset($pa_parametros_campo["obrigatorio"]) && $pa_parametros_campo["obrigatorio"] )
        {
            if ( 
                (isset($pa_parametros_campo["sugerir_valores"]) && !$pa_parametros_campo["sugerir_valores"])
                ||
                (isset($pa_parametros_campo["permitir_entrada_avulsa"]) && $pa_parametros_campo["permitir_entrada_avulsa"])
            )
            {
                if (isset($pa_valores_form[$pa_parametros_campo["nome"][0]]))
                {
                    $vs_texto_campo = trim($pa_valores_form[$pa_parametros_campo["nome"][0]]);

                    if ($vs_texto_campo == "")
                        return 'O campo "' . $pa_parametros_campo["label"] . '" é de preenchimento obrigatório!';
                }
            }
            elseif ($vs_valor_campo == "")
                return 'O campo "' . $pa_parametros_campo["label"] . '" é de preenchimento obrigatório!';
        }
    }

    return true;
}

}