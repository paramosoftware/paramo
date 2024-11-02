<?php

class formato_data extends objeto_base
{

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["formato_data_codigo"] = ["nome" => "formato_data_codigo"];
        $va_campos_visualizacao["formato_data_nome"] = ["nome" => "formato_data_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        //return $va_chave_primaria['genero_textual_codigo'] = ['genero_textual_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['formato_data_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);

        if (count($va_resultado))
            $va_resultado = $va_resultado[0];

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['formato_data_codigo' => '1', 'formato_data_nome' => 'completo'];
        $va_itens['2'] = ['formato_data_codigo' => '2', 'formato_data_nome' => 'ano'];
        $va_itens['3'] = ['formato_data_codigo' => '3', 'formato_data_nome' => 'década'];
        $va_itens['3'] = ['formato_data_codigo' => '3', 'formato_data_nome' => 'século'];

        if (isset($pa_filtros_busca['formato_data_codigo']))
            return $va_resultado[] = $va_itens[$pa_filtros_busca['formato_data_codigo']];
        else
            return $va_itens;
    }

}

?>