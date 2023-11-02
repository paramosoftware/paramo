<?php

#[\AllowDynamicProperties]
class html_text_input extends html_input
{

public function preencher($pa_valores_form, $pa_parametros_campo)
{
    $vs_novo_valor_serial = "";
    if (isset($pa_parametros_campo["serial"]["objeto"]))
    {
        $va_parametros_consulta = array();

        $vs_objeto_id = $pa_parametros_campo["serial"]["objeto"];
        $vo_objeto = new $vs_objeto_id("");

        if ( isset($pa_parametros_campo["serial"]["agrupado_por"]) && isset($pa_valores_form[$pa_parametros_campo["serial"]["agrupado_por"]]) )
        {
            $vs_atributo_nome = "";
            if (isset($pa_parametros_campo["campo_pai"]))
                $vs_atributo_nome = $pa_parametros_campo["campo_pai"] . "_0_" . $pa_parametros_campo["nome"];

            $va_parametros_consulta[$pa_parametros_campo["serial"]["agrupado_por"]] = $pa_valores_form[$pa_parametros_campo["serial"]["agrupado_por"]];

            $va_ultimo_registro = $vo_objeto->ler_lista($va_parametros_consulta, "navegacao", 1, 1, $vs_atributo_nome, "DESC");

            if (!count($va_ultimo_registro))
                $vs_novo_valor_serial = str_pad("1", 7, "0", STR_PAD_LEFT);
            else
            {
                $vs_novo_valor_serial = $this->ler_valor_textual($va_ultimo_registro, $vs_atributo_nome) + 1;
                $vs_novo_valor_serial = str_pad($vs_novo_valor_serial, 7, "0", STR_PAD_LEFT);
            }
        }
    }

    return $vs_novo_valor_serial;
}

public function build(&$pa_valores_form=array(), $pa_parametros_campo=array(), $pa_valores_form_portugues=array())
{
    $vs_tela = $this->get_tela();
    $vs_modo = $this->get_modo_form();
    $vs_ui_element = $this->get_ui_element();
    
    $vb_marcar_sem_valor = false;
    $vb_marcar_com_valor = false;

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    if (isset($pa_parametros_campo["escopo"]))
        $vs_escopo = $pa_parametros_campo["escopo"];

    // Só vai apresentar o valor do campo se não for um campo de valor
    // calculado pelo sistema e não for duplicação
    //////////////////////////////////////////////////////////////////
    
    $vs_valor_campo = "";

    if (!isset($pa_parametros_campo["automatico"]) || ($vs_modo != "duplicacao") )
    {
        $vs_valor_campo = $this->ler_valor_textual($pa_valores_form, $pa_parametros_campo["nome"] . $vs_sufixo_nome_campo);

        if ( (!$vs_valor_campo) && (isset($pa_parametros_campo["campo_correlato"])) )
            $vs_valor_campo = $this->ler_valor_textual($pa_valores_form, $pa_parametros_campo["campo_correlato"]["atributo"]);

        if ( (!$vs_valor_campo) && (isset($pa_parametros_campo["valor_padrao"])) )
        {
            $vs_valor_campo = $pa_parametros_campo["valor_padrao"];
            $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo] = $vs_valor_campo;
        }

        //if (!$vs_valor_campo)
            //$vs_valor_campo_portugues = $this->ler_valor_textual($pa_valores_form_portugues, $pa_parametros_campo["nome"] . $vs_sufixo_nome_campo);
    }

    if ( ($vs_valor_campo != "") && isset($pa_parametros_campo["padding"]))
        $vs_valor_campo = str_pad($vs_valor_campo, $pa_parametros_campo["padding"][1], $pa_parametros_campo["padding"][0], $pa_parametros_campo["padding"]["2"]);

    if ($vs_valor_campo == "")
        $vs_valor_campo = $this->preencher($pa_valores_form, $pa_parametros_campo);

    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_sem_valor"]))
        $vb_marcar_sem_valor = true;
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_com_valor"]))
        $vb_marcar_com_valor = true;
    
    if ($vb_marcar_sem_valor || $vb_marcar_com_valor)
        $pa_parametros_campo["desabilitar"] = true;

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo, $vs_sufixo_nome_campo);

    if (isset($pa_parametros_campo["exibir_quando_preenchido"]))
        $vb_pode_exibir = $vb_pode_exibir && ((trim($vs_valor_campo) != "") || $vb_marcar_sem_valor || $vb_marcar_com_valor)&& $pa_parametros_campo["exibir_quando_preenchido"];

    require dirname(__FILE__) . "/../../../app/components/campo_texto.php";
}

public function validar_valores($pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]))
    {
        $vs_valor_campo = trim($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]);
        $vs_valor_campo = filter_var($vs_valor_campo, FILTER_UNSAFE_RAW);

        if ($vs_valor_campo === false)
        {
            return 'O valor inserido no campo "' . $pa_parametros_campo["label"] . '" é inválido!';
        }

        if ( isset($pa_parametros_campo["obrigatorio"]) && $pa_parametros_campo["obrigatorio"] )
        {
            if ($vs_valor_campo == "")
                return 'O campo "' . $pa_parametros_campo["label"] . '" é de preenchimento obrigatório!';
        }

        if (isset($pa_parametros_campo["tamanho_maximo"]))
        {
            if (strlen($vs_valor_campo) > $pa_parametros_campo["tamanho_maximo"])
                return 'O valor inserido no campo "' . $pa_parametros_campo["label"] . '" é maior do que o permitido!';
        }

        if (isset($pa_parametros_campo["numerico"]))
        {
            if ( ($vs_valor_campo != "") && !preg_match('/^\d+$/', $vs_valor_campo) )
                return 'O valor inserido no campo "' . $pa_parametros_campo["label"] . '" é inválido!';
        }

        if (isset($pa_parametros_campo["igual_campo"]))
        {
            $vs_nome_campo_comparar = array_keys($pa_parametros_campo["igual_campo"]);
            $vs_valor_campo_comparar = $pa_valores_form[$vs_nome_campo_comparar];

            var_dump($vs_valor_campo_comparar, $vs_valor_campo);
            if ($vs_valor_campo_comparar != $vs_valor_campo)
                return 'O valor inserido no campo "' . $pa_parametros_campo["label"] . '" é diferente de ' . $pa_parametros_campo["igual_campo"] . '!';
        }
    }

    return true;
}

}