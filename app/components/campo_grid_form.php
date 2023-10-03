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

    if (!isset($va_valor_campo))
        $va_valor_campo = array();

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;
?>

<div class="mb-3" id="div_<?php print $vs_nome_campo ?>"
<?php
if (!count($va_itens_campo))
    print " style=display:none";
?>
>					
    <label class="form-label">
        <?php print $vs_label_campo; ?>
    </label>
    
    <div class="input_campo_formulario">
        <?php
        foreach ($va_itens_campo as $vn_key_item_campo => $vs_valor_item_campo)
        {   
            $va_valores_linha_com_codigo = array();

            //if (isset($va_valor_campo[$vs_nome_campo]))
            {
                foreach ($va_valor_campo as $va_valores_linha)
                {
                    //$vn_linha_codigo = reset($va_valores_linha);
                    $vn_linha_codigo = $va_valores_linha[$vs_nome_campo][$pa_parametros_campo["atributos"][0]];
                    
                    //var_dump($va_valores_linha);

                    if ($vn_linha_codigo == $vn_key_item_campo)
                    {
                        $contador = 1;
                        foreach ($va_valores_linha as $vs_key_valor_linha => $v_valor)
                        {
                            $va_valores_linha_com_codigo[$vs_key_valor_linha . "_" . $vn_linha_codigo] = $v_valor;
                        }

                        break;
                    }
                }
            }

            $vn_linha_codigo = $vn_key_item_campo;
            $vs_linha_valor = $vs_valor_item_campo;

            $va_valores_linha = $va_valores_linha_com_codigo;
            $vn_valor_campo_codigo[] = $vn_linha_codigo;
            $vb_pode_remover = false;

            require dirname(__FILE__)."/../functions/linha.php";
        }

        $vn_valor_campo_codigo = join("|", $vn_valor_campo_codigo);
        ?>

        <input type="hidden" id="<?php print $vs_nome_campo ?>" name="<?php print $vs_nome_campo ?>" value="<?php print $vn_valor_campo_codigo; ?>"
        <?php
            if (!$vb_pode_exibir)
                print ' disabled';
        ?>
        >
    </div>
</div>