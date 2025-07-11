<?php

#[\AllowDynamicProperties]
class Periodo
{

private $sem_data = false;

private $dia_inicial = "";
private $mes_inicial = "";
private $ano_inicial = "";

private $data_inicial;

private $dia_final = "";
private $mes_final = "";
private $ano_final = "";

private $data_final;

private $presumido = 0;

private $complemento = "";
private $periodo_amplo = "";

private $va_nomes_meses_abreviados;
private $lista_periodos_amplos;

function __construct()
{
	$this->va_nomes_meses_abreviados = array(1 => "jan.",2 => "fev.",3 => "mar.",4 => "abr.",5 => "maio",6 => "jun.",7 => "jul.",8 => "ago.",9 => "set.",10 => "out.",11 => "nov.",12 => "dez.");
	$this->seculos_romano = array(15 => "XV", 16 => "XVI", 17 => "XVII", 18 => "XVIII", 19 => "XIX", 20 => "XX", 21 => "XXI");

	$this->lista_periodos_amplos = [
		1701 => "Primeira metade do século XVIII",
		1751 => "Segunda metade do século XVIII",
		1801 => "Primeira metade do século XIX",
		1851 => "Segunda metade do século XIX",
		1901 => "Primeira metade do século XX",
		1951 => "Segunda metade do século XX",
		2001 => "Primeira metade do século XXI",
		2051 => "Segunda metade do século XXI"
	];
}

public function set_sem_data($pb_sem_data)
{
	$this->sem_data = $pb_sem_data;
}

public function get_sem_data()
{
	return $this->sem_data;
}

public function set_complemento($ps_complemento)
{
	$this->complemento = $ps_complemento;
}

public function get_complemento()
{
	return $this->complemento;
}

public function set_periodo_amplo($ps_periodo_amplo)
{
	$this->periodo_amplo = $ps_periodo_amplo;
}

public function set_dia_inicial($pn_dia_inicial)
{
	$this->dia_inicial = $pn_dia_inicial;
}

public function get_dia_inicial()
{
	if ($this->data_inicial)
		return intval(date('j', strtotime($this->data_inicial)));
	else
	{
		return $this->dia_inicial;
	}
}

public function get_hora_completa()
{
	if ($this->data_inicial)
		return date("H:i:s", strtotime($this->data_inicial));
	else
		return "";
}

public function get_dia_inicial_exibicao()
{
	$vDia_Inicial = $this->get_dia_inicial();
	$vDia_Final = $this->get_dia_final();
	
	$vMes_Inicial = $this->get_mes_inicial();
	$vMes_Final = $this->get_mes_final();
	
	$vAno_Inicial = $this->get_ano_inicial();
	$vAno_Final = $this->get_ano_final();
	
	if ( ($vDia_Inicial == $vDia_Final) && ($vMes_Inicial == $vMes_Final) && ($vAno_Inicial == $vAno_Final) )
		return $this->get_dia_inicial();
	
	elseif ( ($vDia_Inicial == 1) && ($vMes_Inicial == 1) && ($vAno_Inicial != $vAno_Final) )
		return "";

	elseif ( ($vDia_Inicial == 1) && ( ($vDia_Final == 31) || ($vDia_Final == 30) ) )
		return "";

	elseif ( ($vDia_Inicial == 1) && ( ($vDia_Final == 28) || ($vDia_Final == 29) ) && ($vMes_Inicial == 2) && ($vMes_Final == 2 ) )
		return "";
	
	elseif ($vDia_Inicial != $vDia_Final)
		return $this->get_dia_inicial();
		
	elseif ($vMes_Inicial != $vMes_Final)
		return $this->get_dia_inicial();
	
	else
		return "";
}

public function set_mes_inicial($pn_mes_inicial)
{
	$this->mes_inicial = $pn_mes_inicial;
}

public function get_mes_inicial()
{
	if ($this->data_inicial)
		return intval(date('m', strtotime($this->data_inicial)));
	else
		return $this->mes_inicial;
}

public function get_mes_inicial_exibicao()
{
	$vDia_Inicial = $this->get_dia_inicial();
	$vDia_Final = $this->get_dia_final();

	$vMes_Inicial = $this->get_mes_inicial();
	$vMes_Final = $this->get_mes_final();
	
	$vAno_Inicial = $this->get_ano_inicial();
	$vAno_Final = $this->get_ano_final();
	
	if ( ($vDia_Inicial == 1) && ($vDia_Final == 31) && ($vMes_Inicial == 1) && ($vMes_Final == 12) )
		return "";

	if ( ($vDia_Inicial == 1) && ($vMes_Inicial == 1) && ($vAno_Inicial != $vAno_Final) )
		return "";
	else
		return $vMes_Inicial;
}

public function set_ano_inicial($pn_ano_inicial)
{
	$this->ano_inicial = $pn_ano_inicial;
}

public function get_ano_inicial()
{
    if ($this->data_inicial)
		return intval(date('Y', strtotime($this->data_inicial)));
	else
		return $this->ano_inicial;
}

public function set_data_inicial($ps_data_inicial)
{
	// Pode receber somente o ano
	/*
	if (intval($ps_data_inicial) && strlen(trim($ps_data_inicial)))
	{
		$this->data_inicial = $ps_data_inicial . "-01-01";

		// Setamos a data final preventivamente
		$this->data_final = $ps_data_inicial . "-12-31";
	}
	else
	*/
		$this->data_inicial = $ps_data_inicial;
}

public function set_dia_final($pn_dia_final)
{
	$this->dia_final = $pn_dia_final;
}

public function get_dia_final()
{
	if ($this->data_final)
		return intval(date('j', strtotime($this->data_final)));
	else
		return $this->dia_final;
}

public function set_mes_final($pn_mes_final)
{
	$this->mes_final = $pn_mes_final;
}

public function get_mes_final()
{
	if ($this->data_final)
		return intval(date('m', strtotime($this->data_final)));
	else
		return $this->mes_final;
}

public function get_dia_final_exibicao()
{
	$vDia_Inicial = $this->get_dia_inicial();
	$vDia_Final = $this->get_dia_final();
	
	$vMes_Inicial = $this->get_mes_inicial();
	$vMes_Final = $this->get_mes_final();
	
	$vAno_Inicial = $this->get_ano_inicial();
	$vAno_Final = $this->get_ano_final();
	
	if ( ($vDia_Inicial == $vDia_Final) && ($vMes_Inicial == $vMes_Final) && ($vAno_Inicial == $vAno_Final) )
		return "";
	
	elseif ( ($vDia_Inicial == 1) && ( ($vDia_Final == 31) || ($vDia_Final == 30) ) )
		return "";
		
	elseif ( ($vDia_Inicial == 1) && ( ($vDia_Final == 28) || ($vDia_Final == 29) ) && ($vMes_Inicial == 2) && ($vMes_Final == 2 ) )
		return "";

	else
		return $this->get_dia_final();
}

public function get_mes_final_exibicao()
{
	$vDia_Inicial = $this->get_dia_inicial();	
	$vDia_Final = $this->get_dia_final();
	
	$vMes_Inicial = $this->get_mes_inicial();
	$vMes_Final = $this->get_mes_final();
	
	$vAno_Inicial = $this->get_ano_inicial();
	$vAno_Final = $this->get_ano_final();
	
	// Se datas iniciais e finais coincidem
	if ( ($vDia_Inicial == $vDia_Final) && ($vMes_Inicial == $vMes_Final) && ($vAno_Inicial == $vAno_Final) )
		return "";
	
	if ( ($vDia_Inicial != 1) )
		return $vMes_Final;
	
	//elseif ( ($vDia_Inicial == 1) && ($vDia_Final == 31) && ($vMes_Inicial == 1) && ($vMes_Final == 12) && ($vAno_Inicial == $vAno_Final) )
	elseif ( ($vDia_Inicial == 1) && ($vDia_Final == 31) && ($vMes_Inicial == 1) && ($vMes_Final == 12) )
		return "";
	
	elseif ( ($vMes_Inicial == $vMes_Final) && ($vAno_Inicial == $vAno_Final) )
		return "";
	
	//elseif ($vMes_Final == 12)
		//return "";
	
	else
		return $vMes_Final;
}

public function set_ano_final($pn_ano_final)
{
	$this->ano_final = $pn_ano_final;
}

public function get_ano_final()
{
	if ($this->data_final)
		return intval(date('Y', strtotime($this->data_final)));
	else
		return $this->ano_final;
}

public function set_presumido($pb_presumido)
{
	$this->presumido = $pb_presumido;
}

public function get_presumido()
{
	if ($this->presumido)
		return $this->presumido;
	else
		return 0;
}

public function consolidar()
{
	if ( (!$this->dia_inicial) && (!$this->mes_inicial) && (!$this->ano_inicial) )
	{
		$this->data_inicial = "";	
		$this->data_final = "";
	}
	else
	{
		if (!$this->mes_inicial && $this->dia_inicial)
		{
			return false;
		}
		elseif (!$this->mes_inicial)
		{
			if ( ($this->dia_final && !$this->mes_final) || ($this->dia_final && $this->mes_final && !$this->ano_final) ) 
				return false;

			$this->mes_inicial = 1;
			$this->mes_final = 12;
			
			if (!$this->ano_final)
				$this->ano_final = $this->ano_inicial;
		}
		else
		{
			if (!$this->mes_final)
			{
				if ( ($this->dia_final && !$this->mes_final) || ($this->dia_final && $this->mes_final && !$this->ano_final) ) 
					return false;

				if ( (!$this->ano_final) )
				{
					$this->mes_final = $this->mes_inicial;
					$this->ano_final = $this->ano_inicial;
				}
				else
				{
					$this->mes_final = 12;
				}		
			}
			
			if (!$this->ano_final)
			{
				$this->ano_final = $this->ano_inicial;
			}
		}
		
		if (!$this->dia_inicial)
		{
			$this->dia_inicial = 1;
			
			if (!$this->dia_final)
			{
				if ( ($this->mes_final == 1) || ($this->mes_final == 3) || ($this->mes_final == 5) || ($this->mes_final == 7) || ($this->mes_final == 8) || ($this->mes_final == 10) || ($this->mes_final == 12) )
					$this->dia_final = 31;
				
				else if ( ($this->mes_final == 4) || ($this->mes_final == 6) || ($this->mes_final == 9) || ($this->mes_final == 11) )
					$this->dia_final = 30;
				
				else if ( ($this->mes_final == 2) )
					$this->dia_final = 28;
			}
		}
		else
		{
			if (!$this->dia_final)
				$this->dia_final = $this->dia_inicial;
		}
		
		$this->data_inicial = $this->ano_inicial . "-" . $this->mes_inicial . "-" . $this->dia_inicial;	
		$this->data_final = $this->ano_final . "-" . $this->mes_final . "-" . $this->dia_final;
	}
}

public function validar()
{
	$va_meses_30_dias = [4,6,9,11];
	$va_meses_31_dias = [1,3,5,7,8,10,12];
	$vb_ano_bissexto = false;

	if ($this->ano_inicial)
	{
		if ($this->dia_inicial && !$this->mes_inicial)
			return false;

		if ( ($this->ano_inicial < 1000) || ($this->ano_inicial > 9999) )
			return false;

		$vb_ano_inicial_bissexto = $this->ano_bissexto($this->ano_inicial);
	}
	else
	{
		// Se não existe ano inicial preenchido, verifica se dia e mês inicial vieram preenchidos
		/////////////////////////////////////////////////////////////////////////////////////////

		if ($this->dia_inicial || $this->mes_inicial)
			return false;
	}

	if (isset($this->ano_final) && $this->ano_final)
	{
		if ($this->dia_final && !$this->mes_final)
			return false;

		if ( ($this->ano_final < 1000) || ($this->ano_final > 9999) )
			return false;

		$vb_ano_final_bissexto = $this->ano_bissexto($this->ano_inicial);
	}
	else
	{
		// Se não existe ano final preenchido, verifica se dia e mês inicial vieram preenchidos
		/////////////////////////////////////////////////////////////////////////////////////////

		if ($this->dia_final || $this->mes_final)
			return false;
	}

	if (isset($this->mes_inicial) && ($this->mes_inicial > 12))
		return false;

	if (isset($this->mes_final) && ($this->mes_final > 12))
		return false;

	if ($this->ano_inicial > $this->ano_final)
		return false;

	if ( ($this->ano_inicial == $this->ano_final) && ($this->mes_inicial > $this->mes_final) )
		return false;

	if ( ($this->ano_inicial == $this->ano_final) && ($this->mes_inicial == $this->mes_final) && ($this->dia_inicial > $this->dia_final) )
		return false;

	if ( in_array($this->mes_inicial, $va_meses_30_dias) && ($this->dia_inicial > 30) )
		return false;

	if ( in_array($this->mes_inicial, $va_meses_31_dias) && ($this->dia_inicial > 31) )
		return false;

	if (
		( ($this->mes_inicial == 2) && !$vb_ano_inicial_bissexto && ($this->dia_inicial > 28) )
		||
		( ($this->mes_inicial == 2) && $vb_ano_inicial_bissexto && ($this->dia_inicial > 29) )
	)
		return false;

	if ( in_array($this->mes_final, $va_meses_30_dias) && ($this->dia_final > 30) )
		return false;

	if ( in_array($this->mes_final, $va_meses_31_dias) && ($this->dia_final > 31) )
		return false;

	if (
		( ($this->mes_final == 2) && !$vb_ano_final_bissexto && ($this->dia_final > 28) )
		||
		( ($this->mes_final == 2) && $vb_ano_final_bissexto && ($this->dia_final > 29) )
	)
		return false;

	return true;
}

public function ano_bissexto($pn_ano)
{
	if ($pn_ano == "")
		return false;
	
	if ( ($pn_ano % 4) == 0)
	{
		if ( ($pn_ano % 100) != 0)
			return true;
		else
		{
			if ( ($pn_ano % 400) == 0)
				return true;
		}
	}
	
	return false;
}

public function get_ano_final_exibicao()
{
	$vDia_Inicial = $this->get_dia_inicial();	
	$vDia_Final = $this->get_dia_final();
	
	$vMes_Inicial = $this->get_mes_inicial();
	$vMes_Final = $this->get_mes_final();
	
	$vAno_Inicial = $this->get_ano_inicial();
	$vAno_Final = $this->get_ano_final();
	
	// Se datas iniciais e finais coincidem
	if ( ($vDia_Inicial == $vDia_Final) && ($vMes_Inicial == $vMes_Final) && ($vAno_Inicial == $vAno_Final) )
		return "";
	
	if ( ($vDia_Inicial != 1) )
		return $vAno_Final;
	
	if ( ($vMes_Inicial == 1) && ($vMes_Final == 12) && ($vAno_Inicial == $vAno_Final) )
		return "";
	
	else if ( ($vMes_Inicial == $vMes_Final) && ($vAno_Inicial == $vAno_Final) )
		return "";
	
	else
		return $vAno_Final;
}

public function get_data_inicial()
{
	return $this->data_inicial;
}

public function get_data_inicial_exibicao()
{
	$vDia_Inicial = $this->get_dia_inicial_exibicao();
	$vMes_Inicial = $this->get_mes_inicial_exibicao();
	$vAno_Inicial = $this->get_ano_inicial();
	
	if ($vDia_Inicial)
		$vData_Inicial = $vDia_Inicial . "/" . $vMes_Inicial . "/" . $vAno_Inicial;
	elseif ($vMes_Inicial)
		$vData_Inicial = $vMes_Inicial . "/" . $vAno_Inicial;
	elseif ($vAno_Inicial)
		$vData_Inicial = $vAno_Inicial;
	else
		$vData_Inicial = "";
	
	return $vData_Inicial;
}

public function get_data_final_exibicao()
{
	$vDia_Final = $this->get_dia_final_exibicao();
	$vMes_Final = $this->get_mes_final_exibicao();
	$vAno_Final = $this->get_ano_final_exibicao();
	
	if ($vDia_Final)
		$vData_Final = $vDia_Final . "/" . $vMes_Final . "/" . $vAno_Final;
	elseif ($vMes_Final)
		$vData_Final = $vMes_Final . "/" . $vAno_Final;
	elseif ($vAno_Final)
		$vData_Final = $vAno_Final;
	else
		$vData_Final = "";
	
	return $vData_Final;
}

