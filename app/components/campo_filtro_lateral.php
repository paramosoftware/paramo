<?php
    if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];

    if (!isset($va_itens_campo))
        $va_itens_campo = array();

    if (!isset($vs_valor_campo))
        $vs_valor_campo = '';

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;
?>

<?php if ( $vs_valor_campo || (count($va_itens_campo) > 0) )
{
?>

<div class="p-4 nav-group" id="div_<?php print $vs_nome_campo ?>"
<?php
if (!count($va_itens_campo) || !$vb_pode_exibir)
    print " style=display:none";
?>
>
    <div class="label_campo_formulario">
        <?php print $vs_label_campo; ?>
    </div>
    
    <div class="input_campo_formulario">
        <ul class="ul-filtro-lateral">
            <?php
            $contador = 1;
            foreach ($va_itens_campo as $vn_key_item_campo => $vs_valor_item_campo)
            {                  
                if ($vs_valor_campo)
                {
                ?>
                    <span class="span-x-filtro-lateral" id="rem_<?php print $vs_nome_campo . '_' . $vn_key_item_campo; ?>">x&nbsp;&nbsp;</span>
                <?php
                }
                ?>

                <li class="<?php print $vs_nome_campo; ?> linha li-filtro-lateral" id="<?php print $vs_nome_campo . '_' . $vn_key_item_campo; ?>" title="<?php print $vs_valor_item_campo; ?>">
                    
                    <?php
                        //if (strlen($vs_valor_item_campo) > 30)
                            //print substr($vs_valor_item_campo, 0, 30) . "...";
                        //else
                            print $vs_valor_item_campo;
                    ?>
                </li>
            
            <?php
                $contador++;
            }
            ?>            
        </ul>
    </div>

    <input type="hidden" name="<?php print $vs_nome_campo; ?>" id="<?php print $vs_nome_campo; ?>" value="<?php print htmlentities($vs_valor_campo, ENT_QUOTES, "UTF-8", false); ?>"
    <?php
        if (!$vs_valor_campo)
            print " disabled;"
    ?>
    >
</div>

<script>

$(document).on('click', "li.<?php print $vs_nome_campo ?>", function()
{
    vn_item_codigo = ($(this).attr('id').replace('<?php print $vs_nome_campo ?>_', ''));
    $("#<?php print $vs_nome_campo ?>").val(vn_item_codigo);

    $("#<?php print $vs_nome_campo ?>").prop('disabled', false);

    $("#form_lista").submit();
});

$(document).on('click', "#rem_<?php print $vs_nome_campo . '_' . $vn_key_item_campo; ?>", function()
{
    vn_item_codigo = parseInt($(this).attr('id').replace('rem_<?php print $vs_nome_campo ?>_', ''));
    
    $("#<?php print $vs_nome_campo ?>").prop('disabled', true);

    <?php 
    if (isset($pa_parametros_campo["filtros_dependentes"]))
    {
        foreach($pa_parametros_campo["filtros_dependentes"] as $vs_filtro_dependente)
        {
        ?>
            $("#<?php print $vs_filtro_dependente ?>").prop('disabled', true);
        <?php
        }
    }
    ?>

    $("#form_lista").submit();
});

</script>

<?php
}
?>