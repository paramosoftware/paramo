<?php

class html_form_cadastro
{

protected $id;
protected $abas_form;
protected $campos;
protected $valores;
protected $valores_portugues;
protected $recursos_sistema_permissao_edicao;

function __construct($ps_id_form, $pa_abas_form=array(), $pa_campos=array(), $pa_valores_form=array(), $pa_valores_form_portugues=array(), $pa_recursos_sistema_permissao_edicao=array())
{
	$this->id = $ps_id_form;
    $this->abas_form = $pa_abas_form;
    $this->campos = $pa_campos;
    $this->valores = $pa_valores_form;
    $this->valores_portugues = $pa_valores_form_portugues;
    $this->recursos_sistema_permissao_edicao = $pa_recursos_sistema_permissao_edicao;
}

public function build($pn_objeto_codigo, $pn_usuario_logado_instituicao_codigo, $vn_usuario_logado_acervo_codigo='', $vs_modo="edicao")
{
    $vs_id_objeto_tela = $this->id;
    $va_abas_form = $this->abas_form;
    $va_campos = $this->campos;
    $va_objeto = $this->valores;
    $va_objeto_portugues = $this->valores_portugues;
    $va_recursos_sistema_permissao_edicao = $this->recursos_sistema_permissao_edicao;

    if (!$pn_objeto_codigo)
        $vs_modo_form = "insert";
    else
        $vs_modo_form = "update";

    require dirname(__FILE__) . "/../../../app/functions/montar_campos.php";

    if (is_array($vs_campo_foco))
        return $vs_campo_foco[0];
    else
        return $vs_campo_foco;
}

}