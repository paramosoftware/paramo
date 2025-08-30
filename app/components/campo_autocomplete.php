<?php
    $vb_atualizacao_campo = false;
    if (isset($pa_parametros_campo["atualizacao"]))
        $vb_atualizacao_campo = $pa_parametros_campo["atualizacao"];

    if (!isset($pa_parametros_campo["nome"]))
    {
        print "Não é possível criar campo sem nome.";
        exit();
    }
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];

    if (!isset($pa_parametros_campo["objeto"]))
    {
        print "Não é possível criar $vs_nome_campo campo sem objeto.";
        exit();
    }
    else
        $vs_objeto_campo = $pa_parametros_campo["objeto"];

    if (!isset($vs_objeto_campo))
    {
        print "Não é possível criar campo $vs_nome_campo sem objeto base associado.";
        exit();
    }

    $vs_procurar_por = "";
    if (isset($pa_parametros_campo["procurar_por"]))
        $vs_procurar_por = $pa_parametros_campo["procurar_por"];

    $vs_operador = $pa_parametros_campo["operador"] ?? "LIKE";

    $vb_configuracao_padrao = $pa_parametros_campo["forcar_configuracao_padrao"] ?? 0;

    if (!isset($pa_parametros_campo["multiplos_valores"]))
        $vb_multiplos_valores = false;
    else
        $vb_multiplos_valores = $pa_parametros_campo["multiplos_valores"];

    $vb_valor_no_input = $pa_parametros_campo["valor_no_input"] ?? false;

    $vb_permitir_cadastro = false;
    if (isset($pa_parametros_campo["permitir_cadastro"]))
        $vb_permitir_cadastro = $pa_parametros_campo["permitir_cadastro"];

    if ($vb_permitir_cadastro)
    {
        if (isset($pa_parametros_campo["campo_salvar"]))
            $vs_campo_salvar = $pa_parametros_campo["campo_salvar"];
        else
            $vs_campo_salvar = "";

        if (!$vs_campo_salvar)
        {
            print "Não é possível criar $vs_nome_campo sem nome de campo para salvar.";
            exit();
        }

        if (!is_array($vs_campo_salvar))
            $va_campos_salvar = array($vs_campo_salvar);
        else
            $va_campos_salvar = $vs_campo_salvar;
    }

    $vb_pode_remover = true;
    if (isset($pa_parametros_campo["pode_remover"]))
        $vb_pode_remover = $pa_parametros_campo["pode_remover"];

    $vb_permitir_repeticao_termo = "false";
    if (isset($pa_parametros_campo["permitir_repeticao_termo"]) && $pa_parametros_campo["permitir_repeticao_termo"])
        $vb_permitir_repeticao_termo = $pa_parametros_campo["permitir_repeticao_termo"];

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;

    if (!isset($va_valor_campo))
        $va_valor_campo = array();

    if (!isset($vn_largura_campo))
        $vn_largura_campo = '';

    if (!isset($vn_tamanho_maximo))
        $vn_tamanho_maximo = '';

    $vn_valor_campo_codigo = "";
    $vs_valor_campo_nome = "";

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    if (is_array($vs_nome_campo))
    {
        $vs_nome_campo_lookup = $vs_nome_campo[0] . $vs_sufixo_nome_campo;
        $vs_nome_campo_codigos = $vs_nome_campo[1] . $vs_sufixo_nome_campo;
    }
    else
    {
        $vs_nome_campo_lookup = $vs_nome_campo . $vs_sufixo_nome_campo;
        $vs_nome_campo_codigos = $vs_nome_campo. "_codigo" . $vs_sufixo_nome_campo;
    }

    $vs_id_campo_codigos = str_replace(",", "_", $vs_nome_campo_codigos);

    unset($vs_nome_campo);

    $va_keys_atributos = array_keys($pa_parametros_campo["atributos"]);

    $vb_edicao_lote = false;

    $vb_valor_nulo = false;
    if (isset($va_valor_campo[$vs_nome_campo_codigos]) && is_array($va_valor_campo[$vs_nome_campo_codigos]) && (count($va_valor_campo[$vs_nome_campo_codigos]) == 1) && !isset($va_valor_campo[$vs_nome_campo_codigos][0][$vs_nome_campo_codigos]))
    {
        $vn_valor_campo_codigo = "0";
        $vb_valor_nulo = true;
    }

    if (!$vb_multiplos_valores && !$vb_valor_nulo)
    {
        if (isset($va_valor_campo[$vs_nome_campo_codigos]))
        {
            if (is_array($va_valor_campo[$vs_nome_campo_codigos]))
            {
                $va_item = $va_valor_campo[$vs_nome_campo_codigos];

                if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[0]]))
                {
                    // Se o campo é para único valor mas o relacionamento é nxn, então o valor está $va_item[0]
                    ///////////////////////////////////////////////////////////////////////////////////////////

                    if (!isset($va_item[$pa_parametros_campo["atributos"][$va_keys_atributos[0]]]))
                        $va_item[$pa_parametros_campo["atributos"][$va_keys_atributos[0]]] = $va_item[0][$vs_nome_campo_codigos][$pa_parametros_campo["atributos"][$va_keys_atributos[0]]];

                    $vn_valor_campo_codigo = $va_item[$pa_parametros_campo["atributos"][$va_keys_atributos[0]]];
                }
                else
                    $vn_valor_campo_codigo = $va_item[$va_keys_atributos[0]];

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
                
                // Temos que generalizar essa montagem numa função
                $va_item_lista_value = array();

                if (isset($va_item[0][$vs_nome_campo_codigos]))
                    $va_item = $va_item[0][$vs_nome_campo_codigos];

                while ($vb_ler_hierarquia)
                {
                    $vs_valor_item_lista = $this->ler_valor_textual($va_item, $vs_campo_item_lista_value);

                    array_unshift($va_item_lista_value, $vs_valor_item_lista);
                    
                    if (isset($va_item[$vs_campo_hierarquia]))
                    {
                        $va_item = $va_item[$vs_campo_hierarquia];
                        $vs_valor_item_lista = $va_item;
                    }
                    else
                        $vb_ler_hierarquia = false;
                }

                $vs_linha_valor = join(" >  ", $va_item_lista_value);
                // Temos que generalizar essa montagem numa função
            }
            else
            {
                $vn_valor_campo_codigo = $va_valor_campo[$vs_nome_campo_codigos];

                $va_valor_campo_temp = explode("|", $vn_valor_campo_codigo);

                $vs_linha_valor = "";

                if (count($va_valor_campo_temp) > 1)
                    $vb_edicao_lote = true;
                else
                {
                    $vs_linha_valor = ler_valor1($vs_nome_campo_lookup, $va_valor_campo, null);

                    if (empty($vs_linha_valor) && $vn_valor_campo_codigo)
                    {
                        // Se não veio o valor de exibição da linha, tem que ler do banco
                        // (caso do formulário vazio que abre a patir da ficha do pai)

                        $vo_objeto_filho = new $vs_objeto_campo('');
                        $va_objeto_filho = $vo_objeto_filho->ler($vn_valor_campo_codigo, "lista");

                        if (isset($pa_parametros_campo["atributos"][1]) && !is_array($pa_parametros_campo["atributos"][1]))
                            $vs_linha_valor = $this->ler_valor_textual($va_objeto_filho, $pa_parametros_campo["atributos"][1]);
                        else
                        {
                            $vb_ler_hierarquia = true;
                            $vs_campo_hierarquia = $pa_parametros_campo["atributos"][$va_keys_atributos[1]]["hierarquia"];
                            $va_item_lista_value = array();

                            //$vs_linha_valor = $this->ler_valor_textual($va_objeto_filho, $va_keys_atributos[1]);

                            while ($vb_ler_hierarquia)
                            {
                                $vs_valor_item_lista = $this->ler_valor_textual($va_objeto_filho, $va_keys_atributos[1]);

                                array_unshift($va_item_lista_value, $vs_valor_item_lista);
                                
                                if (isset($va_objeto_filho[$vs_campo_hierarquia]))
                                {
                                    $va_objeto_filho = $va_objeto_filho[$vs_campo_hierarquia];
                                    $vs_valor_item_lista = $va_objeto_filho;
                                }
                                else
                                    $vb_ler_hierarquia = false;
                            }

                            $vs_linha_valor = join(" >  ", $va_item_lista_value);
                        }
                    }
                }
            }
            
            $vs_valor_campo_nome = $vs_linha_valor;
        }
    }

    if (isset($va_valor_campo[$vs_nome_campo_codigos]) && is_array($va_valor_campo[$vs_nome_campo_codigos]))
        $va_numero_itens = count($va_valor_campo[$vs_nome_campo_codigos]);
    else
        $va_numero_itens = 0;
