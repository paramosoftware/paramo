<?php
     if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];

    $vs_css_class = "";
    if (isset($pa_parametros_campo["css-class"]))
        $vs_css_class = $pa_parametros_campo["css-class"];

    if (!isset($vs_valor_campo))
        $vs_valor_campo = '';

    if (!isset($vs_valor_campo_portugues))
        $vs_valor_campo_portugues = '';

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

    if (!isset($pa_parametros_campo["numero_linhas"]))
        $vn_numero_linhas = 1;
    else
        $vn_numero_linhas = $pa_parametros_campo["numero_linhas"];

    $vb_hidden = false;
    if (isset($pa_parametros_campo["nao_exibir"]))
        $vb_hidden = true;

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;
?>

<?php
if ( ($vs_escopo == "interno") || ($vs_ui_element == "linha") )
{
?>
    <?php 
    if ($vs_formato == "rich")
    {
        $vs_nome_campo = str_replace(".0.", "_0_", $vs_nome_campo);
    ?>
        <div class="col-2">
            <label class="form-label"><?php print $vs_label_campo; ?></label>
        </div>

        <script src="assets/libraries/ckeditor/ckeditor.js"></script>
        <textarea class="texto input" rows="<?php print $vn_numero_linhas ?>" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"><?php print htmlspecialchars($vs_valor_campo); ?></textarea>
        
        <script>
            ClassicEditor
                .create( document.querySelector('#<?php print $vs_nome_campo  . $vs_sufixo_nome_campo; ?>') )
                .catch( error => {
                    console.error( error );
                });
        </script>
    <?php
    }
    elseif ($vs_formato == "date")
    {
    ?>
        <div class="mb-3" style="float:left; padding-right:10px;
        <?php
            if ( !$vb_pode_exibir || $vb_hidden )
                print ' display:none';
        ?>">
            <div class="">
                <label class="form-label"><?php print $vs_label_campo; ?></label>
                <input type="date" class="form-control input" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>">
            </div>
        </div>
    <?php
    }
    elseif ($vs_formato == "full")
    {
    ?>
        <div class="row mb-3" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
        <?php
            if ( !$vb_pode_exibir || $vb_hidden )
                print ' style="display:none"';
        ?>
        >
            <div class="col-2">
                <label class="form-label"><?php print $vs_label_campo; ?></label>
            </div>

            <div class="col-4">
                <input type="text" class="form-control input" size="<?php print $vn_tamanho_maximo; ?>" maxlength="<?php print $vn_tamanho_maximo; ?>"  name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>">
            </div>
        </div>
    <?php
    }
    elseif ($vn_numero_linhas == 1)
    {
    ?>
        <div class="mb-3 <?php print $vs_css_class; ?>" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" style="float:left; padding-right:10px;
        <?php
            if ( !$vb_pode_exibir || $vb_hidden )
                print ' display:none';
        ?>
        ">
            <div class="">
                <label class="form-label"><?php print $vs_label_campo; ?></label>
                <input type="text" class="form-control input" size="<?php print $vn_tamanho_maximo; ?>" maxlength="<?php print $vn_tamanho_maximo; ?>"  name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>">
            </div>
        </div>
    <?php
    }
    else
    {
    ?>
        <div class="mb-3" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
        <?php
            if ( !$vb_pode_exibir || $vb_hidden )
                print ' style="display:none"';
        ?>
        >
            <div class="col-4 mt-3">
                <label class="form-label mt-3"><?php print $vs_label_campo; ?></label>
            </div>

            <div class="col-6">
                <textarea class="form-control input" rows="<?php print $vn_numero_linhas ?>" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"><?php print htmlspecialchars($vs_valor_campo);; ?></textarea>
            </div>
        </div>
    <?php
    }
    ?>
<?php
}

