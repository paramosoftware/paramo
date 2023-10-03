<?php

class html_number_input extends html_input
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
            $va_parametros_consulta[$pa_parametros_campo["serial"]["agrupado_por"]] = $pa_valores_form[$pa_parametros_campo["serial"]["agrupado_por"]];

            $va_ultimo_registro = $vo_objeto->ler_lista($va_parametros_consulta, "navegacao", 1, 1);

            if (!count($va_ultimo_registro))
                $vs_novo_valor_serial = str_pad("1", 7, "0", STR_PAD_LEFT);
            else
            {
                $vs_atributo_nome = "";
                if (isset($pa_parametros_campo["campo_pai"]))
                    $vs_atributo_nome = $pa_parametros_campo["campo_pai"] . "_0_" . $pa_parametros_campo["nome"];

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

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    if (isset($pa_parametros_campo["escopo"]))
        $vs_escopo = $pa_parametros_campo["escopo"];

    // Só vai apresentar o valor do campo se não for um campo de valor calculado pelo sistema e não for duplicação
    if (!isset($pa_parametros_campo["automatico"]) || ($vs_modo != "duplicacao") )
    {
        $vs_valor_campo = $this->ler_valor_textual($pa_valores_form, $pa_parametros_campo["nome"] . $vs_sufixo_nome_campo);

        if ( (!$vs_valor_campo) && (isset($pa_parametros_campo["valor_padrao"])) )
        {
            $vs_valor_campo = $pa_parametros_campo["valor_padrao"];
            $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo] = $vs_valor_campo;
        }
    }

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    if (!$vs_valor_campo)
        $vs_valor_campo = $this->preencher($pa_valores_form, $pa_parametros_campo);


    require dirname(__FILE__) . "/../../../app/components/campo_numero.php";

}

public function validar_valores($pa_valores_form=array(), $pa_parametros_campo=array())
{
    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    if (isset($pa_valores_form[$pa_parametros_campo["nome"]  . $vs_sufixo_nome_campo]))
    {
        $vs_valor_campo = trim($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]);
        $vs_valor_campo = filter_var($vs_valor_campo, FILTER_UNSAFE_RAW);

        if ( ($vs_valor_campo != "") && !preg_match('/^\d+$/', $vs_valor_campo) )
            return 'O valor inserido no campo "' . $pa_parametros_campo["label"] . '" é inválido!';

        if ( isset($pa_parametros_campo["obrigatorio"]) && $pa_parametros_campo["obrigatorio"] )
        {
            if ($vs_valor_campo == "")
                return 'O campo "' . $pa_parametros_campo["label"] . '" é de preenchimento obrigatório!';
        }

        if (isset($pa_parametros_campo["tamanho_maximo"]))
        {
            if ($vs_valor_campo > $pa_parametros_campo["tamanho_maximo"])
                return 'O valor inserido no campo "' . $pa_parametros_campo["label"] . '" é maior do que o permitido!';
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