<?php

class genero_gramatical extends objeto_base
{

    private $nome;

    function __construct()
    {
        /*
        $this->tabela_banco = "GENERO_GRAMATICAL";
        $this->chave_primaria = $this->inicializar_chave_primaria();

        // [primary key, ui, class, db, tipo]
        $this->atributos['genero_textual_nome'] = ['genero_textual_nome', 'nome', 'Nome', 's'];
        */

        $va_campos_visualizacao = array();
        $va_campos_visualizacao["genero_gramatical_codigo"] = ["nome" => "genero_gramatical_codigo"];
        $va_campos_visualizacao["genero_gramatical_nome"] = ["nome" => "genero_gramatical_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function inicializar_chave_primaria()
    {
        //return $va_chave_primaria['genero_textual_codigo'] = ['genero_textual_codigo', 'codigo', 'Codigo', 'i'];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();
        return $va_atributos;
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = $this->ler_lista(['genero_gramatical_codigo' => $pn_codigo], $ps_visualizacao, 0, 1);
        return $va_resultado;
    }

    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_itens = array();
        $va_resultado = array();

        $va_itens['1'] = ['genero_gramatical_codigo' => '1', 'genero_gramatical_nome' => 'feminino'];
        $va_itens['2'] = ['genero_gramatical_codigo' => '2', 'genero_gramatical_nome' => 'masculino'];
        $va_itens['3'] = ['genero_gramatical_codigo' => '3', 'genero_gramatical_nome' => 'neutro'];

        if (isset($pa_filtros_busca['genero_gramatical_codigo']))
            return $va_resultado[] = $va_itens[$pa_filtros_busca['genero_gramatical_codigo']];
        else
            return $va_itens;
    }

}

?>