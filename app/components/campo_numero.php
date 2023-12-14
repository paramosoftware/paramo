<?php
     if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];

    if (!isset($vs_valor_campo))
        $vs_valor_campo = '';

    if (!isset($vn_largura_campo))
        $vn_largura_campo = '';

    $vn_tamanho_maximo = "";
    if (isset($pa_parametros_campo["tamanho_maximo"]))
        $vn_tamanho_maximo = $pa_parametros_campo["tamanho_maximo"];

    if (!isset($pa_parametros_campo["escopo"]))
        $vs_escopo = '';
    else
        $vs_escopo = $pa_parametros_campo["escopo"];

    if (!isset($pa_parametros_campo["formato"]))
        $vs_formato = '';
    else
        $vs_formato = $pa_parametros_campo["formato"];

    if (!isset($pa_parametros_campo["modo"]))
        $vs_modo = '';
    else
        $vs_modo = $pa_parametros_campo["modo"];

    $vb_hidden = false;
    if (isset($pa_parametros_campo["nao_exibir"]))
        $vb_hidden = true;

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;

    //$vs_sufixo_nome_campo = "";
?>

<?php
if ( ($vs_escopo == "interno") || ($vs_ui_element == "linha") )
{
?>
    <div class="mb-3 w-25" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"
    <?php
        if ( !$vb_pode_exibir || $vb_hidden )
            print ' style="display:none"';
    ?>
    >
        <label class="form-label">
            <?php print $vs_label_campo; ?>
        </label>
       
        <input type="number" class="form-control input" size="<?php print $vn_tamanho_maximo; ?>" max="<?php print $vn_tamanho_maximo; ?>" name="<?php print $vs_nome_campo  . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo  . $vs_sufixo_nome_campo ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>">        
    </div>
<?php
}

else
{
?>
    <div class="mb-3" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"
    <?php
        if ( !$vb_pode_exibir || $vb_hidden )
            print ' style="display:none"';
    ?>
    >        
        <?php
        if ($vs_label_campo)
        {
        ?>
            <label class="form-label">
                <?php if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                {
                ?>
                    <input type="checkbox" class="check-campo form-check-input" id="chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>">
                <?php
                }
                ?>

                <?php print $vs_label_campo; ?>
            </label>
        <?php
        }
        ?>

        <input type="number" class="form-control input"  size="<?php print $vn_tamanho_maximo; ?>" max="<?php print $vn_tamanho_maximo; ?>"  name="<?php print $vs_nome_campo  . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo  . $vs_sufixo_nome_campo ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>"
        <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled style="display:none"';

            if (isset($pa_parametros_campo["readonly"]))
                print ' readonly';
        ?>
        >
        
    </div>
<?php
}
?>

<script>

$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>').keyup(function (event)
{
  if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) 
  {
    // 0 for null value
    // 8 for backspace
    // 48-57 for 0-9 numbers

    $(this).val(function(index, value) 
    {
        return value.replace(/\D/g, "");
    });
  }
});

<?php if ($vn_tamanho_maximo)
{
?>

$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>').keypress(function (event)
{
    if (this.value >= <?php print $vn_tamanho_maximo; ?>)
        event.preventDefault();
});

<?php
}
?>

$(document).on('click', "#chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>", function()
{
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").toggle();
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").focus();
});


</script>