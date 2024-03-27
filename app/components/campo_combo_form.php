<?php
    if (!isset($vs_tela))
        exit();

    if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
    {
        $vs_nome_campo = $pa_parametros_campo["nome"];

        if (isset($pa_parametros_campo["id"]))
            $vs_id_campo = $pa_parametros_campo["id"];
        else
            $vs_id_campo = str_replace(",", "_", $vs_nome_campo);
    }

    $vn_valor_campo_codigo = "";
    $vs_valor_campo_nome = "";

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    $vs_nome_campo_lookup = 'ids_' . str_replace("_codigo", "", $vs_nome_campo);
    $vs_nome_campo_codigos = 'ids_' . $vs_nome_campo;

    if (isset($pa_parametros_campo["objeto"]))
        $vs_objeto_campo = $pa_parametros_campo["objeto"];

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

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;

    if ( isset($pa_parametros_campo["valor_padrao"]) && ($vs_valor_campo == "") )
        $vs_valor_campo = $pa_parametros_campo["valor_padrao"];

    if (!isset($pa_parametros_campo["css-class"]))
        $pa_parametros_campo["css-class"] = "form-select";

    if (!isset($pa_parametros_campo["multiplos_valores"]))
        $vb_multiplos_valores = false;
    else
        $vb_multiplos_valores = $pa_parametros_campo["multiplos_valores"];

    if ($vb_multiplos_valores)
        $vs_nome_campo = $vs_nome_campo_lookup;

    if (isset($pa_parametros_campo["atributos"]))
        $va_keys_atributos = array_keys($pa_parametros_campo["atributos"]);

    $vb_nunca_exibir = false;
    $vn_numero_mininimo_itens_exibicao_campo = 1;

    if (isset($pa_parametros_campo["atributo_obrigatorio"]) && $pa_parametros_campo["atributo_obrigatorio"])
    {
        $vn_numero_mininimo_itens_exibicao_campo = 2;
    }

    if ( (count($va_itens_campo) < $vn_numero_mininimo_itens_exibicao_campo) && !(isset($pa_parametros_campo["exibicao_obrigatoria"]) && $pa_parametros_campo["exibicao_obrigatoria"]) )
        $vb_nunca_exibir = true;
?>

<div class="mb-3" id="div_<?php print $vs_id_campo; ?>"
<?php
if ($vb_nunca_exibir)
    print " hidden";

if (!$vb_pode_exibir)
    print ' style="display:none"';
