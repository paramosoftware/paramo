<?php

class html_grid_input extends html_input
{

private $itens;

public function get_itens()
{
    if (!isset($this->itens))
        $this->itens = array();

    return $this->itens;
}

public function preencher($pa_valores_form, $pa_parametros_campo)
{
    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    $va_itens = array();

    if (isset($pa_parametros_campo["objeto"]))
    {
        $vo_objeto = new $pa_parametros_campo["objeto"];

        $va_filtro = array();

        if (isset($pa_parametros_campo["filtro"]))
        {
            foreach ($pa_parametros_campo["filtro"] as $va_filtro_grid)
            {
                if (isset($va_filtro_grid["operador"]))
                    $va_filtro[$va_filtro_combo["atributo"]] = [$va_filtro_grid["valor"], $va_filtro_grid["operador"]];
                else
                    $va_filtro[$va_filtro_grid["atributo"]] = $va_filtro_grid["valor"];
            }
        }

        $va_itens = $vo_objeto->ler_lista($va_filtro, "lista");
        $va_visualizacao = $vo_objeto->get_visualizacao("lista");
    }
    
    foreach($va_itens as $va_item)
    {
        if (isset($pa_parametros_campo["atributos"]))
        {
            $vn_item_lista_option = $va_item[$pa_parametros_campo["atributos"][0]];

            // Temos que generalizar essa montagem numa função
            $va_campo_nome = explode(".", $pa_parametros_campo["atributos"][1]);
            $vs_valor_item_lista = $va_item;
            foreach ($va_campo_nome as $vs_campo_nome)
            {
                if (isset($vs_valor_item_lista[$vs_campo_nome]))
                    $vs_valor_item_lista = $vs_valor_item_lista[$vs_campo_nome];
                else
                {
                    $vs_valor_item_lista = "";
                    break;
                }
            }
            // Temos que generalizar essa montagem numa função

            $vs_item_lista_value = $vs_valor_item_lista;
        }

        $this->adicionar_item($vn_item_lista_option, $vs_item_lista_value);
    }
}

public function adicionar_item($ps_key, $ps_valor)
{
    if (!isset($this->itens))
        $this->itens = array();

    $this->itens[$ps_key] = $ps_valor;
}

public function build(&$pa_valores_form=null, $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();
    
    $this->preencher($pa_valores_form, $pa_parametros_campo);
    $va_itens_campo = $this->get_itens();
    
    if (isset($pa_valores_form[$pa_parametros_campo["nome"]]))
        $va_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"]];
    
    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);
    
    require dirname(__FILE__) . "/../../../app/components/campo_grid_form.php";
}

}