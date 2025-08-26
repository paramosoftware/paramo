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

    if (!isset($vn_largura_campo))
        $vn_largura_campo = '';

    if (!isset($vn_tamanho_maximo))
        $vn_tamanho_maximo = '';

    if (!isset($vb_permitir_sem_valor))
        $vb_permitir_sem_valor = true;
?>

<div class="mb-3" id="div_<?php print $vs_nome_campo ?>">	
    <!--
    <label class="form-label">
        <?php print $vs_label_campo; ?>
    </label>
    -->
    
    <?php
    if (count($va_itens_campo))
    {
    ?>
        <select class="form-select" multiple="yes" id="lista_<?php print $vs_nome_campo ?>" size="8">
            <?php
            $contador = 1;
            foreach ($va_itens_campo as $vn_key_valor_campo => $vs_valor_valor_campo)
            {
            ?>
                    <option value="<?php print $vn_key_valor_campo ?>"
                    <?php if ($contador == 1)
                        print " selected ";
                    ?>
                    ><?php print $vs_valor_valor_campo ?></option>
            <?php
                $contador++;
            }
            ?>
        </select>
    <?php
    }
    elseif (!isset($pa_parametros_campo["permitir_entrada_avulsa"]) || (isset($pa_parametros_campo["permitir_entrada_avulsa"]) && !$pa_parametros_campo["permitir_entrada_avulsa"]))
    {
    ?>
        <div class="nenhuma-correspondencia" style="margin-left:5px">Nenhuma correspondência encontrada.</div>
    <?php
    }
    ?>
    
    <!--
    <div style="float:right">
        <input type="button" id="btn_descartar" value="Descartar sugestões">
    </div>
    -->
</div>

<script>

$(document).on('click', "#btn_descartar", function()
{
    $("#div_sugestoes_<?php print $vs_nome_campo ?>").empty();
});

</script>