?>
>
    <?php
    if ($vs_label_campo)
    {
    ?>
        <label class="form-label" title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>">
            <?php if ($vs_modo == "lote")
            {
            ?>
                <input type="checkbox" class="check-campo form-check-input" id="chk_<?php print $vs_id_campo; ?>">
            <?php
            }
            ?>

            <?php print $vs_label_campo; ?>
        </label>
    <?php
    }
    ?>

    <?php if ($vb_multiplos_valores) {

        print '<div id="div_selecionados_' . $vs_nome_campo_lookup . '">';

        if (isset($va_valor_campo[$vs_id_campo]))
        {
            if (is_array($va_valor_campo[$vs_id_campo]))
                $va_valores_campo = $va_valor_campo[$vs_id_campo];
            else
                $va_valores_campo[$vs_id_campo] = $va_valor_campo[$vs_id_campo];

            $vn_valor_campo_codigo = array();

            foreach($va_valores_campo as $va_valores_linha)
            {
                $contador = 1;

                if ($va_valores_linha == "")
                    continue;

                if (!is_array($va_valores_linha))
                    $va_valores_linha_temp[] = $va_valores_linha;
                else
                    $va_valores_linha_temp = $va_valores_linha;

                if (isset($va_valores_linha_temp[$vs_id_campo]))
                    $vn_linha_codigo = $va_valores_linha_temp[$vs_id_campo][$pa_parametros_campo["atributos"][0]];
                else
                    $vn_linha_codigo = $va_valores_linha_temp[$pa_parametros_campo["atributos"][0]];

                $vb_ler_hierarquia = true;
                $vs_campo_hierarquia = "";
                if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[1]]))
                    $vs_campo_item_lista_value = $pa_parametros_campo["atributos"][$va_keys_atributos[1]];
                else
                {
                    if (isset($pa_parametros_campo["atributos"][$va_keys_atributos[1]]["hierarquia"]))
                    {
                        $vs_campo_item_lista_value = $va_keys_atributos[1];
                        $vs_campo_hierarquia = $pa_parametros_campo["atributos"][$va_keys_atributos[1]]["hierarquia"];
                    }
                    else
                        $vs_campo_item_lista_value = $pa_parametros_campo["atributos"][$va_keys_atributos[1]];
                }

                $vs_linha_valor = $this->ler_valor_textual($va_valores_linha_temp, $vs_campo_item_lista_value);

                // Tem que passar todos os valores do objeto para montar a linha
                foreach ($va_valores_linha_temp as $vs_key_valor_linha => $v_valor)
                {
                    if (is_array($vn_linha_codigo))
                        $vn_linha_codigo = $vn_linha_codigo[$pa_parametros_campo["atributos"][0]];

                    $va_valores_linha_com_codigo[$vs_key_valor_linha . "_" . $vn_linha_codigo] = $v_valor;

                    if (count($va_valores_linha_temp) == 1 && $vs_objeto_campo)
                    {
                        // Se não veio o valor de exibição da linha, tem que ler do banco
                        // (caso do formulário vazio que abre a partir da ficha do pai)
                        /////////////////////////////////////////////////////////////////

                        $vo_objeto_filho = new $vs_objeto_campo;
                        $va_objeto_filho = $vo_objeto_filho->ler($vn_linha_codigo, "lista");

                        $vs_linha_valor = $va_objeto_filho[$pa_parametros_campo["atributos"][1]];
                    }
                }

                $va_valores_linha = $va_valores_linha_com_codigo;

                if (isset($pa_parametros_campo["dependencia_linha"]))
                {
                    $va_valores_linha[$pa_parametros_campo["dependencia_linha"]["campo"]] =  $va_valor_campo[$pa_parametros_campo["dependencia_linha"]["campo"]];
                }

                $vn_valor_campo_codigo[] = $vn_linha_codigo;

                require dirname(__FILE__)."/../functions/linha.php";
            }

            $vn_valor_campo_codigo = join("|", $vn_valor_campo_codigo);
        }

        print '</div>';

        print '<input type="hidden" name="' . str_replace("ids_", "", $vs_nome_campo_codigos)  . '" id="' . $vs_nome_campo_codigos . '" value="' . $vn_valor_campo_codigo . '">';

        }
    ?>

    <select class="<?php print $pa_parametros_campo["css-class"] ?> input" name="<?php print $vs_nome_campo ?>" id="<?php print $vs_id_campo ?>"
    <?php
        if ( (isset($pa_parametros_campo["nao_exibir"]) && $pa_parametros_campo["nao_exibir"]) || ($vs_modo == "lote") )
            print ' style="display:none"';

        if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            print ' disabled ';
    ?>
    >
        <?php if ($vb_permitir_sem_valor)
        {
        ?>
            <option value=""></option>
        <?php
        }
        ?>            
        
        <?php
        $contador = 1;
        foreach ($va_itens_campo as $vn_key_item_campo => $vs_valor_item_campo)
        {
            if ( (!$vb_permitir_sem_valor) && ($vs_valor_campo == "") && ($contador == 1) )
                $vs_valor_campo = $vn_key_item_campo;
                
        ?>
                <option value="<?php print $vn_key_item_campo ?>"
                <?php 
                if (strval($vn_key_item_campo) == strval($vs_valor_campo))
                {
                    print " selected ";
                }
                ?>
                ><?php print $vs_valor_item_campo; ?></option>
        <?php
            $contador++;
        }
        ?>            
    </select>

    <?php if (($vs_modo == "listagem") && config::get(["f_filtros_busca_preenchimento_campo"]))
    {
    ?>
        <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo ?>_com_valor" id="<?php print $vs_nome_campo ?>_com_valor" onclick="alterar_valor_filtro_<?php print $vs_id_campo ?>(this.checked, 'com_valor')"
        <?php 
        if ($vb_marcar_com_valor)
            print " checked";
        ?>
        > preenchido
        
        <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo ?>_sem_valor" id="<?php print $vs_nome_campo ?>_sem_valor" onclick="alterar_valor_filtro_<?php print $vs_id_campo ?>(this.checked, 'sem_valor')"
        <?php 
        if ($vb_marcar_sem_valor)
            print " checked";
        ?>
        > não preenchido
    <?php
    }
    ?>

    <?php
    if (isset($pa_parametros_campo["conectar"]))
    {
    ?>

        <script>

        $(document).on('change', "#<?php print $vs_id_campo ?>", function()
        {
            //Para chamar corretamente o $.get mais de uma vez
            jQuery.ajaxSetup({async:false});

            <?php
            foreach($pa_parametros_campo["conectar"] as $v_conectar)
            {
                $vs_campo_conectar = $v_conectar["campo"];

                $vs_campo_pai = "";
                if (isset($v_conectar["campo_pai"]))
                    $vs_campo_pai = $v_conectar["campo_pai"];

                $vs_sufixo_nome_campo_conectar = "";
                if (isset($v_conectar["sufixo_nome"]))
                    $vs_sufixo_nome_campo_conectar = $v_conectar["sufixo_nome"];

            ?>
                vs_filtro = '&<?php print $v_conectar["atributo"]; ?>='+$(this).val();

                v_campos_conectar = $("#<?php print $vs_campo_conectar . $vs_sufixo_nome_campo_conectar; ?>");

                for (let i = 0; i < v_campos_conectar.length; i++)
                {
                    vs_campo_atualizar_nome = v_campos_conectar[i].name;
                    vs_campo_atualizar_id = v_campos_conectar[i].id;

                    vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_tela; ?>&campo='+vs_campo_atualizar_nome+'&campo_pai=<?php print $vs_campo_pai; ?>&sufixo=<?php print $vs_sufixo_nome_campo_conectar; ?>&modo=<?php print $vs_modo; ?>&atualizacao=1'+vs_filtro;

                    //console.log(vs_url_campo_atualizado);
                    $.get(vs_url_campo_atualizado, function(data, status)
                    {
                        var v_field_to_update = document.querySelector("#div_"+vs_campo_atualizar_id);

                        var v_updated_field = document.createElement('div');
                        v_updated_field.innerHTML = data;

                        v_field_to_update.parentNode.replaceChild(v_updated_field, v_field_to_update);
                        
                        $("#" + vs_campo_atualizar_nome).trigger("change");
                    });
                }
            <?php
            }
            ?>

            // A partir daqui, verifica se precisa atualizar o conteúdo
            // de algum campo dependente do item recém-adicionado
            <?php
            if (isset($pa_parametros_campo["atualizar_campo"]))
            {
            ?>
                //Para chamar corretamente o $.get mais de uma vez
                jQuery.ajaxSetup({async:false});

            <?php
                foreach($pa_parametros_campo["atualizar_campo"] as $va_campo)
                {
                    if (is_array($va_campo))
                        $vs_campo = $va_campo[0];
                    else
                        $vs_campo = $va_campo;

                    $vb_exibir = 1;
                    if (isset($va_campo["nao_exibir"]) && $va_campo["nao_exibir"])
                        $vb_exibir = 0;

                    $vs_objeto_fonte = $pa_parametros_campo["objeto"];
                ?>
                    vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_objeto_fonte; ?>&campo=<?php print $vs_campo; ?>&modo=edicao&cod='+$(this).val()+'&exibir=<?php print $vb_exibir; ?>&atualizacao=1';

                    $.get(vs_url_campo_atualizado, function(data, status)
                    {
                        var v_field_to_update = document.querySelector("#div_<?php print $vs_campo; ?>");
                
                        var v_updated_field = document.createElement('div');
                        v_updated_field.innerHTML = data;

                        v_field_to_update.parentNode.replaceChild(v_updated_field, v_field_to_update);

                        vs_tipo_campo = $("#<?php print $vs_campo; ?>").attr("class");

                        if (vs_tipo_campo == "checkbox")
                            atualizar_dependencias_<?php print $vs_campo; ?>($("#<?php print $vs_campo; ?>_chk").is(':checked'));
                    });
                <?php
                }
            }
            ?>
        });

        </script>

    <?php
    }
    ?>

    <?php
    if (isset($pa_parametros_campo["controlar_exibicao"]))
    {
    ?>

        <script>

        $(document).on('change', "#<?php print $vs_id_campo ?>", function()
        {
        <?php 
        foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
        {
        ?>
            atualizar_exibicao_<?php print $vs_campo_controlar ?>($(this).val());
        <?php
        }
        ?>
        });

        </script>

    <?php
    }
    ?>

    <?php if ($vb_multiplos_valores) { ?>
        <script>
            $(document).on('click', "#<?php print $vs_id_campo ?>", function()
            {
                const codigo = $(this).val();
                const valor = $(this).find('option:selected').text();

                if (codigo == "") {
                    return;
                } else if ($("#<?php print $vs_nome_campo_codigos ?>").val().indexOf(codigo) != -1) {
                    return;
                }

                const vs_url_nova_linha = "functions/linha.php?tela=<?php print $vs_tela ?>&campo_lookup=<?php print $vs_nome_campo_lookup ?>&campo_codigos=<?php print $vs_nome_campo_codigos  ?>&codigo=" + codigo + "&valor=" + encodeURIComponent(valor);

                $.get(vs_url_nova_linha, function(data, status)
                {
                    $("#div_selecionados_<?php print $vs_nome_campo_lookup ?>").before(data);
   
                    const valor_atual = $("#<?php print $vs_nome_campo_codigos ?>").val();

                    if (valor_atual == "") {
                        $("#<?php print $vs_nome_campo_codigos ?>").val(codigo);
                    } else {
                        $("#<?php print $vs_nome_campo_codigos ?>").val(valor_atual + "|" + codigo);
                    }
                    
                    $("#<?php print $vs_id_campo ?>").val($("#<?php print $vs_id_campo ?> option:first").val());

                });
            });

            function atualizar_dependencias_<?php print $vs_nome_campo_lookup; ?>(codigo) {
                return;
            }

        </script>

    <?php } ?>

</div>

<script>

$(document).on('click', "#chk_<?php print $vs_id_campo ?>", function()
{
    $("#<?php print $vs_id_campo ?>").toggle();
    $("#<?php print $vs_id_campo ?>").prop("disabled", !$('#<?php print $vs_id_campo ?>').prop('disabled'));

    $("#<?php print $vs_id_campo; ?>").focus();
});

function alterar_valor_filtro_<?php print $vs_id_campo ?>(pb_checked, ps_valor)
{
    if (ps_valor == "sem_valor")
        $("#<?php print $vs_id_campo ?>_com_valor").prop("checked", false);
    else if (ps_valor == "com_valor")
        $("#<?php print $vs_id_campo ?>_sem_valor").prop("checked", false);

    $("#<?php print $vs_id_campo ?>").val("");
    $("#<?php print $vs_id_campo ?>").prop("disabled", pb_checked);
};

</script>