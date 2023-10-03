<?php
    require_once dirname(__FILE__) . "/autenticar_usuario.php";
    
    if (!isset($_GET['obj']))
    {
        print "erro";
        exit();
    }
    else
        $vs_id_objeto = $_GET['obj'];

    if (!isset($_GET['campo']))
    {
        print "erro";
        exit();
    }
    else
        $vs_campo = $_GET['campo'];

    $va_registro_atual = array();
    $va_registros_filhos = array();
    $va_registros_pais = array();

    $vo_objeto = new $vs_id_objeto('');

    $va_campos = $vo_objeto->get_campos_edicao();
    $va_campo = $va_campos[$vs_campo];

    if (isset($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]]))
        $va_registro_atual[] = $_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]];
    
    // Vamos ver se o campo é de pai é ou de filhos
    ///////////////////////////////////////////////
    
    if ($vs_campo == $va_campo["prevenir_circularidade"]["atributo_pai"])
    {
        if (isset($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]]))
        {
            // Recupera os ramos inferiores e superiores já salvos no banco

            $va_registros_filhos = $vo_objeto->ler_codigos_ramo_inferior($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]], $va_campo["prevenir_circularidade"]["atributo_pai"]);
            $va_registros_pais = $vo_objeto->ler_codigos_ramo_superior($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]], $va_campo["prevenir_circularidade"]["atributo_pai"], 2);
        }
        
        // Recupera o ramo inferior dos filhos escolhidos no formulário
        ///////////////////////////////////////////////////////////////

        if (isset($_GET[$va_campo["prevenir_circularidade"]["atributo_filho"]]))
        {
            $va_codigos_filhos = explode("|", $_GET[$va_campo["prevenir_circularidade"]["atributo_filho"]]);

            foreach($va_codigos_filhos as $vn_filho_codigo)
            {
                if (!in_array($vn_filho_codigo, $va_registros_filhos))
                {
                    $va_registros_filhos = array_merge($va_registros_filhos, $vo_objeto->ler_codigos_ramo_inferior($vn_filho_codigo, $va_campo["prevenir_circularidade"]["atributo_pai"]));
                    $va_registros_filhos[] = $vn_filho_codigo;
                }
            }
        }
    }
    elseif ($vs_campo == $va_campo["prevenir_circularidade"]["atributo_filho"])
    {
        if (isset($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]]))
        {
            // Recupera os ramos inferiores e superiores já salvos no banco

            $va_registros_filhos = $vo_objeto->ler_codigos_ramo_inferior($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]], $va_campo["prevenir_circularidade"]["atributo_pai"], 2);
            $va_registros_pais = $vo_objeto->ler_codigos_ramo_superior($_GET[$va_campo["prevenir_circularidade"]["atributo_chave"]], $va_campo["prevenir_circularidade"]["atributo_pai"]);
        }
        
        // Recupera o ramo inferior dos filhos escolhidos no formulário
        ///////////////////////////////////////////////////////////////

        if (isset($_GET[$va_campo["prevenir_circularidade"]["atributo_filho"]]))
        {
            $va_codigos_filhos = explode("|", $_GET[$va_campo["prevenir_circularidade"]["atributo_filho"]]);

            foreach($va_codigos_filhos as $vn_filho_codigo)
            {
                if (!in_array($vn_filho_codigo, $va_registros_filhos))
                {
                    $va_registros_filhos = array_merge($va_registros_filhos, $vo_objeto->ler_codigos_ramo_inferior($vn_filho_codigo, $va_campo["prevenir_circularidade"]["atributo_pai"]));
                    $va_registros_filhos[] = $vn_filho_codigo;
                }
            }
        }

        // Recupera o ramo inferior dos filhos escolhidos no formulário
        ///////////////////////////////////////////////////////////////

        if (isset($_GET[$va_campo["prevenir_circularidade"]["atributo_pai"]]))
        {
            if (!in_array($_GET[$va_campo["prevenir_circularidade"]["atributo_pai"]], $va_registros_pais))
            {
                $va_registros_pais = array_merge($va_registros_pais, $vo_objeto->ler_codigos_ramo_superior($_GET[$va_campo["prevenir_circularidade"]["atributo_pai"]], $va_campo["prevenir_circularidade"]["atributo_pai"]));
                $va_registros_pais[] = $_GET[$va_campo["prevenir_circularidade"]["atributo_pai"]];
            }
        }
    }

    // Não pode receber como registro pai os registros filhos, os pais do pai e ele mesmo
    /////////////////////////////////////////////////////////////////////////////////////

    print implode("|", array_unique(array_merge($va_registros_filhos, $va_registros_pais, $va_registro_atual)));
?>