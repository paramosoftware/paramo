<?php
     if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];

    if (!isset($vb_valor_campo))
        $vb_valor_campo = 0;

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;
?>

<?php
if ( ($vs_ui_element == "linha") )
{
?>
    
        <div class="row mb-3">
            <div class="col-6">
                <input type="checkbox" class="form-check-input mt-0" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk"
                    <?php 
                    if ($vb_valor_campo)
                        print " checked";
                    ?> 
                > <?php print $vs_label_campo ?>

                <input type="hidden" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="<?php print $vb_valor_campo ?>">
            </div>
        </div>  

        <!--
        <label class="form-check-label padding-right-10">
        
        <label class="form-control cor-interna-edit no-border">
           
        </label>
        -->


<?php
}
else
{
?>

<div class="row mb-3" id="div_<?php print $vs_nome_campo ?>"
<?php
    if (!$vb_pode_exibir || (isset($pa_parametros_campo["nao_exibir"]) && $pa_parametros_campo["nao_exibir"]) )
        print ' style="display:none"';
?>
>
    
    <?php
    if ($vs_label_campo && (!isset($pa_parametros_campo["1_linha"]) || (isset($pa_parametros_campo["1_linha"]) && !$pa_parametros_campo["1_linha"])))
    {
    ?>
        <div class="label_campo_formulario">
            <?php if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            {
            ?>
                <input type="checkbox" class="check-campo form-check-input" id="chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
            <?php
            }
            ?>

            <label class="form-label" title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>">  
                <?php print $vs_label_campo; ?>
            </label>
        </div>
    <?php
    }
    ?>
    
    <div class="input_campo_formulario">
        
        <input type="checkbox" class="form-check-input" name="<?php print $vs_nome_campo ?>_chk" id="<?php print $vs_nome_campo ?>_chk" value="1"
        <?php 
        if ($vb_valor_campo)
            print " checked";
        ?>

        <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled style="display:none"';
        ?>
		>

        <?php
        if ($vs_label_campo && isset($pa_parametros_campo["1_linha"]) && $pa_parametros_campo["1_linha"])
        {
        ?>
            <label title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>">
                <?php print $vs_label_campo; ?>
            </label>
        <?php
        }
        ?>

        <input type="hidden" class="checkbox" name="<?php print $vs_nome_campo ?>" id="<?php print $vs_nome_campo ?>" value="<?php print $vb_valor_campo ?>"
        <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled';
        ?>
        >
    </div>
    
</div>

<?php
}
?>

<script>

$(document).on('click', "#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk", function()
{
    if ($(this).is(':checked'))
        v_valor_chk = "1";
    else
        v_valor_chk = "0";

    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").val(v_valor_chk);

    <?php
    if (isset($pa_parametros_campo["controlar_exibicao"]))
    {
        foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
        {
        ?>
            atualizar_exibicao_<?php print $vs_campo_controlar ?>(v_valor_chk);
        <?php
        }
    }
?>
});

$(document).on('click', "#chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>", function()
{
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk").toggle();
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").prop("disabled", !$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk").prop("disabled", !$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk').prop('disabled'));

    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_chk").focus();
});

<?php
if (isset($pa_parametros_campo["controlar_exibicao"]))
{
?>

function atualizar_dependencias_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>(pb_valor)
{
    <?php
    foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
    {
    ?>
        atualizar_exibicao_<?php print $vs_campo_controlar ?>(pb_valor);
    <?php
    }
    ?>
}

<?php
}
?>

</script>