    public function get_data_inicial_barra($ps_separador = '')
    {
        if ($this->get_ano_inicial())
        {
            if ($ps_separador)
                return str_pad($this->get_dia_inicial(), 2, "0", STR_PAD_LEFT) . $ps_separador . str_pad($this->get_mes_inicial(), 2, "0", STR_PAD_LEFT) . $ps_separador . $this->get_ano_inicial();
            else
                return $this->get_dia_inicial() . " " . $this->va_nomes_meses_abreviados[$this->get_mes_inicial()] . " " . $this->get_ano_inicial();
        }
        else
            return "";
    }

public function get_data_inicial_barra_sem_ano()
{
	if ($this->get_ano_inicial())
	{
		return $this->get_dia_inicial() . " " . $this->va_nomes_meses_abreviados[$this->get_mes_inicial()];
	}
	else
		return "";
}

public function set_data_final($ps_data_final)
{
	$this->data_final = $ps_data_final;
}

public function get_data_final()
{
	return $this->data_final;
}

public function get_data_final_barra($ps_separador='')
{
	if ($this->get_ano_final())
	{
		if ($ps_separador)
			return periodo . phpstr_pad($this->get_dia_final(), 2, "0", STR_PAD_LEFT) . $ps_separador . str_pad($this->get_mes_final(), 2, "0", STR_PAD_LEFT) . $ps_separador . $this->get_ano_final();
		else
			return $this->get_dia_final() . " " . $this->va_nomes_meses_abreviados[$this->get_mes_final()] . " " . $this->get_ano_final();
	}
	else
		return "";
}

public function get_data_exibicao($ps_separador='')
{
	$vs_data_exibicao = "";
	if ($this->get_sem_data())
		return !empty(config::get(["data_indisponivel"])) ? config::get(["data_indisponivel"]) : "[s.d.]";

    if ($this->get_data_inicial() == $this->get_data_final())	
		$vs_data_exibicao = $this->get_data_inicial_barra($ps_separador);
	
	elseif ($this->get_ano_inicial() == $this->get_ano_final())
	{
        if ( ($this->get_mes_inicial() == 1) && ($this->get_mes_final() == 12) && ($this->get_dia_inicial() == 1) && ($this->get_dia_final() == 31) )
			$vs_data_exibicao = $this->get_ano_inicial();
		
		elseif ( ($this->get_mes_inicial() == $this->get_mes_final()) && ($this->get_dia_inicial() == 1) && ( ($this->get_dia_final() == 31) || ($this->get_dia_final() == 30) || ($this->get_dia_final() == 28) ) )
		{
			if ($ps_separador)
				$vs_data_exibicao = periodo . phpstr_pad($this->get_mes_inicial(), 2, "0", STR_PAD_LEFT) . $ps_separador . $this->get_ano_inicial();
			else
				$vs_data_exibicao = $this->va_nomes_meses_abreviados[$this->get_mes_inicial()] . " " . $this->get_ano_inicial();
		}
		elseif ( ($this->get_dia_inicial() == 1) && ( ($this->get_dia_final() == 31) || ($this->get_dia_final() == 30) || ($this->get_dia_final() == 28) ) )
		{
			if ($ps_separador)
				$vs_data_exibicao = str_pad($this->get_mes_inicial(), 2, "0", STR_PAD_LEFT) . "-" . str_pad($this->get_mes_final(), 2, "0", STR_PAD_LEFT) . $ps_separador . $this->get_ano_inicial();
			else
				$vs_data_exibicao = $this->va_nomes_meses_abreviados[$this->get_mes_inicial()] . "/" . $this->va_nomes_meses_abreviados[$this->get_mes_final()] . " " . $this->get_ano_inicial();
		}
		else
			$vs_data_exibicao = $this->get_data_inicial_barra($ps_separador) . " a " . $this->get_data_final_barra($ps_separador);
	}
	elseif ($this->get_ano_inicial() && $this->get_ano_final())
	{
		if ( (($this->get_ano_final() - $this->get_ano_inicial()) == 99) && (($this->get_ano_final() % 100) == 0) )
		{
			if (isset($this->seculos_romano[substr($this->get_ano_final(), 0, 2)]))
				$vs_data_exibicao = "séc. " . $this->seculos_romano[substr($this->get_ano_final(), 0, 2)];
			else
				$vs_data_exibicao = "séc. " . substr($this->get_ano_final(), 0, 2);
		}
		else
			$vs_data_exibicao = $this->get_ano_inicial() . " - " . $this->get_ano_final();
	}

	if ($this->get_presumido())
		$vs_data_exibicao = "[" . $vs_data_exibicao . "]";


	return $vs_data_exibicao;
}

public function get_data_exibicao_sem_ano()
{
	if ($this->get_data_inicial() == $this->get_data_final())
		return $this->get_data_inicial_barra_sem_ano();
	
	elseif ($this->get_ano_inicial() == $this->get_ano_final())
	{
		if ( ($this->get_mes_inicial() == 1) && ($this->get_mes_final() == 12) && ($this->get_dia_inicial() == 1) && ($this->get_dia_final() == 31) )
			return "";
		
		elseif ( ($this->get_mes_inicial() == $this->get_mes_final()) && ($this->get_dia_inicial() == 1) && ( ($this->get_dia_final() == 31) || ($this->get_dia_final() == 30) || ($this->get_dia_final() == 28) ) )
		{
			return $this->va_nomes_meses_abreviados[$this->get_mes_inicial()];
		}
	}
}

public function get_formato_data()
{
	if ($this->get_data_inicial() == "")
		return 1;

	// DIA
	if ($this->get_data_inicial() == $this->get_data_final())
		return 1;

	// MÊS/ANO
	if ( ($this->get_ano_inicial() == $this->get_ano_final()) && ($this->get_mes_inicial() == $this->get_mes_final()) && ($this->get_dia_inicial() == 1) )
	{
		if (
			(in_array($this->get_mes_inicial(), [1,3,5,7,8,10,12]) && ($this->get_dia_final() == 31))
			||
			(in_array($this->get_mes_inicial(), [2,4,6,9,11]) && ($this->get_dia_final() == 30))
			||
			(($this->get_mes_inicial() == 2) && ($this->get_dia_final() == 28))
		)
		{
			return 2;
		}
	}

	// ANO
	if ( ($this->get_ano_inicial() == $this->get_ano_final()) && ($this->get_mes_inicial() == 1) && ($this->get_mes_final() == 12) && ($this->get_dia_inicial() == 1) && ($this->get_dia_final() == 31) )
		return 3;

	// DÉCADA
	if ( (($this->get_ano_final() % 10) == 0) && (($this->get_ano_final() - $this->get_ano_inicial()) == 9) && ($this->get_mes_inicial() == 1) && ($this->get_mes_final() == 12) && ($this->get_dia_inicial() == 1) && ($this->get_dia_final() == 31) )
		return 4;

	// SÉCULO
	if ( (($this->get_ano_final() % 100) == 0) && (($this->get_ano_final() - $this->get_ano_inicial()) == 99) && ($this->get_mes_inicial() == 1) && ($this->get_mes_final() == 12) && ($this->get_dia_inicial() == 1) && ($this->get_dia_final() == 31) )
		return 5;

	return 6;
}

public function get_periodos_amplos()
{
	return $this->lista_periodos_amplos;
}

public function get_periodo_amplo()
{
	if ( ($this->get_ano_inicial() >= 1701) && ($this->get_ano_inicial() <= 1750) && ($this->get_ano_final() <= 1750) )
		return $this->lista_periodos_amplos[1701];
	elseif ( ($this->get_ano_inicial() >= 1751) && ($this->get_ano_inicial() <= 1800) && ($this->get_ano_final() <= 1800) )
		return $this->lista_periodos_amplos[1751];
	elseif ( ($this->get_ano_inicial() >= 1801) && ($this->get_ano_inicial() <= 1850) && ($this->get_ano_final() <= 1850) )
		return $this->lista_periodos_amplos[1801];
	elseif ( ($this->get_ano_inicial() >= 1851) && ($this->get_ano_inicial() <= 1900) && ($this->get_ano_final() <= 1900) )
		return $this->lista_periodos_amplos[1851];
	elseif ( ($this->get_ano_inicial() >= 1901) && ($this->get_ano_inicial() <= 1950) && ($this->get_ano_final() <= 1950) )
		return $this->lista_periodos_amplos[1901];
	elseif ( ($this->get_ano_inicial() >= 1951) && ($this->get_ano_inicial() <= 2000) && ($this->get_ano_final() <= 2000) )
		return $this->lista_periodos_amplos[1951];
	elseif ( ($this->get_ano_inicial() >= 2001) && ($this->get_ano_inicial() <= 2050) && ($this->get_ano_final() <= 2050) )
		return $this->lista_periodos_amplos[2001];
	elseif ( ($this->get_ano_inicial() >= 2051) && ($this->get_ano_inicial() <= 2100) && ($this->get_ano_final() <= 2100) )
		return $this->lista_periodos_amplos[2051];
	elseif ($this->get_ano_inicial())
		return "";
		
	return $this->periodo_amplo;
}

public function tratar_string($ps_data)
{
	$vs_data = trim($ps_data);

	$this->set_sem_data(0);

	if (substr($vs_data, 0, 1) == "[")
	{
		$vs_data = str_replace("[", "", $vs_data);
		$vs_data = str_replace("]", "", $vs_data);

		$this->set_presumido(1);
	}

	if(strpos($vs_data, "?") > 0)
	{
		$vs_data = str_replace("?", "", $vs_data);
		
		$this->set_presumido(1);
	}

	if (!empty(config::get(["data_indisponivel"])) && config::get(["data_indisponivel"]) == $vs_data || in_array($vs_data, ["s.d.", "s.d", "sd", "s/d"]))
	{
		$this->set_sem_data(1);
	}

	// Tratamento para datas no formato 19-- ou 194- ou 194
	///////////////////////////////////////////////////////

	if (substr($vs_data, -1) == "-" || strlen($vs_data) == 3) 
	{
		$vs_data = str_replace(" ", "", $vs_data);

		if (substr_count($vs_data, "-") == 1 || strlen($vs_data) == 3) 
		{
			$vs_data = str_replace("-", "", $vs_data);
			$vs_data = $vs_data . "0-".$vs_data."9";
		} 
		else 
		{
			$vs_data = str_replace("-", "", $vs_data);
			$vs_data = $vs_data . "01-".($vs_data+1)."00";
		}

		$this->set_presumido(1);
	}

	if (strpos($vs_data, "-") > 0)
	{
		$va_datas = explode("-", $vs_data);
		$vs_data = $va_datas[0];

		if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/", $va_datas[1], $matches))
		{
			$this->set_dia_final($matches[1]);
			$this->set_mes_final($matches[2]);
			$this->set_ano_final($matches[3]);
		}
		elseif (preg_match("/^(0[1-9]|1[0-2])\/([0-9]{4})$/", $va_datas[1], $matches))
		{
			$this->set_mes_final($matches[1]);
			$this->set_ano_final($matches[2]);
		}
		elseif (preg_match("/^\-\-\/(0[1-9]|1[0-2])\/([0-9]{4})$/", $va_datas[1], $matches))
		{
			$this->set_mes_final($matches[1]);
			$this->set_ano_final($matches[2]);
		}
		elseif (intval($va_datas[1]) && strlen($va_datas[1]) == 4)
			$this->set_ano_final($va_datas[1]);
	}

	if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/", $vs_data, $matches))
	{
		$this->set_dia_inicial($matches[1]);
		$this->set_mes_inicial($matches[2]);
		$this->set_ano_inicial($matches[3]);
	}
	elseif (preg_match("/^(0[1-9]|1[0-2])\/([0-9]{4})$/", $vs_data, $matches))
	{
		$this->set_mes_inicial($matches[1]);
		$this->set_ano_inicial($matches[2]);
	}
	elseif (preg_match("/^\-\-\/(0[1-9]|1[0-2])\/([0-9]{4})$/", $vs_data, $matches))
	{
		$this->set_mes_inicial($matches[1]);
		$this->set_ano_inicial($matches[2]);
	}
	elseif (intval($vs_data) && strlen($vs_data) == 4)
		$this->set_ano_inicial($vs_data);

	$this->consolidar();

	return $this->validar();
}

}

?>