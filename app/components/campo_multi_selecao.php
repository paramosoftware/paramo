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
        $va_valor_campo = array();
    else
        $va_valor_campo = $vs_valor_campo;

    if (!$va_valor_campo)
        $va_valor_campo = array();

    if (!isset($pa_parametros_campo["sem_valor"]))
        $vb_permitir_sem_valor = true;
    else
        $vb_permitir_sem_valor = $pa_parametros_campo["sem_valor"];

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;

    $va_valores_codigos = array();

    if (is_array($va_valor_campo))
    {
        foreach($va_valor_campo as $vn_valor_codigo)
        {
            if (is_array($vn_valor_codigo))
                $va_valores_codigos[] = $vn_valor_codigo[$vs_nome_campo][$pa_parametros_campo["atributos"][0]];
            else
                $va_valores_codigos[] = $vn_valor_codigo;
        }
    }
    else
        $va_valores_codigos[] = $va_valor_campo;

   // if ( isset($pa_parametros_campo["valor_padrao"]) && (!count($va_valores_codigos)) )
        //$va_valores_codigos[] = $pa_parametros_campo["valor_padrao"];
?>

<div class="mb-3" id="div_<?php print $vs_nome_campo ?>"
<?php
if (!count($va_itens_campo) || !$vb_pode_exibir)
    print " style=display:none";
?>
>					
    <label class="form-label">
        <?php print $vs_label_campo; ?>
    </label><br>
    
    <div class="form-check form-check-inline">
        
        <?php
            $contador = 1;
            foreach ($va_itens_campo as $vn_key_item_campo => $vs_valor_item_campo)
            {                
            ?>
                <div>
                <input type="checkbox" class="form-check-input chk_<?php print $vs_nome_campo ?>" value="<?php print $vn_key_item_campo ?>"
                <?php if (in_array($vn_key_item_campo, $va_valores_codigos))
                {
                    print " checked";
                }
                ?>
                ><label class="form-check-label"><?php print $vs_valor_item_campo ?></label>
                </div>
            <?php
                $contador++;
            }

            $vn_valor_campo_codigo = join("|", $va_valores_codigos);
        ?>    
        

        <input type="hidden" id="<?php print $vs_nome_campo ?>" name="<?php print $vs_nome_campo ?>" value="<?php print $vn_valor_campo_codigo; ?>"
        <?php
            if (!$vb_pode_exibir)
                print ' disabled';
        ?>
        >
    </div>
</div>

<script>

$(document).on('click', ".chk_<?php print $vs_nome_campo ?>", function()
{
    if ($(this).is(':checked'))
    {
        if ($("#<?php print $vs_nome_campo ?>").val().length == 0)
        {
            $("#<?php print $vs_nome_campo ?>").val($(this).val());
        }
        else
        {
            va_codigos = $("#<?php print $vs_nome_campo ?>").val().split("|");

            if (!va_codigos.includes($(this).val().toString()))
            {
                va_lista_codigos_atualizada = $("#<?php print $vs_nome_campo ?>").val() + "|" + $(this).val();
                $("#<?php print $vs_nome_campo ?>").val(va_lista_codigos_atualizada);
            }
        }
    }
    else
    {
        va_codigos = $("#<?php print $vs_nome_campo ?>").val().split("|");
        va_codigos.splice($.inArray($(this).val(), va_codigos), 1);

        va_lista_codigos_atualizada = "";
        
        for (vn_key in va_codigos) 
        {
            if (va_lista_codigos_atualizada == "")
                va_lista_codigos_atualizada = va_codigos[vn_key];
            else
                va_lista_codigos_atualizada = va_lista_codigos_atualizada + "|" + va_codigos[vn_key];
        }

        $("#<?php print $vs_nome_campo ?>").val(va_lista_codigos_atualizada);        
    }

    <?php
    if (isset($pa_parametros_campo["controlar_exibicao"]))
    {
        foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
        {
        ?>
            atualizar_exibicao_<?php print $vs_campo_controlar ?>($("#<?php print $vs_nome_campo ?>").val());
        <?php
        }
    }
    ?>

});

</script>