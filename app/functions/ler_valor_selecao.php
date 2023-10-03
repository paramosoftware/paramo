<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";
    require_once dirname(__FILE__) . "/../components/ler_valor.php";
    
    if (count($_POST))
        $va_parametros = $_POST;
    else
        $va_parametros = $_GET;

    if (!isset($va_parametros['obj']))
    {
        print "erro";
        exit();
    }
    else
        $vs_id_objeto = $va_parametros['obj'];

    if (!isset($va_parametros['cod']))
    {
        print "erro";
        exit();
    }
    else
        $vn_objeto_codigo = $va_parametros['cod'];

    if (!isset($va_parametros['vs']))
    {
        print "erro";
        exit();
    }
    else
        $vs_valor_selecao = $va_parametros['vs'];

    $vo_objeto = new $vs_id_objeto;
    $va_objeto = $vo_objeto->ler($vn_objeto_codigo, "lista");

    print ler_valor1($vs_valor_selecao, $va_objeto);
?>