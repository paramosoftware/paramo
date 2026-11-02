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
        $vn_objeto_codigo = (int) $va_parametros['cod'];

    if (!isset($va_parametros['vs']))
    {
        print "erro";
        exit();
    }
    else
        $vs_valor_selecao = $va_parametros['vs'];

    if (!is_string($vs_valor_selecao) || !preg_match('/\A[a-z][a-z0-9_]{0,63}\z/', $vs_valor_selecao))
    {
        print "erro";
        exit();
    }

    $vo_objeto = autorizacao::resolver_objeto($vs_id_objeto);

    if (!($vo_objeto instanceof objeto_base) || !autorizacao::autorizar_leitura_registro($vo_objeto, $vn_objeto_codigo))
    {
        print "erro";
        exit();
    }

    $va_objeto = $vo_objeto->ler($vn_objeto_codigo, "lista");

    if (!is_array($va_objeto) || !count($va_objeto))
    {
        print "erro";
        exit();
    }

    print ler_valor1($vs_valor_selecao, $va_objeto);
?>