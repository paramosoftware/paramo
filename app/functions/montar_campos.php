<?php
    if (!isset($vb_autenticar_usuario))
        $vb_autenticar_usuario = true;

    if ($vb_autenticar_usuario)
        require dirname(__FILE__) . "/autenticar_usuario.php";

    if (!isset($vb_aplicar_controle_acesso))
        $vb_aplicar_controle_acesso = true;

    $vb_atualizacao_campo = false;

    if (!isset($va_campos))
    {
        if (!isset($vs_id_objeto_tela))
        {
            if (isset($_GET['obj']))
                $vs_id_objeto_tela = $_GET['obj'];
            else
                exit();
        }

        // $vs_modo indica se estamos em edição ou em listagem (filtros)
        ////////////////////////////////////////////////////////////////
        
        if (!isset($vs_modo))
        {
            if (isset($_GET['modo']))
                $vs_modo = $_GET['modo'];
            else
                exit();
        }

        $vb_exibir_campo = true;

        if (!isset($vs_id_campo))
        {
            $vs_id_campo = "";
            if (isset($_GET['campo']))
                $vs_id_campo = $_GET['campo'];

            $vs_sufixo_nome_campo = "";
            if (isset($_GET['sufixo']))
                $vs_sufixo_nome_campo = $_GET['sufixo'];

            $vs_campo_pai = "";
            if (isset($_GET['campo_pai']))
                $vs_campo_pai = $_GET['campo_pai'];

            $vb_ler_valor_campo_correlato = "0";
            if (isset($_GET['valor_campo_correlato']))
                $vb_ler_valor_campo_correlato = $_GET['valor_campo_correlato'];

            if (isset($_GET['atualizacao']))
                $vb_atualizacao_campo = true;

            if (isset($_GET['exibir']))
                $vb_exibir_campo = $_GET['exibir'];

            if (isset($_GET['multiplas_instancias']))
                $vb_multiplas_instancias_campo = $_GET['multiplas_instancias'];

            // configurar_campos_tela.php precisa saber o código do objeto
            // para atualizar os campos dos representantes digitais
            //////////////////////////////////////////////////////////////
            
            if ($vb_atualizacao_campo && isset($_GET['cod']) && $_GET['cod'])
                $pn_objeto_codigo = $_GET['cod'];
        }

        require dirname(__FILE__) . "/configurar_campos_tela.php";

        // A avaliar o impacto: quando queremos só um campo específico (atualização via AJAX)
        // tem que pegar os parâmetros vindo do $_GET
        /////////////////////////////////////////////////////////////////////////////////////

        if ($vb_atualizacao_campo)
        {
            if (isset($_GET['cod']) && $_GET['cod'])
            {
                $pn_objeto_codigo = $_GET['cod'];

                $vs_id_objeto = "";

                if (!$vb_ler_valor_campo_correlato)
                    $vs_id_objeto = $vs_id_objeto_tela;
                elseif (isset($va_campos[$vs_id_campo]["campo_correlato"]["objeto"]))
                    $vs_id_objeto = $va_campos[$vs_id_campo]["campo_correlato"]["objeto"];

                if ($vs_id_objeto)
                {
                    $vo_objeto = new $vs_id_objeto;
                    $va_objeto = $vo_objeto->ler($pn_objeto_codigo, "ficha");
                }
                else
                    $va_objeto = $_GET;
            }
            else
                $va_objeto = $_GET;
        }
        
        if (!$vb_exibir_campo && isset($va_campos[$vs_id_campo]))
            $va_campos[$vs_id_campo]["nao_exibir"] = true;
    }

    $vs_campo_foco = "";

    if (!isset($va_abas_form))
        $va_abas_form = array();

    if (!count($va_abas_form) || $vb_atualizacao_campo)
    {
        $va_abas_form = array();
        $va_abas_form[]["campos"] = array_keys($va_campos);
    }

    if ( (count($va_abas_form) > 1) && !$vb_atualizacao_campo )
    {
    ?>

        <div class="row">
            <div class="btn-group me-2 espacamento-esquerda-10 flex-wrap">
                <?php
                $contador_abas = 1;
                foreach ($va_abas_form as $vs_key_aba => $va_aba)
                {
                ?>
                    <button class="btn btn-tab <?php
                        if ($contador_abas == 1)
                            print "btn-outline-primary active";
                        else
                            print "btn-outline-primary";
                    ?>"
                        id="<?php print $vs_key_aba; ?>" type="button">
                        <?php print $va_aba["label"]; ?>

                    </button>

                <?php
                    $contador_abas++;
                }
                ?>
            </div>
        </div>
    <?php
    }

    $contador = 1;
    foreach ($va_abas_form as $vs_key_aba => $va_aba)
    {
        if (!$vb_atualizacao_campo && ($vs_modo != "listagem") && isset($va_aba["campos"]) && count($va_aba["campos"]))
        {
        ?>
            <div class="tab" id="tab_<?php print $vs_key_aba; ?>" style="margin-top:10px;
            <?php
                if ($contador > 1)
                    print " display:none";
            ?>
            ">
        <?php
        }

        foreach ($va_aba["campos"] as $vs_campo_key)
        {
            if (isset($va_campos[$vs_campo_key]))
            {
                $va_parametros_campo = $va_campos[$vs_campo_key];

                $vs_formato = "";
                if (isset($va_parametros_campo["formato"]))
                    $vs_formato = $va_parametros_campo["formato"];

                $vo_campo = new $va_parametros_campo[0]($vs_id_objeto_tela, $va_parametros_campo["nome"], $vs_formato, $vs_modo);

                // No form de edição é aqui que eu controlo a exibição de instituições e acervos
                // conforme as permissões do usuário
                ///////////////////////////////////////////////////////////////////////////////

                if (isset($va_parametros_campo["atributo"]))
                {
                    if ( $vb_aplicar_controle_acesso && (in_array($va_parametros_campo["atributo"], array_keys($va_parametros_controle_acesso))) )
                    {
                        if (!in_array($va_parametros_controle_acesso[$va_parametros_campo["atributo"]], ["", "_ALL_"]))
                            $va_objeto[$va_parametros_campo["atributo"]] = $va_parametros_controle_acesso[$va_parametros_campo["atributo"]];
                    }
                    elseif (isset($va_objeto[$va_parametros_campo["nome"]]))
                        $va_objeto[$va_parametros_campo["atributo"]] = $va_objeto[$va_parametros_campo["nome"]];
                }

                if (!isset($va_objeto))
                    $va_objeto = array();

                if (!isset($va_objeto_portugues))
                    $va_objeto_portugues = array();

                $vo_campo->build($va_objeto, $va_parametros_campo, $va_recursos_sistema_permissao_edicao);

                if (isset($va_parametros_campo["foco"]))
                    $vs_campo_foco = $va_parametros_campo["nome"];
            }
        }

        if (!$vb_atualizacao_campo && ($vs_modo != "listagem") && isset($va_aba["campos"]) && count($va_aba["campos"]))
        {
        ?>
            </div>
        <?php
        }

        $contador++;
    }
?>