else
{
?>
    <div class="mb-3" id="div_<?php print $vs_nome_campo  . $vs_sufixo_nome_campo; ?>"
    <?php
        if ( !$vb_pode_exibir || $vb_hidden )
            print ' style="display:none"';
    ?>
    >        
        <?php
        if ($vs_label_campo)
        {
        ?>
            <label class="form-label" title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>">
                <?php if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                {
                ?>
                    <input type="checkbox" class="check-campo" id="chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>">
                <?php
                }
                ?>

                <?php 
                    print $vs_label_campo;
                ?>
            </label>
        <?php
        }
        ?>

        <?php
        if ($vs_valor_campo_portugues)
        {
        ?>
            <div style="border:1px solid; max-height:25px; overflow:hidden; margin:0px 10px 5px 5px; padding:5px 5px 5px 5px">
                <?php print htmlspecialchars($vs_valor_campo_portugues); ?>
            </div>
        <?php
        }
        ?>

        <?php 
        if ($vs_formato == "senha")
        {
        ?>
            <input type="password" class="form-control input" size="<?php print $vn_tamanho_maximo; ?>" maxlength="<?php print $vn_tamanho_maximo; ?>"  name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>"
            <?php
                if (!$vb_pode_exibir)
                    print ' disabled';
            ?>
            >
        <?php
        }
        elseif ($vs_formato == "rich")
        {
            $vs_nome_campo = str_replace(".0.", "_0_", $vs_nome_campo);
        ?>
            <script src="assets/libraries/ckeditor/ckeditor.js"></script>

            <div id="rich_text_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"
            <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                    print ' style="display:none"';
            ?>
            >
                <textarea class="form-control input" rows="<?php print $vn_numero_linhas ?>" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>"
                <?php
                    if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                        print ' disabled style="display:none"';

                    if (isset($pa_parametros_campo["readonly"]))
                        print ' readonly';
                ?>
                ><?php print htmlspecialchars($vs_valor_campo); ?></textarea>

                <script>
                    var editor_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?> = ClassicEditor
                        .create( document.querySelector('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>') )
                        .then(editor => {
                            editor.model.document.on('change:data', (evt, data) => {
                                vb_alterou_cadastro = true;

                                <?php 
                                if (isset($pa_parametros_campo["logar_alteracao"]) && $pa_parametros_campo["logar_alteracao"])
                                {
                                ?>
                                    $("#alterou_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").val("1");
                                <?php
                                }
                                ?>
                            });
                        })
                        .catch( error => {
                            console.error( error );
                    } );
                </script>
            </div>

        <?php
        }
        elseif ($vs_formato == "color")
        {
            if (!$vs_valor_campo)
                $vs_valor_campo = "#FFFFFF";
        ?>
            <input type="color" class="input" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>"
            <?php
                if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                    print ' disabled style="display:none"';
            ?>
            >
        <?php
        }
        elseif ($vs_formato == "date")
        {
        ?>
            <input type="date" class="form-control input <?php print $vs_css_class; ?>" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>"
            <?php
                if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                    print ' disabled style="display:none"';

                if (isset($pa_parametros_campo["readonly"]) && $pa_parametros_campo["readonly"])
                    print ' readonly';
            ?>
            >
        <?php
        }
        elseif ($vn_numero_linhas == 1)
        {
        ?>
            <input type="text" class="form-control input <?php print $vs_css_class; ?>" size="<?php print $vn_tamanho_maximo; ?>" maxlength="<?php print $vn_tamanho_maximo; ?>"  name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="<?php print htmlspecialchars($vs_valor_campo); ?>"
            <?php
                if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                    print ' disabled style="display:none"';

                if (isset($pa_parametros_campo["readonly"]) && $pa_parametros_campo["readonly"])
                    print ' readonly';
            ?>
            >
        <?php
        }
        else
        {
        ?>
            <textarea class="form-control texto input" rows="<?php print $vn_numero_linhas ?>" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
            <?php
                if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                    print ' disabled style="display:none"';
            ?>
            ><?php print htmlspecialchars($vs_valor_campo); ?></textarea>
        <?php
        }
        ?>

        <?php 
        if (isset($pa_parametros_campo["logar_alteracao"]) && $pa_parametros_campo["logar_alteracao"])
        {
        ?>
            <input type="hidden" id="alterou_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" name="alterou_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>" value="0">
        <?php
        }
        ?>
    </div>
<?php
}
?>

<script>

$(document).on('click', "#chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>", function()
{
    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>').prop('disabled'));

    <?php
    if ($vs_formato == "rich")
    {
    ?>
        $("#rich_text_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").toggle();
    <?php
    }
    else
    {
    ?>
        $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").toggle();
        $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").focus();
    <?php
    }
    ?>
});


$(document).on('keyup', "#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>", function(event)
{
    vb_alterou_cadastro = true;

    <?php 
    if (isset($pa_parametros_campo["logar_alteracao"]) && $pa_parametros_campo["logar_alteracao"])
    {
    ?>
        $("#alterou_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").val("1");
    <?php
    }
    ?>
});

</script>