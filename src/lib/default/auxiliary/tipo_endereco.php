<?php

class tipo_endereco extends objeto_base
{

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_endereco_codigo"] = ["nome" => "tipo_endereco_codigo"];
        $va_campos_visualizacao["tipo_endereco_nome"] = ["nome" => "tipo_endereco_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_endereco_codigo'] = ['tipo_endereco_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['tipo_endereco_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = 'lista', $pn_primeiro_registro = 0, $pn_numero_registros = 0, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['endereço original'] = ['tipo_endereco_codigo' => 'endereço original', 'tipo_endereco_nome' => 'endereço original'];
        $va_itens['endereço atualizado'] = ['tipo_endereco_codigo' => 'endereço atualizado', 'tipo_endereco_nome' => 'endereço atualizado'];

        if (isset($pa_filtros_busca['tipo_endereco_codigo']))
            return $va_itens[$pa_filtros_busca['tipo_endereco_codigo']];
        else
            return $va_itens;
    }

}

?>