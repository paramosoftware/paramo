<?php

class ordenacao
{
    private $campos_ordenacao;
    protected $visualizacoes = array();

    function __construct($pa_campos_ordenacao = array())
    {
        $this->campos_ordenacao = $pa_campos_ordenacao;

        $va_campos_visualizacao = array();
        $va_campos_visualizacao["codigo"] = ["nome" => "codigo", "exibir" => false];
        $va_campos_visualizacao["valor"] = ["nome" => "valor"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function get_visualizacao($ps_visualizacao)
    {
        return $this->visualizacoes[$ps_visualizacao];
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 0, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1)
    {
        //var_dump($this->campos_ordenacao);
        foreach ($this->campos_ordenacao as $vs_campo_ordenacao => $vs_label_campo_ordenacao) {
            $va_valores[] = ["codigo" => $vs_campo_ordenacao, "nome" => $vs_label_campo_ordenacao];
        }

        if (isset($pa_filtros_busca['ordenacao']))
            return $va_valores[$pa_filtros_busca['ordenacao']];
        else
            return $va_valores;
    }

}