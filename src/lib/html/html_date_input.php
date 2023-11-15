<?php
#[\AllowDynamicProperties]
class html_date_input extends html_input
{

public function build(&$pa_valores_form=null, $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();
    $vs_modo = $this->get_modo_form();
    $vs_ui_element = $this->get_ui_element();

    $vb_marcar_sem_valor = false;
    $vb_marcar_com_valor = false;

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    $vb_campo_preenchido = false;
    
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_data_inicial" . $vs_sufixo_nome_campo]))
    {
        $vo_data = new Periodo;

        $vo_data->set_data_inicial($pa_valores_form[$pa_parametros_campo["nome"] . "_data_inicial" . $vs_sufixo_nome_campo]);

        if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_data_final" . $vs_sufixo_nome_campo]))
            $vo_data->set_data_final($pa_valores_form[$pa_parametros_campo["nome"] . "_data_final" . $vs_sufixo_nome_campo]);
                
        $vn_dia_inicial = $vo_data->get_dia_inicial_exibicao();
        $vn_mes_inicial = $vo_data->get_mes_inicial_exibicao();
        $vn_ano_inicial = $vo_data->get_ano_inicial();

        $vn_dia_final = $vo_data->get_dia_final_exibicao();
        $vn_mes_final = $vo_data->get_mes_final_exibicao();
        $vn_ano_final = $vo_data->get_ano_final_exibicao();

        $vb_campo_preenchido = true;
    }
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_ano_inicial" . $vs_sufixo_nome_campo]))
    {
        $vo_data = new Periodo;

        if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_dia_inicial" . $vs_sufixo_nome_campo]))
            $vn_dia_inicial = $pa_valores_form[$pa_parametros_campo["nome"] . "_dia_inicial" . $vs_sufixo_nome_campo];

            if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_mes_inicial" . $vs_sufixo_nome_campo]))
            $vn_mes_inicial = $pa_valores_form[$pa_parametros_campo["nome"] . "_mes_inicial" . $vs_sufixo_nome_campo];

        $vn_ano_inicial = "";
        if ($pa_valores_form[$pa_parametros_campo["nome"] . "_ano_inicial" . $vs_sufixo_nome_campo] != "")
        {
            $vn_ano_inicial = $pa_valores_form[$pa_parametros_campo["nome"] . "_ano_inicial" . $vs_sufixo_nome_campo];
            $vb_campo_preenchido = true;
        }

        if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_dia_final" . $vs_sufixo_nome_campo]))
            $vn_dia_final = $pa_valores_form[$pa_parametros_campo["nome"] . "_dia_final" . $vs_sufixo_nome_campo];

        if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_mes_final" . $vs_sufixo_nome_campo]))
            $vn_mes_final = $pa_valores_form[$pa_parametros_campo["nome"] . "_mes_final" . $vs_sufixo_nome_campo];

        if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_ano_final" . $vs_sufixo_nome_campo]))
            $vn_ano_final = $pa_valores_form[$pa_parametros_campo["nome"] . "_ano_final" . $vs_sufixo_nome_campo];
    }
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_data" . $vs_sufixo_nome_campo]))
    {
        $vo_data = new Periodo;

        $vo_data->set_data_inicial($pa_valores_form[$pa_parametros_campo["nome"] . "_data" . $vs_sufixo_nome_campo]);

        $vn_dia_inicial = $vo_data->get_dia_inicial_exibicao();
        $vn_mes_inicial = $vo_data->get_mes_inicial_exibicao();
        $vn_ano_inicial = $vo_data->get_ano_inicial();

        if ($vn_ano_inicial)
            $vb_campo_preenchido = true;
    }
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]))
    {
        $vo_data = new Periodo;

        if ( $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo] != "_data_")
            $vo_data->set_data_inicial($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]);

        $vn_dia_inicial = $vo_data->get_dia_inicial_exibicao();
        $vn_mes_inicial = $vo_data->get_mes_inicial_exibicao();
        $vn_ano_inicial = $vo_data->get_ano_inicial();

        if ($vn_ano_inicial)
            $vb_campo_preenchido = true;
    }

    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_presumido" . $vs_sufixo_nome_campo]))
        $vb_presumido = $pa_valores_form[$pa_parametros_campo["nome"] . "_presumido" . $vs_sufixo_nome_campo];
    else
        $vb_presumido = 0;

    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_sem_data" . $vs_sufixo_nome_campo]))
        $vb_sem_data = $pa_valores_form[$pa_parametros_campo["nome"] . "_sem_data". $vs_sufixo_nome_campo];
    else
        $vb_sem_data = 0;

    $vs_complemento = "";
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_complemento" . $vs_sufixo_nome_campo]))
        $vs_complemento = $pa_valores_form[$pa_parametros_campo["nome"] . "_complemento". $vs_sufixo_nome_campo];

    $vs_periodo = "";
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_periodo" . $vs_sufixo_nome_campo]))
        $vs_periodo = $pa_valores_form[$pa_parametros_campo["nome"] . "_periodo". $vs_sufixo_nome_campo];

    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_sem_valor"]))
        $vb_marcar_sem_valor = true;
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_com_valor"]))
        $vb_marcar_com_valor = true;
    
    if ($vb_marcar_sem_valor || $vb_marcar_com_valor)
        $pa_parametros_campo["desabilitar"] = true;

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    if (isset($pa_parametros_campo["exibir_quando_preenchido"]))
        $vb_pode_exibir = $vb_pode_exibir && ($vb_campo_preenchido || $vb_marcar_sem_valor || $vb_marcar_com_valor) && $pa_parametros_campo["exibir_quando_preenchido"];

    if (isset($pa_parametros_campo["formato"]))
        $vn_formato_data = $pa_parametros_campo["formato"];
    else
        $vn_formato_data = 1;

    $va_periodos_amplos = array();

    if (!isset($vo_data))
        $vo_data = new Periodo;

    if (isset($pa_parametros_campo["formato"]))
        $vn_formato_data = $pa_parametros_campo["formato"];
    else
        $vn_formato_data = $vo_data->get_formato_data();

    $va_periodos_amplos = $vo_data->get_periodos_amplos();

    require dirname(__FILE__) . "/../../../app/components/campo_data.php";
}

public function validar_valores($pa_valores_form=null, $pa_parametros_campo=array())
{
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_ano_inicial"]))
    {
        $vo_data = new Periodo;

        $vo_data->set_dia_inicial($pa_valores_form[$pa_parametros_campo["nome"] . "_dia_inicial"]);
        $vo_data->set_mes_inicial($pa_valores_form[$pa_parametros_campo["nome"] . "_mes_inicial"]);
        $vo_data->set_ano_inicial($pa_valores_form[$pa_parametros_campo["nome"] . "_ano_inicial"]);

        if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_dia_final"]))
        {
            $vo_data->set_dia_final($pa_valores_form[$pa_parametros_campo["nome"] . "_dia_final"]);
            $vo_data->set_mes_final($pa_valores_form[$pa_parametros_campo["nome"] . "_mes_final"]);
            $vo_data->set_ano_final($pa_valores_form[$pa_parametros_campo["nome"] . "_ano_final"]);
        }
        
        $vo_data->consolidar();

        if (!$vo_data->validar())
            return "Data inválida!";
    }

    return true;
}

}