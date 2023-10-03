<?php

class html_link_cadastrar extends html_input
{

public function build($pa_campos_salvar)
{
    $vs_id_objeto_tela = $this->get_tela();
    $vs_nome_campo = $this->get_nome();
    $vs_termo_busca = $this->get_termo_busca();

    $vo_objeto_campo = new $vs_id_objeto_tela;
    $va_campos_edicao_objeto_campo = $vo_objeto_campo->get_campos_edicao();

    if (!is_array($pa_campos_salvar))
        $va_campos_salvar = array($pa_campos_salvar);
    else
        $va_campos_salvar = $pa_campos_salvar;

    require dirname(__FILE__) . "/../../../app/components/link_cadastrar.php";
}

}