?>

<div class="mb-3" id="div_<?php print $vs_nome_campo_codigos ?>"
<?php
    if (!$vb_pode_exibir)
        print ' style="display:none"';
?>
>

    
        <?php if(!empty($pa_parametros_campo["descricao"])) : ?>
            <label class="form-label" title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>"> 
        <?php endif ?>

        <?php if ($vs_modo == "lote")
        {
        ?>
            <input type="checkbox" class="check-campo form-check-input" id="chk_<?php print $vs_nome_campo_lookup . $vs_sufixo_nome_campo; ?>">
        <?php
        }
        ?>

        <?php print $vs_label_campo;

        if (isset($pa_parametros_campo["selecao_modal"]) && ($pa_parametros_campo["selecao_modal"]))
        {
            ?>
            <button type="button" class="btn btn-primary" id="btn_modal_<?php print $vs_nome_campo_lookup; ?>">
                <svg class="icon">
                    <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-search"></use>
                </svg>
            </button>
            <?php
        }

        ?>

        <?php if ($vb_multiplos_valores) 
        {
        ?>
            <div id="div_remover_todos_<?php print $vs_nome_campo_lookup ?>" style="float:right; margin-top:5px;
            <?php
            if ( ($va_numero_itens <= 5) || (!$vb_multiplos_valores) )
                print " display:none";
            ?>
            ">
                <button class="btn btn-primary px-4" type="button" id="btn_remover_todos_<?php print $vs_nome_campo_lookup; ?>">Remover todos</button>
            </div>
        <?php
        }
        ?>
    </label>
        
    <div id="div_selecionados_<?php print $vs_nome_campo_lookup ?>"
    <?php
    if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            print ' style="display:none"';
    ?>
    >

    <?php
    $va_valores_campo = array();

    if ($vb_multiplos_valores && isset($va_valor_campo[$vs_nome_campo_codigos]) && !$vb_valor_nulo)
    {
        if (is_array($va_valor_campo[$vs_nome_campo_codigos]))
            $va_valores_campo = $va_valor_campo[$vs_nome_campo_codigos];
        else
            $va_valores_campo[$vs_nome_campo_codigos] = $va_valor_campo[$vs_nome_campo_codigos];
            
        $vn_valor_campo_codigo = array();

        $contador = 1;

        foreach($va_valores_campo as $va_valores_linha)
        {
            if (!is_array($va_valores_linha))
                $va_valores_linha_temp[] = $va_valores_linha[$vs_nome_campo_codigos];
            else
                $va_valores_linha_temp = $va_valores_linha[$vs_nome_campo_codigos];

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
            
            // Temos que generalizar essa montagem numa função
            $va_item_lista_value = array();

            while ($vb_ler_hierarquia)
            {
                $vs_valor_item_lista = $this->ler_valor_textual($va_valores_linha_temp, $vs_campo_item_lista_value);

                array_unshift($va_item_lista_value, $vs_valor_item_lista);
                
                if (isset($va_valores_linha_temp[$vs_campo_hierarquia]))
                {
                    $va_valores_linha_temp = $va_valores_linha_temp[$vs_campo_hierarquia];
                    $vs_valor_item_lista = $va_valores_linha_temp;
                }
                else
                    $vb_ler_hierarquia = false;
            }

            $vs_linha_valor = join(" >  ", $va_item_lista_value);
            // Temos que generalizar essa montagem numa função

            // Tem que passar todos os valores do objeto para montar a linha
            
            foreach ($va_valores_linha as $vs_key_valor_linha => $v_valor)
            {
                if (is_array($vn_linha_codigo))
                    $vn_linha_codigo = $vn_linha_codigo[$pa_parametros_campo["atributos"][0]];

                $va_valores_linha_com_codigo[$vs_key_valor_linha . "_" . $vn_linha_codigo] = $v_valor;
                
                if ($va_valores_linha_temp && count($va_valores_linha_temp) == 1)
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
                $va_valores_linha[$pa_parametros_campo["dependencia_linha"]["campo"]] = $va_valor_campo[$pa_parametros_campo["dependencia_linha"]["campo"]];
            }
            
            if ($vb_permitir_repeticao_termo == "true")
                $vn_linha_codigo = $vn_linha_codigo . "_" . $contador;

            $vn_valor_campo_codigo[] = $vn_linha_codigo;

            require dirname(__FILE__)."/../functions/linha.php";

            $contador++;
        }
        
        $vn_valor_campo_codigo = join("|", $vn_valor_campo_codigo);
    }
    else
    {
        if ($vn_valor_campo_codigo && !$vb_edicao_lote && !$vb_valor_no_input)
        {
            $vn_linha_codigo = $vn_valor_campo_codigo;

            $va_valores_linha = array();
            if (isset($pa_parametros_campo["dependencia_linha"]))
            {
                $va_valores_linha[$pa_parametros_campo["dependencia_linha"]["campo"]] =  $va_valor_campo[$pa_parametros_campo["dependencia_linha"]["campo"]][0];
            }

            if (!isset($pa_parametros_campo["sugerir_valores"]) || (isset($pa_parametros_campo["sugerir_valores"]) && $pa_parametros_campo["sugerir_valores"]))
                require dirname(__FILE__)."/../functions/linha.php";
        }
    }

    $vb_mostrar_subcampos = 1;
    if (isset($pa_parametros_campo["mostrar_subcampos"]) && !$pa_parametros_campo["mostrar_subcampos"])
        $vb_mostrar_subcampos = 0;
    ?>
    </div>
    
    <?php 
        if (isset($pa_parametros_campo["numero_linhas"]))
        {
    ?>
            <textarea class="form-control lookup input"
                      <?php if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]) print ' disabled'; ?>
                      rows="<?php print $pa_parametros_campo["numero_linhas"] ?>"
                      name="<?php print $vs_nome_campo_lookup ?>"
                      id="<?php print $vs_nome_campo_lookup ?>"><?php print htmlspecialchars($vs_valor_campo_nome); ?></textarea>
    <?php
        }
        else
        {
    ?>
        <div class="autocomplete-group">
            <svg class="icon autocomplete-icon">
                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-search"></use>
            </svg>

            <input type="text" class="form-control lookup input"
                   maxlength="<?php print $vn_tamanho_maximo; ?>"
                   name="<?php print $vs_nome_campo_lookup ?>"
                   id="<?php print $vs_nome_campo_lookup ?>"
                   <?php if(!empty($pa_parametros_campo["placeholder"])) : ?>
                    placeholder="<?php print $pa_parametros_campo["placeholder"]?>"
                   <?php endif ?>
                   value="<?php print htmlentities($vs_valor_campo_nome, ENT_QUOTES, "UTF-8", false);
                   ?>"
    <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled';

            elseif ($vb_valor_nulo)
                print 'disabled';

            elseif (!isset($pa_parametros_campo["sugerir_valores"]) || (isset($pa_parametros_campo["sugerir_valores"]) && $pa_parametros_campo["sugerir_valores"]))
            {
                if ((!$vb_multiplos_valores) && !$vb_valor_no_input && isset($va_valor_campo[$vs_nome_campo_codigos]))
                {
                    print ' style="display:none"';
                }
            }

            print '></div>';

            if (($vs_modo == "listagem") && config::get(["f_filtros_busca_preenchimento_campo"]) && empty($pa_parametros_campo['nao_exibir_preenchimento']))
            {
            ?>
                <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo_codigos; ?>_com_valor" id="<?php print $vs_id_campo_codigos ?>_com_valor" onclick="alterar_valor_filtro_<?php print $vs_id_campo_codigos; ?>(this.checked, 'com_valor')"
                <?php
                if ($vb_marcar_com_valor)
                    print " checked";
                ?>
                > preenchido

                <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo_codigos; ?>_sem_valor" id="<?php print $vs_id_campo_codigos ?>_sem_valor" onclick="alterar_valor_filtro_<?php print $vs_id_campo_codigos; ?>(this.checked, 'sem_valor')"
                <?php
                if ($vb_marcar_sem_valor)
                    print " checked";
                ?>
                > não preenchido
            <?php
            }
        }
    ?>

    <div id="div_sugestoes_<?php print $vs_nome_campo_lookup ?>"></div>

    <input type="hidden" class="input" id="<?php print $vs_id_campo_codigos; ?>" name="<?php print $vs_nome_campo_codigos; ?>" value="<?php print $vn_valor_campo_codigo; ?>"
    <?php
        if ( 
            (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            &&
            (!isset($pa_parametros_campo["campo_pai"]) || !$pa_parametros_campo["campo_pai"])
        )
            print ' disabled';
    ?>
    >

    <?php if (isset($pa_parametros_campo["permitir_valor_nulo"]) && ($pa_parametros_campo["permitir_valor_nulo"]))
    {
    ?>
        <input type="checkbox" class="check-campo form-check-input" id="chk_nulo_<?php print $vs_nome_campo_lookup . $vs_sufixo_nome_campo; ?>"
        <?php if ($vb_valor_nulo) print " checked"; ?>
        > Desconhecido(a)
    <?php
    }
    ?>

    <?php if ($this->modo_form != "listagem")
    {
    ?>
        <div class="mb-3" id="div_adicionar_todos_<?php print $vs_nome_campo_lookup ?>" style="margin-top:10px; display:none">					
            <div class="input-group mb-3">
                <button class="btn btn-primary px-4" type="button" id="btn_adicionar_todos_<?php print $vs_nome_campo_lookup ?>">Adicionar todos</button>
            </div>
        </div>
    <?php
    }
    ?>

    <?php if (isset($pa_parametros_campo["selecao_modal"]) && ($pa_parametros_campo["selecao_modal"]))
    {
    ?>
    <div id="myModal_<?php print $vs_nome_campo_lookup ?>" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal"
                            onClick="$('#myModal_<?php print $vs_nome_campo_lookup; ?>').modal('hide');">Fechar
                    </button>
                </div>

                <?php if (!isset($pa_parametros_campo["sugerir_valores"]) || (isset($pa_parametros_campo["sugerir_valores"]) && $pa_parametros_campo["sugerir_valores"]))
                {
                ?>
                    <div class="modal-body" id="modal_body_<?php print $vs_nome_campo_lookup ?>"></div>

                    <div class="modal-footer">
                        <button id='closeModal' type="button" class="btn btn-outline-primary px-4"
                                data-bs-dismiss="modal"
                                onClick="$('#myModal_<?php print $vs_nome_campo_lookup; ?>').modal('hide');">Fechar
                        </button>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>


<?php if (!$vb_atualizacao_campo)
{
?>

<script>

function alterar_valor_filtro_<?php print $vs_id_campo_codigos ?>(pb_checked, ps_valor)
{
    if (ps_valor == "sem_valor")
        $("#<?php print $vs_id_campo_codigos ?>_com_valor").prop("checked", false);
    else if (ps_valor == "com_valor")
        $("#<?php print $vs_id_campo_codigos ?>_sem_valor").prop("checked", false);

    $("#<?php print $vs_id_campo_codigos ?>").val("");
    $("#<?php print $vs_nome_campo_lookup ?>").val("");

    $("#<?php print $vs_id_campo_codigos ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo_lookup ?>").prop("disabled", pb_checked);
};

$(document).on('click', "#chk_nulo_<?php print $vs_nome_campo_lookup . $vs_sufixo_nome_campo; ?>", function()
{
    $("#btn_remover_todos_<?php print $vs_nome_campo_lookup; ?>").trigger("click");

    if ($('#<?php print $vs_nome_campo_lookup ?>').prop('disabled'))
        $("#<?php print $vs_id_campo_codigos ?>").val("");
    else
        $("#<?php print $vs_id_campo_codigos ?>").val("0");

    $("#<?php print $vs_nome_campo_lookup ?>").prop("disabled", !$('#<?php print $vs_nome_campo_lookup ?>').prop('disabled'));
});

$(document).on('click', "#chk_<?php print $vs_nome_campo_lookup . $vs_sufixo_nome_campo; ?>", function()
{
    $("#<?php print $vs_nome_campo_lookup; ?>").toggle();
    $("#div_selecionados_<?php print $vs_nome_campo_lookup; ?>").toggle();

    $("#<?php print $vs_id_campo_codigos ?>").prop("disabled", !$('#<?php print $vs_id_campo_codigos ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo_lookup ?>").prop("disabled", !$('#<?php print $vs_nome_campo_lookup ?>').prop('disabled'));

    $("#<?php print $vs_nome_campo_lookup; ?>").focus();
});


<?php if (!isset($pa_parametros_campo["sugerir_valores"]) || (isset($pa_parametros_campo["sugerir_valores"]) && $pa_parametros_campo["sugerir_valores"]))
{
?>

<?php if (isset($pa_parametros_campo["draggable"]))
{
?>

$(function() 
{
    $("#div_selecionados_<?php print $vs_nome_campo_lookup ?>").sortable(
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

            $('#<?php print $vs_id_campo_codigos ?>').val(va_lista_codigos_atualizada);
        } 
    });
});

<?php
}
?>

<?php if (isset($pa_parametros_campo["selecao_modal"]) && ($pa_parametros_campo["selecao_modal"]))
{
?>

$('#btn_modal_<?php print $vs_nome_campo_lookup; ?>').on('click',function()
{
    let vs_filtro = "";

    <?php
    if (isset($pa_parametros_campo["dependencia"]))
    {
        if (isset($pa_parametros_campo["dependencia"]["campo"]))
            $va_dependencias = array($pa_parametros_campo["dependencia"]);
        else
            $va_dependencias = $pa_parametros_campo["dependencia"];

        foreach($va_dependencias as $v_campo_conexao)
        {
            if (isset($v_campo_conexao["campo"])) 
            {
        ?>
                vs_filtro += '&<?php print $v_campo_conexao["atributo"]; ?>='+$("#<?php print ($v_campo_conexao["campo"] . $vs_sufixo_nome_campo); ?>").val();
        <?php
            }
            elseif (isset($v_campo_conexao["valor"]))
            {
        ?>
                vs_filtro += '&<?php print $v_campo_conexao["atributo"]; ?>=<?php print $v_campo_conexao["valor"]; ?>';
        <?php
            }
        }
    }
    ?>

    $('#modal_body_<?php print $vs_nome_campo_lookup ?>').load('functions/selecao_modal.php?obj=<?php print $vs_objeto_campo; ?>&campo_modal=<?php print $vs_nome_campo_lookup; ?>'+vs_filtro,function() {
        $('#myModal_<?php print $vs_nome_campo_lookup; ?>').modal('show');
    });
});

<?php
}
?>


var timeout_campo_<?php print $vs_nome_campo_lookup ?>;

$(document).on('keyup', "#lista_<?php print $vs_nome_campo_lookup ?>", function(event)
{
    if (event.key == "Escape") 
    {
        // Se a tecla é ESC, apaga o conteúdo do campo

        $("#<?php print $vs_nome_campo_lookup ?>").val('');
        $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").empty();
        $("#lista_<?php print $vs_nome_campo_lookup ?>").focus();
    }
    else if (event.keyCode == 38) 
    {
        // Se a tecla é arrow down, seleciona move para o segundo item da lista de sugestão
        if ($("#lista_<?php print $vs_nome_campo_lookup ?>").prop('selectedIndex') == 0)
            $("#<?php print $vs_nome_campo_lookup ?>").focus();
    }
    else if (event.key == "Enter") 
    {
        $("#lista_<?php print $vs_nome_campo_lookup ?>").trigger("click");
    }
});

$(document).on('keyup', "#<?php print $vs_nome_campo_lookup ?>", function(event)
{
    if (event.key == "Escape") 
    {
        // Se a tecla é ESC, apaga o conteúdo do campo

        $("#<?php print $vs_nome_campo_lookup ?>").val('');
        $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").empty();
    }
    else if (event.keyCode == 40) 
    {
        // Se a tecla é arrow down, seleciona move para o segundo item da lista de sugestão

        if ($("#lista_<?php print $vs_nome_campo_lookup ?>").children('option').length > 1)
        {
            $("#lista_<?php print $vs_nome_campo_lookup ?>").prop('selectedIndex', 1);
            $("#lista_<?php print $vs_nome_campo_lookup ?>").focus();
        }
    }
    else if (event.keyCode == 38) 
    {
        // Se a tecla é arrow down, seleciona move para o segundo item da lista de sugestão
        if ($("#lista_<?php print $vs_nome_campo_lookup ?>").prop('selectedIndex') == 0)
            $("#<?php print $vs_nome_campo_lookup ?>").focus();
    }
    else if (event.key == "Enter") 
    {
        $("#lista_<?php print $vs_nome_campo_lookup ?>").trigger("click");
    }
    else
    {
        //Antes de chamar a lista de sugestões, vamos esperar 1s uma nova digitação do usuário
        //////////////////////////////////////////////////////////////////////////////////////

        clearTimeout(timeout_campo_<?php print $vs_nome_campo_lookup ?>);
        timeout_campo_<?php print $vs_nome_campo_lookup ?> = setTimeout(function()
        {
            vb_valor_no_input = false;
            
            <?php
            if ($vb_valor_no_input)
            {
            ?>
                vb_valor_no_input = true;
            <?php
            }
            ?>

            if (vb_valor_no_input)
                $("#<?php print $vs_id_campo_codigos ?>").val('');

            vs_termo = $("#<?php print $vs_nome_campo_lookup ?>").val();

            // Primeiro, vamos verificar se se trata de uma adição em lote
            // (valores separados por ";")

            if (vs_termo.indexOf(";") != -1)
            {
                $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").empty();
                $("#div_adicionar_todos_<?php print $vs_nome_campo_lookup ?>").show();
            }
            else
            {
                //Para chamar corretamente o $.get mais de uma vez
                jQuery.ajaxSetup({async:false});

                vs_filtro = "";
                vs_valores_proibidos = "";

                <?php
                if (isset($pa_parametros_campo["dependencia"]))
                {
                    if (isset($pa_parametros_campo["dependencia"]["campo"]))
                        $va_dependencias = array($pa_parametros_campo["dependencia"]);
                    else
                        $va_dependencias = $pa_parametros_campo["dependencia"];

                    foreach($va_dependencias as $v_campo_conexao)
                    {
                        if (isset($v_campo_conexao["campo"])) 
                        {
                    ?>
                            vs_filtro += '&<?php print $v_campo_conexao["atributo"]; ?>='+$("#<?php print ($v_campo_conexao["campo"] . $vs_sufixo_nome_campo); ?>").val();
                    <?php
                        }
                        elseif (isset($v_campo_conexao["valor"]))
                        {
                    ?>
                            vs_filtro += '&<?php print $v_campo_conexao["atributo"]; ?>=<?php print $v_campo_conexao["valor"]; ?>';
                    <?php
                        }
                    }
                }

                if (isset($pa_parametros_campo["prevenir_circularidade"]))
                {
                ?>
                    vs_url_valores_proibidos = "functions/ler_valores_proibidos.php?campo=<?php print $vs_id_campo_codigos; ?>&obj=<?php print $vs_objeto_campo; ?>";
                
                    // Vamos adicionar aqui o código do próprio registro, se existir (é atualização)
                    ////////////////////////////////////////////////////////////////////////////////

                    if ($("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_chave"]; ?>").val() != "")
                        vs_url_valores_proibidos = vs_url_valores_proibidos + "&<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_chave"]; ?>="+$("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_chave"]; ?>").val();

                    // Vamos adicionar aqui os registros já escolhidos como filhos (na tela), se existirem
                    //////////////////////////////////////////////////////////////////////////////////////

                    if (typeof $("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_filho"]; ?>").val() != "undefined")
                    {
                        if ($("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_filho"]; ?>").val() != "")
                            vs_url_valores_proibidos = vs_url_valores_proibidos + "&<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_filho"]; ?>="+$("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_filho"]; ?>").val();
                    }

                    // Vamos adicionar aqui o registros já escolhidos como pai (na tela), se existir
                    ////////////////////////////////////////////////////////////////////////////////

                    if (typeof $("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_filho"]; ?>").val() != "undefined")
                    {
                        if ($("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_pai"]; ?>").val() != "")
                            vs_url_valores_proibidos = vs_url_valores_proibidos + "&<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_pai"]; ?>="+$("#<?php print $pa_parametros_campo["prevenir_circularidade"]["atributo_pai"]; ?>").val();
                    }

                    $.get(vs_url_valores_proibidos, function(data, status) 
                    {
                        vs_valores_proibidos = data;
                    });

                <?php
                }

                $va_keys_atributos = array_keys($pa_parametros_campo["atributos"]);

                if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[0]]))
                    $vs_campo_codigo = $pa_parametros_campo["atributos"][$va_keys_atributos[0]];
                else
                    $vs_campo_codigo = $va_keys_atributos[0];

                if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[1]]))
                    $vs_campo_valor = $pa_parametros_campo["atributos"][$va_keys_atributos[1]];
                else
                {
                    $vs_campo_valor = $va_keys_atributos[1];
                }
                ?>

                vs_url_lista_sugestoes = "functions/autocomplete.php?tela=<?php print $vs_tela ?>&campo=<?php print $vs_nome_campo_lookup ?>&campo_codigos=<?php print $pa_parametros_campo["nome"][1]; ?>&termo="+encodeURIComponent(vs_termo)+"&obj=<?php print $vs_objeto_campo ?>"+"&procurar_por=<?php print $vs_procurar_por ?>&permitir_cadastro=<?php print $vb_permitir_cadastro ?>&campo_codigo=<?php print $vs_campo_codigo; ?>&campo_valor=<?php print $vs_campo_valor; ?>&operador=<?php print $vs_operador; ?>&configuracao_padrao=<?php print $vb_configuracao_padrao; ?>"+vs_filtro;
                
                <?php if (isset($pa_parametros_campo["excluir"]))
                {
                ?>
                vs_url_lista_sugestoes = vs_url_lista_sugestoes + "&excluir=<?php print $pa_parametros_campo["excluir"]; ?>";
                <?php
                }
                ?>

                if (vs_valores_proibidos != "")
                    vs_url_lista_sugestoes = vs_url_lista_sugestoes + "&excluir="+vs_valores_proibidos;

                //console.log(vs_url_lista_sugestoes);

                $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").load(vs_url_lista_sugestoes);
                $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").show();
            }
        }, 1000);
    }
});

