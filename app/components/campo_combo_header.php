<?php
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

    if (!isset($va_itens_campo))
        $va_itens_campo = array();

    if (!isset($vs_valor_campo))
        $vs_valor_campo = '';

    if (!isset($pa_parametros_campo["sem_valor"]))
        $vb_permitir_sem_valor = true;
    else
        $vb_permitir_sem_valor = $pa_parametros_campo["sem_valor"];

    if ( isset($pa_parametros_campo["valor_padrao"]) && (!$vs_valor_campo) )
        $vs_valor_campo = $pa_parametros_campo["valor_padrao"];
?>

<select class="form-select" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">       
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
        <option value="<?php print $vn_key_item_campo ?>"
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
