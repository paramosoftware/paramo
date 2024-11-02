<?php

class edicao extends objeto_base
{

    function __construct()
    {
        $this->atributos = $this->inicializar_atributos();
        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
    }

    public function inicializar_chave_primaria()
    {
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();
        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();
        return $va_relacionamentos;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["edicao_codigo"] = ["nome" => "edicao_codigo"];
        $va_campos_visualizacao["edicao_nome"] = ["nome" => "edicao_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista([$this->chave_primaria[0] => $pn_codigo], $ps_visualizacao, 0, 1);

        if (count($va_resultado))
            $va_resultado = $va_resultado[0];

        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $contador = 1;
        while ($contador <= 100) {
            $va_itens[$contador] = ['edicao_codigo' => $contador, 'edicao_nome' => $contador . ".ª"];
            $contador++;
        }

        if (isset($pa_filtros_busca['edicao_codigo']))
            return $va_resultado[] = $va_itens[$pa_filtros_busca['edicao_codigo']];
        else
            return $va_itens;
    }

}

?>