<?php

class identificador_disponivel
{

    function __construct()
    {
    }

    public function inicializar_chave_primaria()
    {
    }

    public function ler()
    {
        $vo_conexao = new Conexao;
        $vo_conexao->conectar();

        $vs_select = "SELECT DISTINCT MIN(identificador) as identificador FROM identificador_texto_disponivel";

        $va_resultado = $vo_conexao->consultar($vs_select);

        if (count($va_resultado) && isset($va_resultado[0]["identificador"]))
            return $va_resultado[0]["identificador"];
        else
            return "";
    }

}

?>