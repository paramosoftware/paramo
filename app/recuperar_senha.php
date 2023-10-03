<?php

    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../autoload.php";
    }

    $vs_usuario_login = "";

    if (isset($_POST['usuario_login']))
        $vs_usuario_login = $_POST['usuario_login'];

    if (!$vs_usuario_login)
    {
        print "Login inválido!";
        exit();
    }
    
    $va_parametros_consulta["usuario_login"] = $vs_usuario_login;

    $vo_usuario = new usuario;
    $va_usuario = $vo_usuario->ler_lista($va_parametros_consulta, "lista", 0, 1);

    if (isset($va_usuario) && count($va_usuario))
    {
        $vb_senha_alterada = $vo_usuario->recuperar_senha($va_usuario[0]["usuario_codigo"], $va_usuario[0]["usuario_email"], $va_usuario[0]["usuario_nome"]);

        if ($vb_senha_alterada)
            print "Nova senha enviada para o e-mail cadastrado!";
        else
            print "Não foi possível enviar a nova senha.";
    }
    else
    {
        print "Login inválido!";
        exit();
    }
?>