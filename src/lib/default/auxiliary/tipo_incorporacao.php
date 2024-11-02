<?php

class tipo_incorporacao extends objeto_base
{

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_incorporacao_codigo"] = ["nome" => "tipo_incorporacao_codigo"];
        $va_campos_visualizacao["tipo_incorporacao_nome"] = ["nome" => "tipo_incorporacao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_incorporacao_codigo'] = ['tipo_incorporacao_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['tipo_incorporacao_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['tipo_incorporacao_codigo' => '1', 'tipo_incorporacao_nome' => 'Compra'];
        $va_itens['2'] = ['tipo_incorporacao_codigo' => '2', 'tipo_incorporacao_nome' => 'Doação'];
        $va_itens['3'] = ['tipo_incorporacao_codigo' => '3', 'tipo_incorporacao_nome' => 'Permuta'];
        $va_itens['4'] = ['tipo_incorporacao_codigo' => '4', 'tipo_incorporacao_nome' => 'Outra'];

        if (isset($pa_filtros_busca['tipo_incorporacao_codigo']))
            return $va_itens[$pa_filtros_busca['tipo_incorporacao_codigo']];
        else
            return $va_itens;
    }

}

?>