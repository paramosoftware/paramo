<?php
    if (!isset($vs_tela))
        exit();

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

    if (!isset($pa_parametros_campo["sem_valor"]))
        $vb_permitir_sem_valor = true;
    else
        $vb_permitir_sem_valor = $pa_parametros_campo["sem_valor"];
?>

<div class="mb-3" id="div_<?php print $vs_nome_campo ?>"
<?php
if (!count($va_itens_campo))
    print " style=display:none";
?>
>					
    <label class="form-label">
        <?php print $vs_label_campo; ?>
    </label><br>
            
    <?php
    $contador = 1;
    foreach ($va_itens_campo as $vn_key_item_campo => $vs_valor_item_campo)
    {
        if ( (!$vb_permitir_sem_valor) && (!$vs_valor_campo) && ($contador == 1) )
            $vs_valor_campo = $vn_key_item_campo;
            
    ?>
        <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="<?php print $vs_nome_campo ?>" id="<?php print $vs_nome_campo ?>" value="<?php print htmlspecialchars($vn_key_item_campo) ?>"
                <?php 
                    if ($vn_key_item_campo == $vs_valor_campo)
                        print " checked ";

                    if (isset($pa_parametros_campo["itens_desabilitados"]))
                    {
                        if (in_array($vn_key_item_campo, $pa_parametros_campo["itens_desabilitados"]))
                            print " disabled ";
                    }
                ?>
            ><label class="form-check-label"><?php print $vs_valor_item_campo ?></label>
        </div>
    <?php

        $contador++;
    }
    ?>
</div>

<?php

if (isset($pa_parametros_campo["conectar"]))
{
    //$vs_campo_a_conectar = $pa_parametros_campo["conectar"]["atributo"];
?>

<script>

$(document).on('change', "#<?php print $vs_nome_campo ?>", function()
{
   
    vs_filtro = '&<?php print $vs_nome_campo; ?>='+$(this).val();

<?php 
foreach($pa_parametros_campo["conectar"] as $v_conectar)
{
?>
    vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_tela; ?>&campo=<?php print $v_conectar["campo"]; ?>&modo=edicao'+vs_filtro;
    
    //console.log(vs_url_campo_atualizado);
    $.get(vs_url_campo_atualizado, function(data, status) 
    {
        campos_formulario = $('.campo_formulario');
        campo_atualizar = $("#div_<?php print $v_conectar["campo"]; ?>");
        campo_anterior_ao_atualizar = campos_formulario.eq(campos_formulario.index(campo_atualizar) - 1)

        campo_atualizar.remove();
        campo_anterior_ao_atualizar.after(data);
    });
<?php
}
?>
}
);

</script>

<?php
}
?>