$(document).on('click', "#btn_adicionar_todos_<?php print $vs_nome_campo_lookup; ?>", function()
{
    vs_termos = $("#<?php print $vs_nome_campo_lookup ?>").val();
    va_termos = vs_termos.split(";");
    var va_termos_inexistentes = [];

    //Para chamar corretamente o load mais de uma vez
    jQuery.ajaxSetup({async:false});

    for (var i = 0; i < va_termos.length; i++) 
    {
        vs_termo = va_termos[i].trim();

        if (vs_termo != "")
        {
            vs_url_lista_sugestoes = "functions/autocomplete.php?tela=<?php print $vs_tela ?>&campo=<?php print $vs_nome_campo_lookup ?>&termo="+encodeURIComponent(vs_termo)+"&obj=<?php print $vs_objeto_campo ?>"+"&procurar_por=<?php print $vs_procurar_por ?>&_permitir_cadastro_=<?php print $vb_permitir_cadastro ?>&campo_codigo=<?php print $vs_campo_codigo; ?>&campo_valor=<?php print $vs_campo_valor; ?>";
                
            <?php if (isset($pa_parametros_campo["excluir"]))
            {
            ?>
            vs_url_lista_sugestoes = vs_url_lista_sugestoes + "&excluir=<?php print $pa_parametros_campo["excluir"]; ?>";
            <?php
            }
            ?>

            $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").load(vs_url_lista_sugestoes);

            if ($("#lista_<?php print $vs_nome_campo_lookup ?>").length == 0)
            {
                va_termos_inexistentes.push(vs_termo);
            } 
            else
            {
                $("#lista_<?php print $vs_nome_campo_lookup ?>").trigger('click');
            }

        }
    }
    $("#<?php print $vs_nome_campo_lookup ?>").val("Não encontrados: " + va_termos_inexistentes.join(';'));
    
    $("#div_adicionar_todos_<?php print $vs_nome_campo_lookup ?>").hide();
});

