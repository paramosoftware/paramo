<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";
    
    $vb_form_valido = true;
    if (!isset($_POST['obj']))
    {
        print "0";
        exit();
    }

    $vs_modo = "edicao";
    require_once dirname(__FILE__) . "/configurar_campos_tela.php";

    foreach($va_campos as $va_parametros_campo)
    {
        $vo_campo = new $va_parametros_campo[0]($vs_id_objeto_tela, $va_parametros_campo["nome"]);
        $vs_resposta_validacao = $vo_campo->validar_valores($_POST, $va_parametros_campo);

        if (!($vs_resposta_validacao === true))
        {
            print $vs_resposta_validacao;
            exit();
        }        
    }

    // Vamos validar agora os dados a partir do banco (padrão: duplicação de um mesmo valor)

    $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);

    $vs_campo_duplicado = $vo_objeto->verificar_valores_duplicados($_POST);

    if ($vs_campo_duplicado != 1)
    {
        print "Valor inserido no campo '" . $va_campos[$vs_campo_duplicado]["label"] . "' já existe e não pode ser repetido!";
        exit();
    }

    print "1";
?>