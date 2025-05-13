<?php

class modelo_etiqueta extends objeto_base
{

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["modelo_etiqueta_codigo"] = ["nome" => "modelo_etiqueta_codigo"];
        $va_campos_visualizacao["modelo_etiqueta_nome"] = ["nome" => "modelo_etiqueta_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['modelo_etiqueta_codigo'] = ['modelo_etiqueta_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['modelo_etiqueta_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 0, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['modelo_etiqueta_codigo' => '1', 'modelo_etiqueta_nome' => 'Formato livro'];
        $va_itens['2'] = ['modelo_etiqueta_codigo' => '2', 'modelo_etiqueta_nome' => 'Formato caixa'];

        if (isset($pa_filtros_busca['modelo_etiqueta_codigo']))
            return $va_itens[$pa_filtros_busca['modelo_etiqueta_codigo']];
        else
            return $va_itens;
    }

}

?>