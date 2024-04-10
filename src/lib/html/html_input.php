<?php

#[\AllowDynamicProperties]
class html_input
{

protected $tela;
protected $label;
protected $ui_element;
protected $modo_form;

protected $tamanho;
protected $largura;
protected $valor;
protected $permitir_sem_valor;
protected $selecao_multipla;

protected $objeto;
protected $visualizacao;


protected $termo_busca;

protected $modo;

function __construct($ps_tela, $ps_nome_campo, $ps_ui_element=null, $ps_modo_form=null) 
{
	$this->tela = $ps_tela;
    $this->nome = $ps_nome_campo;
	$this->ui_element = $ps_ui_element;
    $this->modo_form = $ps_modo_form;
}

public function get_tela()
{
    return $this->tela;
}

public function get_ui_element()
{
	if (!$this->ui_element)
		$this->ui_element = "";
		
    return $this->ui_element;
}

public function get_modo_form()
{
    return $this->modo_form;
}

public function set_termo_busca($ps_termo_busca)
{
	$this->termo_busca = $ps_termo_busca;
}

public function get_termo_busca()
{
    return $this->termo_busca;
}

public function set_permitir_sem_valor($pb_permitir_sem_valor)
{
	$this->permitir_sem_valor = $pb_permitir_sem_valor;
}

public function get_permitir_sem_valor()
{
    return $this->permitir_sem_valor;
}

public function set_label($ps_label)
{
	$this->label = $ps_label;
}

public function get_label()
{
	return $this->label;
}

public function set_nome($ps_nome)
{
	$this->nome = $ps_nome;
}

public function get_nome()
{
	return $this->nome;
}

public function set_valor($ps_valor)
{
	$this->valor = $ps_valor;
}

public function get_valor()
{
	return $this->valor;
}

public function set_objeto($ps_objeto)
{
	$this->objeto = $ps_objeto;
}

public function get_objeto()
{
	return $this->objeto;
}

public function set_visualizacao($ps_visualizacao)
{
	$this->visualizacao = $ps_visualizacao;
}

public function get_visualizacao()
{
	return $this->visualizacao;
}

public function set_selecao_multipla($pb_selecao_multipla)
{
	$this->selecao_multipla = $pb_selecao_multipla;
}

public function get_selecao_multipla()
{
    return $this->selecao_multipla;
}

public function set_tamanho($pn_tamanho)
{
	$this->tamanho = $pn_tamanho;
}

public function get_tamanho()
{
    return $this->tamanho;
}

public function validar_valores($pa_valores_form=array(), $pa_parametros_campo=array())
{
    return true;
}

protected function verificar_exibicao(&$pa_valores_form=null, $pa_parametros_campo=array(), $ps_sufixo_nome_campo = "")
{
    if (isset($pa_parametros_campo["nao_exibir"]) && $pa_parametros_campo["nao_exibir"])
        return false;
    
    $vb_pode_exibir = true;
    $vb_pode_exibir_temp = true;

    if (isset($pa_parametros_campo["regra_exibicao"]))
    {
        foreach ($pa_parametros_campo["regra_exibicao"] as $vs_campo_controle => $v_valores_desejados_campo_controle)
        {
            if (!is_array($v_valores_desejados_campo_controle))
                $v_valores_desejados_campo_controle = array($v_valores_desejados_campo_controle);

            if ($ps_sufixo_nome_campo)
                $vs_campo_controle = $vs_campo_controle . $ps_sufixo_nome_campo;

            if (isset($pa_valores_form[$vs_campo_controle]))
            {
                $v_valor_atual_campo_controle = $pa_valores_form[$vs_campo_controle];

                if (!is_array($v_valor_atual_campo_controle))
                    $va_valores_campo_controle = array($v_valor_atual_campo_controle);
                else
                    $va_valores_campo_controle = $v_valor_atual_campo_controle;

                foreach($va_valores_campo_controle as $v_valor_campo_controle)
                {
                    if (is_array($v_valor_campo_controle))
                        $v_valor_campo_controle = $v_valor_campo_controle[$vs_campo_controle];

                    foreach ($v_valores_desejados_campo_controle as $v_valor_desejado_campo_controle)
                    {
                        if ($v_valor_desejado_campo_controle == "nao_vazio")
                        {
                            if (trim($v_valor_campo_controle) == "")
                                return false;
                        }
                        elseif (substr($v_valor_desejado_campo_controle, 0, 2) == "<>")
                        {
                            $v_valor_nao_desejado_campo_controle = str_replace("<>", "", $v_valor_desejado_campo_controle);
                            
                            if ($v_valor_campo_controle == $v_valor_nao_desejado_campo_controle)
                                return false;
                        }
                        elseif ($v_valor_campo_controle != $v_valor_desejado_campo_controle)
                            $vb_pode_exibir_temp = false;
                        
                        else
                            return true;
                    }
                }
            }
            else
            {
                foreach ($v_valores_desejados_campo_controle as $v_valor_desejado_campo_controle)
                {
                    if ( ($v_valor_desejado_campo_controle == "nao_vazio") || ($v_valor_desejado_campo_controle) )
                        return false;
                }
            }
        }
    }

    return $vb_pode_exibir && $vb_pode_exibir_temp;
}

protected function ler_valor_textual($pa_valores_form, $ps_campo_nome)
{
    if (!isset($pa_valores_form))
        return "";
    
    if (!is_array($ps_campo_nome))
        $va_campo_nome = array($ps_campo_nome);
    else
        $va_campo_nome = $ps_campo_nome;

    $va_valor_campo = array();
    
    foreach($va_campo_nome as $vs_campo_nome)
    {
        if (is_array($vs_campo_nome) && isset($vs_campo_nome["constante"]))
        {
            $va_valor_campo[] = $vs_campo_nome["constante"];
        }
        else
        {
            if (isset($pa_valores_form[$vs_campo_nome]))
            {
                $va_valor_campo[] = $pa_valores_form[$vs_campo_nome];
                continue;
            }

            $va_partes_campo_nome = explode("_0_", $vs_campo_nome);
            $vs_valor_campo = $pa_valores_form;

            foreach ($va_partes_campo_nome as $vs_parte_campo_nome)
            {
                if (isset($vs_valor_campo[$vs_parte_campo_nome]))
                    $vs_valor_campo = $vs_valor_campo[$vs_parte_campo_nome];
                
                elseif ((isset($vs_valor_campo[0])) && is_array($vs_valor_campo[0]))
                {
                    $vs_valor_campo = $vs_valor_campo[0];
                    $vs_valor_campo = $vs_valor_campo[$vs_parte_campo_nome];
                }
                else
                {
                    $vs_valor_campo = "";
                    break;
                }
            }

            if (isset($vs_valor_campo) && trim($vs_valor_campo) != "")
                $va_valor_campo[] = $vs_valor_campo;
        }
    }

    return join(" ", $va_valor_campo);
}

}