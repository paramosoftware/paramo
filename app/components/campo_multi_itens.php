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
    
    if (!isset($vn_numero_campo))
        $vn_numero_campo = 1;

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;

?>

<div class="mb-3" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
<?php
    if (!$vb_pode_exibir)
        print ' style="display:none"';
?>
>
    <label class="form-label" title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>">
        <?php if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
        {
        ?>
            <input type="checkbox" class="check-campo form-check-input" id="chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
        <?php
        }
        ?>

        <?php print $vs_label_campo; ?>
    </label><br>
    
    <div id="div_campos_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
    <?php
        if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            print ' style="display:none"';
    ?>
    >
    <?php
        $vb_tem_valores = false;

        if (isset($va_valor_campo))
        {
            if (count($va_valor_campo))
            {
                $vb_tem_valores = true;
                foreach($va_valor_campo as $va_valores_linha)
                {
                    $va_valores_linha_com_codigo = array();
                    foreach ($va_valores_linha as $vs_key_valor_linha => $v_valor)
                    {
                        $va_valores_linha_com_codigo[$vs_key_valor_linha . "_" . $vn_numero_campo] = $v_valor;
                    }

                    $va_valores_linha = $va_valores_linha_com_codigo;
                    
                    if (isset($pa_parametros_campo["dependencia_linha"]))
                    {
                        $va_valores_linha[$pa_parametros_campo["dependencia_linha"]["campo"]] = $pa_valores_form[$pa_parametros_campo["dependencia_linha"]["campo"]];
                    }

                    $vn_linha_codigo = $vn_numero_campo;

                    require dirname(__FILE__)."/../functions/linha.php";

                    $vn_numero_campo++;
                }
            }
        }
        
        if (!$vb_tem_valores && isset($pa_parametros_campo["numero_itens_inicial"]))
        {
            $vb_pode_remover = false;

            while ($vn_numero_campo <= (int) $pa_parametros_campo["numero_itens_inicial"])
            {   
                $va_valores_linha = array();
                $vn_linha_codigo = $vn_numero_campo;

                require dirname(__FILE__)."/../functions/linha.php";

                $vn_numero_campo++;
            }
        }
        ?>
    </div>

    <?php if (isset($pa_parametros_campo["modal"]) && ($pa_parametros_campo["modal"]))
    {
    ?>
        <script src="assets/libraries/bootstrap/js/bootstrap.min.js"></script>

        <div id="modal_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-body" id="modal_body_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
                    </div>

                    <div class="modal-footer">
                        <button id='btn_salvar_modal_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>' type="button" class="btn btn-outline-primary px-4">Adicionar</button>
                        <button id='closeModal' type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <?php if ( !isset($pa_parametros_campo["numero_maximo_itens"]) || ($vn_numero_campo <= $pa_parametros_campo["numero_maximo_itens"] ) )
    {
    ?>
        <button class="btn btn-primary px-4" type="button" id="btn_adicionar_campo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
        <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' style="display:none"';
        ?>
        >Adicionar</button>
    <?php
    }
    ?>

    <input type="hidden" id="numero_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" name="numero_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="<?php print ($vn_numero_campo-1); ?>"
    <?php
        if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            print ' disabled';
    ?>
    >
</div>

<script>

<?php if (isset($pa_parametros_campo["draggable"]))
{
?>

$(function()
{
    $("#div_campos_<?php print $vs_nome_campo_lookup ?>").sortable(
        {
            update: function(event, ui)
            {
                var va_lista_codigos_atualizada = "";

                $('.linha_<?php print $vs_nome_campo_lookup ?>').each(function(index)
                {
                    if (va_lista_codigos_atualizada == "")
                        va_lista_codigos_atualizada = $(this).attr("id").replace("linha_<?= $vs_nome_campo_lookup ?>_", "");
                    else
                        va_lista_codigos_atualizada = va_lista_codigos_atualizada + "|" + $(this).attr("id").replace("linha_<?php print $vs_nome_campo_lookup ?>_", "");
                });

                $('#<?php print $vs_nome_campo_codigos ?>').val(va_lista_codigos_atualizada);
            }
        });
});

<?php
}
?>

<?php if (isset($pa_parametros_campo["modal"]) && ($pa_parametros_campo["modal"]))
{
?>
    $(document).on('click', "#btn_salvar_modal_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>", function()
    {

    });
<?php
}
?>

$(document).on('click', "#btn_adicionar_campo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>", function()
{
    vb_alterou_cadastro = true;
    
    jQuery.ajaxSetup({async:false});

    vn_proximo_numero = parseInt($("#numero_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").val()) + 1;

    vs_filtro = "";

    <?php
    if (isset($pa_parametros_campo["dependencia_linha"]))
    {
        foreach($pa_parametros_campo["dependencia_linha"] as $v_campo_conexao)
        {
        ?>
            vs_filtro += '&<?php print $v_campo_conexao; ?>='+$("#<?php print $v_campo_conexao; ?>").val();
        <?php
        }
    }
    ?>

    vs_url_nova_linha = "functions/linha.php?tela=<?php print $vs_tela ?>&campo_lookup=<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>&codigo="+vn_proximo_numero+vs_filtro;
    
    //console.log(vs_url_nova_linha);

    $.get(vs_url_nova_linha, function(data, status)
    {
        <?php if (isset($pa_parametros_campo["modal"]) && ($pa_parametros_campo["modal"]))
        {
        ?>
            $("#modal_body_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").html(data);
            $("#btn_rem_<?php print $vs_nome_campo . $vs_sufixo_nome_campo . "_"; ?>"+vn_proximo_numero).hide();
            $("#modal_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").modal('show');

        <?php
        }
        else
        {
        ?>
            $("#div_campos_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").append(data);
        <?php
        }
        ?>
    });

    $("#numero_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").val(vn_proximo_numero);

    $("#div_campos_linha_<?php print $vs_nome_campo . $vs_sufixo_nome_campo . "_" ?>"+vn_proximo_numero).find(".input").first().focus();

    <?php if ( isset($pa_parametros_campo["numero_maximo_itens"]) )
    {
    ?>
        if (vn_proximo_numero == <?php print $pa_parametros_campo["numero_maximo_itens"] ?>)
            $("#btn_adicionar_campo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();

    <?php
    }
    ?>
}
);

$(document).on('click', "#chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>", function()
{
    $("#btn_adicionar_campo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").toggle();
    $("#div_campos_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").toggle();
    $("#numero_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>').prop('disabled'));
});


</script>

