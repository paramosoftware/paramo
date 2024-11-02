<?php

class tipo_usuario extends objeto_base
{

    function __construct()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_usuario_codigo"] = ["nome" => "tipo_usuario_codigo"];
        $va_campos_visualizacao["tipo_usuario_nome"] = ["nome" => "tipo_usuario_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_usuario_codigo'] = ['tipo_usuario_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['tipo_usuario_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['tipo_usuario_codigo' => '1', 'tipo_usuario_nome' => 'usuário interno'];
        $va_itens['2'] = ['tipo_usuario_codigo' => '2', 'tipo_usuario_nome' => 'administrador'];
        $va_itens['3'] = ['tipo_usuario_codigo' => '3', 'tipo_usuario_nome' => 'usuário externo (consulente)'];

        if (isset($pa_filtros_busca['tipo_usuario_codigo']))
            return $va_itens[$pa_filtros_busca['tipo_usuario_codigo']];
        else
            return $va_itens;
    }

}

?>
