<?php

class tipo_entidade extends objeto_base
{

    private $nome;

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_entidade_codigo"] = ["nome" => "tipo_entidade_codigo"];
        $va_campos_visualizacao["tipo_entidade_nome"] = ["nome" => "tipo_entidade_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();
        return $va_atributos;
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['tipo_entidade_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);
        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['tipo_entidade_codigo' => '1', 'tipo_entidade_nome' => 'Física'];
        $va_itens['2'] = ['tipo_entidade_codigo' => '2', 'tipo_entidade_nome' => 'Jurídica'];
        $va_itens['3'] = ['tipo_entidade_codigo' => '3', 'tipo_entidade_nome' => 'Coletiva'];

        if (isset($pa_filtros_busca['tipo_entidade_codigo']))
            return $va_resultado[] = $va_itens[$pa_filtros_busca['tipo_entidade_codigo']];
        else
            return $va_itens;
    }

}

?>