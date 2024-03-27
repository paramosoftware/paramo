<?php
    $vb_atualizacao_campo = false;
    if (isset($pa_parametros_campo["atualizacao"]))
        $vb_atualizacao_campo = $pa_parametros_campo["atualizacao"];

    if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    $vs_campo_pai = "";
    if (isset($pa_parametros_campo["campo_pai"]))
        $vs_campo_pai = $pa_parametros_campo["campo_pai"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];

    $vs_css_class = "";
    if (isset($pa_parametros_campo["css-class"]))
        $vs_css_class = $pa_parametros_campo["css-class"];

    if (!isset($va_itens_campo))
        $va_itens_campo = array();

    if (!isset($vs_valor_campo))
        $vs_valor_campo = '';

    $vs_valor_textual_campo = "";
    if (isset($va_itens_campo[$vs_valor_campo]))
        $vs_valor_textual_campo = $va_itens_campo[$vs_valor_campo];
    else
        $vs_valor_textual_campo = $vs_valor_campo;

    if (!isset($pa_parametros_campo["sem_valor"]))
        $vb_permitir_sem_valor = true;
    else
        $vb_permitir_sem_valor = $pa_parametros_campo["sem_valor"];

    if ( isset($pa_parametros_campo["valor_padrao"]) && (!$vs_valor_campo) )
        $vs_valor_campo = $pa_parametros_campo["valor_padrao"];

    $vb_exibir_campo_texto_se_vazio = false;
    if ( isset($pa_parametros_campo["exibir_campo_texto_se_vazio"]) )
        $vb_exibir_campo_texto_se_vazio = $pa_parametros_campo["exibir_campo_texto_se_vazio"];

    $vb_edicao_por_demanda = false;
    if ( isset($pa_parametros_campo["edicao_por_demanda"]) )
        $vb_edicao_por_demanda = $pa_parametros_campo["edicao_por_demanda"];

    $vb_exibir_campo = false;
    if ( (count($va_itens_campo) > 1) || (isset($pa_parametros_campo["exibicao_obrigatoria"]) && $pa_parametros_campo["exibicao_obrigatoria"]) )
        $vb_exibir_campo = true;
?>

<div class="mb-3 <?php print $vs_css_class; ?>" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" style="float:left; padding-right:10px;
<?php
if ( !(count($va_itens_campo) || $vb_exibir_campo_texto_se_vazio || $vb_exibir_campo) )
    print " display:none";
?>
">
        <?php
        if ($vb_edicao_por_demanda && $vs_valor_textual_campo && !$vb_atualizacao_campo)
        {
        ?>
            
            <span>
                <a href="#" onclick="carregar_valores_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>(); return false;"><?php print $vs_valor_textual_campo; ?></a>
                <input type="hidden" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" value="<?php print htmlentities($vs_valor_campo, ENT_QUOTES, "UTF-8", false); ?>">

                <?php if (isset($pa_parametros_campo["campo_tip"]) && $pa_parametros_campo["campo_tip"]) 
                { 
                ?>
                    <a class="link-sem-estilo" id="tip_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" onclick="ler_tip_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>('<?php print htmlentities($vs_valor_campo, ENT_QUOTES, "UTF-8", false); ?>'); return false;">
                         <svg class="icon"><use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-flag-alt"></use></svg>
                    </a>
                <?php
                }
                ?>
            </span>
        <?php
        }
        elseif ((count($va_itens_campo) > 1) || !$vb_exibir_campo_texto_se_vazio)
        {
        ?>
            <?php
            if ($vb_exibir_campo)
            {
            ?>
            
                <label class="form-label"><?php print $vs_label_campo; ?></label>
            <?php
            }
            ?>

            <select class="form-select" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"
            <?php
            if (!$vb_exibir_campo)
                print ' style="display:none"';
            ?>
            >
                <?php if ($vb_permitir_sem_valor)
                {
                ?>
                    <option value=""></option>
                <?php
                }
                ?>

                <?php
                foreach ($va_itens_campo as $vn_key_item_campo => $vs_valor_item_campo)
                {
                ?>
                    <option value="<?php print htmlentities($vn_key_item_campo, ENT_QUOTES, "UTF-8", false) ?>"
                    <?php
                    if ($vn_key_item_campo == $vs_valor_campo)
                    {
                        print " selected ";
                    }
                    ?>
                    ><?php print $vs_valor_item_campo ?></option>
                <?php
                }
                ?>
            </select>
            <?php if (isset($pa_parametros_campo["campo_tip"]) && $pa_parametros_campo["campo_tip"])
            {
            ?>
                <a class="link-sem-estilo" id="tip_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
                    <svg class="icon"><use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-flag-alt"></use></svg>
                </a>
            <?php
            }
            ?>
        <?php
        }
        elseif ($vb_exibir_campo_texto_se_vazio)
        {
        ?>
            <label class="form-label"><?php print $vs_label_campo; ?></label>
            <input type="text" class="form-control input" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="<?php print htmlentities($vs_valor_textual_campo, ENT_QUOTES, "UTF-8", false); ?>">
        <?php
        }
        ?>
