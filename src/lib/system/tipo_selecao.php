<?php

class tipo_selecao extends objeto_base
{

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_selecao_codigo"] = ["nome" => "tipo_selecao_codigo"];
        $va_campos_visualizacao["tipo_selecao_nome"] = ["nome" => "tipo_selecao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_selecao_codigo'] = ['tipo_selecao_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['tipo_selecao_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['tipo_selecao_codigo' => '1', 'tipo_selecao_nome' => 'seleção interna'];
        $va_itens['2'] = ['tipo_selecao_codigo' => '2', 'tipo_selecao_nome' => 'consulta'];

        if (isset($pa_filtros_busca['tipo_selecao_codigo']))
            return $va_itens[$pa_filtros_busca['tipo_selecao_codigo']];
        else
            return $va_itens;
    }

}

?>