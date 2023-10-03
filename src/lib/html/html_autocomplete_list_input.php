<?php

class html_autocomplete_list_input extends html_combo_input
{

public function build(&$pa_valores_form=null, $pa_parametros_campo=array())
{
    require dirname(__FILE__)."/../../../app/components/ui/campo_lista_selecao.php";
}

}