</div>

<?php
if (isset($pa_parametros_campo["conectar"]) || isset($pa_parametros_campo["campo_tip"]))
{
?>

    <script>

    $(document).on('change', "#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>", function()
    {
        //Para chamar corretamente o $.get mais de uma vez
        jQuery.ajaxSetup({async:false});

        <?php
        if (isset($pa_parametros_campo["campo_tip"]))
        {
        ?>
            ler_tip_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>($(this).val());
        <?php
        }
        ?>

        <?php
        if (isset($pa_parametros_campo["conectar"]))
        {
            foreach($pa_parametros_campo["conectar"] as $v_conectar)
            {
            ?>
                vs_filtro = '&<?php print $v_conectar["atributo"]; ?>='+$(this).val();

                v_campos_conectar = $("#<?php print $v_conectar["campo"] . $vs_sufixo_nome_campo; ?>");
                for (let i = 0; i < v_campos_conectar.length; i++)
                {
                    va_campo_atualizar = v_campos_conectar[i].id;

                    vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_tela; ?>&campo_pai=<?php print $vs_campo_pai; ?>&campo=<?php print $v_conectar["campo"]; ?>&sufixo=<?php print $vs_sufixo_nome_campo; ?>&modo=edicao&atualizacao=1'+vs_filtro;

                    console.log(vs_url_campo_atualizado);
                    $.get(vs_url_campo_atualizado, function(data, status)
                    {
                        campos_formulario = $('.input-linha');
                        campo_atualizar = $("#div_"+va_campo_atualizar);
                        //campo_anterior_ao_atualizar = campos_formulario.eq(campos_formulario.index(campo_atualizar) - 1);
                        campo_anterior_ao_atualizar = $("#div_"+va_campo_atualizar).prev();

                        campo_atualizar.remove();
                        campo_anterior_ao_atualizar.after(data);
                    });
                }
            <?php
            }
        }
        ?>
    });


    </script>

<?php
}
?>

<?php
if (isset($pa_parametros_campo["controlar_exibicao"]))
{
?>

    <script>

    $(document).on('change', "#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>", function()
    {
    <?php
    foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
    {
    ?>
        atualizar_exibicao_<?php print $vs_campo_controlar ?>('<?php print $vs_sufixo_nome_campo; ?>', $(this).val());
    <?php
    }
    ?>
    });

    </script>

<?php
}
?>

<?php
if (isset($pa_parametros_campo["edicao_por_demanda"]))
{
?>

    <script>

    function carregar_valores_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>()
    {
        vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_tela; ?>&campo_pai=<?php print $vs_campo_pai; ?>&campo=<?php print $vs_nome_campo; ?>&sufixo=<?php print $vs_sufixo_nome_campo; ?>&modo=edicao&atualizacao=1&<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>=<?php print $vs_valor_campo; ?>';

        //console.log(vs_url_campo_atualizado);
        $.get(vs_url_campo_atualizado, function(data, status)
        {
            var v_field_to_update = document.querySelector("#div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>");

            var v_updated_field = document.createElement('div');
            v_updated_field.innerHTML = data;

            v_field_to_update.parentNode.replaceChild(v_updated_field, v_field_to_update);
        });
    }

    </script>

<?php
}
?>

<?php
if (isset($pa_parametros_campo["campo_tip"]))
{
?>

    <script>

    function ler_tip_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>(ps_objeto_codigo)
    {
        vs_url_valor_tip = 'functions/ler_valor_selecao.php?obj=<?php print $pa_parametros_campo["objeto"]; ?>&cod='+ps_objeto_codigo+"&vs=<?php print $pa_parametros_campo["campo_tip"]; ?>";

        //console.log(vs_url_valor_tip);
        $.get(vs_url_valor_tip, function(data, status)
        {
            $("#tip_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").attr("title", data);
        });
    }

    </script>

<?php
}
?>