function adicionar_<?php print $vs_nome_campo_lookup; ?>(pn_valor_selecionado, ps_texto_selecionado)
{
    va_codigos = $("#<?php print $vs_id_campo_codigos ?>").val().split("|");

    vb_multiplos_valores = false;
    <?php
    if ($vb_multiplos_valores)
    {
    ?>
        vb_multiplos_valores = true;
    <?php
    }
    ?>

    vb_valor_no_input = false;
    <?php
    if ($vb_valor_no_input)
    {
    ?>
        vb_valor_no_input = true;
    <?php
    }
    ?>

    if ( ($("#<?php print $vs_id_campo_codigos; ?>").val() != "") && !vb_multiplos_valores && !vb_valor_no_input)
        return false;
    
    if (va_codigos.indexOf(pn_valor_selecionado) == -1)
    {
        vb_alterou_cadastro = true;
        vs_filtro = "";
        vs_atributo_complementar = "";
        vn_valor_selecionado = pn_valor_selecionado;

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

        <?php
        if (isset($pa_parametros_campo["selecionar_atributo"]))
        {
        ?>
            jQuery.ajaxSetup({async:false});
            
            vs_url_valor_selecao = pn_valor_selecionado;
            $.post("functions/ler_valor_selecao.php", {obj: "<?php print $pa_parametros_campo["objeto"]; ?>", cod: vs_url_valor_selecao, vs: "<?php print $pa_parametros_campo["selecionar_atributo"]; ?>"}, function(response)
            {
                vs_valor_selecionado = response;
            });
        <?php
        }
        else
        {
        ?>
            vs_valor_selecionado = ps_texto_selecionado;
        <?php
        }
        ?>

        if (<?php print $vb_permitir_repeticao_termo; ?>)
        {
            if ($("#<?php print $vs_id_campo_codigos ?>").val().length == 0)
                vn_valor_selecionado = pn_valor_selecionado + "_1";
            else
                vn_valor_selecionado = pn_valor_selecionado + "_" + (va_codigos.length + 1);
        }

        <?php if (!$vb_valor_no_input)
        {
        ?>
            vs_url_nova_linha = "functions/linha.php?tela=<?php print $vs_tela ?>&campo_lookup=<?php print $vs_nome_campo_lookup ?>&campo_codigos=<?php print $vs_id_campo_codigos ?>&mostrar_subcampos=<?php print $vb_mostrar_subcampos; ?>&codigo="+vn_valor_selecionado+"&valor="+encodeURIComponent(vs_valor_selecionado)+"&pode_editar=<?php print $vb_pode_editar; ?>"+vs_filtro;

            <?php
            if (isset($pa_parametros_campo["atributo_complementar"]))
            {
            ?>
                jQuery.ajaxSetup({async:false});
                
                vs_url_valor_selecao = pn_valor_selecionado;
                $.post("functions/ler_valor_selecao.php", {obj: "<?php print $pa_parametros_campo["objeto"]; ?>", cod: vs_url_valor_selecao, vs: "<?php print $pa_parametros_campo["atributo_complementar"]; ?>"}, function(response)
                {
                    vs_atributo_complementar = response;
                });

                if (vs_atributo_complementar != "")
                    vs_url_nova_linha = vs_url_nova_linha + "&<?php print $pa_parametros_campo["atributo_complementar"]; ?>=" + vs_atributo_complementar;
            <?php
            }
            ?>

            $.get(vs_url_nova_linha, function(data, status) {
                $("#div_selecionados_<?php print $vs_nome_campo_lookup ?>").append(data);
            });
        <?php
        }
        ?>

        if ( ($("#<?php print $vs_id_campo_codigos ?>").val().length == 0) || !vb_multiplos_valores)
        {
            $("#<?php print $vs_id_campo_codigos ?>").val(vn_valor_selecionado);
        }
        else
        {
            va_codigos = $("#<?php print $vs_id_campo_codigos ?>").val().split("|");
            vn_tamanho_lista = va_codigos.length;

            if (!va_codigos.includes(vn_valor_selecionado.toString()))
            {
                va_lista_codigos_atualizada = $("#<?php print $vs_id_campo_codigos ?>").val() + "|" + vn_valor_selecionado;
                $("#<?php print $vs_id_campo_codigos ?>").val(va_lista_codigos_atualizada);
                vn_tamanho_lista = vn_tamanho_lista + 1;
            }

            if (vn_tamanho_lista > 5)
                $("#div_remover_todos_<?php print $vs_nome_campo_lookup ?>").show();
        }

        <?php 
        if ($vb_multiplos_valores)
        {
        ?>
            $("#<?php print $vs_nome_campo_lookup ?>").val('');
        <?php
        }
        else
        {
        ?>
            $("#<?php print $vs_nome_campo_lookup ?>").val(vs_valor_selecionado);
        <?php
        }
        ?>

        <?php
        if (isset($pa_parametros_campo["controlar_exibicao"]))
        {
            foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
            {
            ?>
                atualizar_exibicao_<?php print $vs_campo_controlar ?>($("#<?php print $vs_id_campo_codigos ?>").val());
            <?php
            }
        }
        ?>
    }
    else
        $("#<?php print $vs_nome_campo_lookup ?>").val('');

    $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").empty();
    
    <?php 
    if (!$vb_multiplos_valores && !$vb_valor_no_input)
    {
    ?>
        $("#<?php print $vs_nome_campo_lookup ?>").hide();
    <?php
    }
    else
    {
    ?>
        $("#<?php print $vs_nome_campo_lookup ?>").focus();
    <?php
    }
    ?>

    <?php
    if (isset($pa_parametros_campo["conectar"]))
    {
    ?>
        atualizar_dependencias_<?php print $vs_nome_campo_lookup; ?>(pn_valor_selecionado);
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
            vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_objeto_fonte; ?>&campo=<?php print $vs_campo; ?>&modo=edicao&cod='+pn_valor_selecionado+'&exibir=<?php print $vb_exibir; ?>';
    
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
}

function atualizar_dependencias_<?php print $vs_nome_campo_lookup; ?>(pn_valor_selecionado)
{
    <?php
    if (isset($pa_parametros_campo["conectar"]))
    {
        foreach($pa_parametros_campo["conectar"] as $v_conectar)
        {
        ?>
            if (pn_valor_selecionado == "")
            {
                $("#div_selecionados_ids_<?php print $v_conectar["campo"]; ?>").empty();
                $("#ids_<?php print $v_conectar["campo"]; ?>").val('');
            }

            let formId = 'form_cadastro';

            if (!$("#" + formId).length) {
                formId = 'form_upload';
            }

            vs_filtro = $("#" + formId).serialize();
            
            vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_tela; ?>&campo=<?php print $v_conectar["campo"] ?>&modo=edicao&atualizacao=1&valor_campo_correlato=1&cod='+pn_valor_selecionado+"&"+vs_filtro;
                
            //console.log(vs_url_campo_atualizado);
            $.get(vs_url_campo_atualizado, function(data, status) 
            {
                v_field_to_update = document.querySelector("#div_<?php print $v_conectar["campo"]; ?>");
            
                v_updated_field = document.createElement('div');
                v_updated_field.innerHTML = data;

                v_field_to_update.parentNode.replaceChild(v_updated_field, v_field_to_update);
            });           
        <?php
        }
    }
    ?>

    <?php
    if (isset($pa_parametros_campo["controlar_exibicao"]))
    {
        foreach($pa_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
        {
        ?>
            v_valor =  $("#<?php print $vs_id_campo_codigos ?>").val();
            atualizar_exibicao_<?php print $vs_campo_controlar ?>(v_valor);
        <?php
        }
    }
    ?>
}

$(document).on('click', "#lista_<?php print $vs_nome_campo_lookup ?>", function()
{
    var vn_selected_index = document.getElementById("lista_<?php print $vs_nome_campo_lookup ?>").selectedIndex;

    if (vn_selected_index == -1) vn_selected_index = 0;

    var vn_selected_value = document.getElementById("lista_<?php print $vs_nome_campo_lookup ?>").options[vn_selected_index].value;
    var vs_selected_text = document.getElementById("lista_<?php print $vs_nome_campo_lookup ?>").options[vn_selected_index].text;    

    adicionar_<?php print $vs_nome_campo_lookup; ?>(vn_selected_value, vs_selected_text);
});

$(document).on('click', "#btn_remover_todos_<?php print $vs_nome_campo_lookup; ?>", function()
{
    $("#div_selecionados_<?php print $vs_nome_campo_lookup ?>").empty();
    $("#<?php print $vs_id_campo_codigos ?>").val('');
    $("#div_remover_todos_<?php print $vs_nome_campo_lookup ?>").hide();
});

<?php
if ($vb_permitir_cadastro)
{
?>
$(document).on('click', "#lnk_cadastrar_<?php print $vs_nome_campo_lookup ?>", function()
{
    event.preventDefault();

    vs_termo_busca = $("#<?php print $vs_nome_campo_lookup ?>").val();
    vo_post_data = {campo: "<?php print $vs_nome_campo_lookup ?>", escopo: "_in", obj: "<?php print $vs_objeto_campo ?>", <?php print $va_campos_salvar[0] ?>: vs_termo_busca, campo_salvar: "<?php print $va_campos_salvar[0] ?>"};

    <?php 
    $vn_contador_campos_salvar = 1;
    foreach($va_campos_salvar as $vs_campo_salvar)
    {
        if ($vn_contador_campos_salvar > 1)
        {
    ?>
            vo_post_data.<?php print $vs_campo_salvar; ?> = $("#<?php print $vs_campo_salvar; ?>").val();
    <?php
        }

        $vn_contador_campos_salvar++;
    }
    ?>

    <?php
    if (isset($pa_parametros_campo["dependencia"]))
    {
        if (isset($pa_parametros_campo["dependencia"]["campo"]))
            $va_dependencias = array($pa_parametros_campo["dependencia"]);
        else
            $va_dependencias = $pa_parametros_campo["dependencia"];

        foreach($va_dependencias as $v_campo_conexao)
        {
            if (isset($v_campo_conexao["campo"])) 
            {
        ?>
                vo_post_data.<?php print $v_campo_conexao["atributo"]; ?>=$("#<?php print ($v_campo_conexao["campo"] . $vs_sufixo_nome_campo); ?>").val();
        <?php
            }
            elseif (isset($v_campo_conexao["valor"]))
            {
        ?>
                vo_post_data.<?php print $v_campo_conexao["atributo"]; ?>=<?php print $v_campo_conexao["valor"]; ?>;
        <?php
            }
        }
    }
    ?>

    $.post("functions/salvar.php", vo_post_data, function(data, status)
    {
        $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").html(data);
        $("#div_sugestoes_<?php print $vs_nome_campo_lookup ?>").show();

        $("#lista_<?php print $vs_nome_campo_lookup ?>").val($("#lista_<?php print $vs_nome_campo_lookup ?> option:first").val());
        $("#lista_<?php print $vs_nome_campo_lookup ?>").trigger("click");
    });
}
);
<?php 
}
?>

<?php
}
?>

</script>

<?php
}
?>

</div>
