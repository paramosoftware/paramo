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

    $vn_ano_maximo = "9999";
    if (isset($pa_parametros_campo["ano_maximo"]))
        $vn_ano_maximo = $pa_parametros_campo["ano_maximo"];

    if (!isset($vn_largura_campo))
        $vn_largura_campo = '';

    if (!isset($vn_tamanho))
        $vn_tamanho = '';

    if (!isset($pa_parametros_campo["formato"]))
        $vs_formato = "completo";
    else
        $vs_formato = $pa_parametros_campo["formato"];

    if (!isset($vn_dia_inicial))
        $vn_dia_inicial = "";
    
    if (!isset($vn_mes_inicial))
        $vn_mes_inicial = "";
    
    if (!isset($vn_ano_inicial))
        $vn_ano_inicial = "";

    if (!isset($vn_dia_final))
        $vn_dia_final = "";
    
    if (!isset($vn_mes_final))
        $vn_mes_final = "";
    
    if (!isset($vn_ano_final))
        $vn_ano_final = "";

    if (!isset($vb_presumido))
        $vb_presumido = false;

    if (!isset($vb_sem_data))
        $vb_sem_data = false;

    if (!isset($vs_complemento))
        $vs_complemento = "";

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;


    $vs_div_container = "";
    if ($vs_ui_element != "linha")
        $vs_div_container = "campo_formulario";
?>

<div class="mb-3" id="div_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
<?php
    if (!$vb_pode_exibir)
        print ' style="display:none"';
?>>
    <?php if ($vs_ui_element != "linha") {
    ?>
        <div class="row">
            <div class="col-9">
                <label class="form-label" for="exampleFormControlInput1" title="<?php if (isset($pa_parametros_campo["descricao"])) print $pa_parametros_campo["descricao"]; ?>">
                    <?php if ($vs_modo == "lote")
                    {
                    ?>
                        <input type="checkbox" class="check-campo form-check-input" id="chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
                    <?php
                    }
                    ?>

                    <?php print $vs_label_campo; ?>
                </label>
            </div>

            <?php if (isset($pa_parametros_campo["permitir_escolha_formato"]) && $pa_parametros_campo["permitir_escolha_formato"])
            {
            ?>
            <div class="col-3">
                <select class="form-select" id="formato_data_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
                    <option value="1" <?php print ($vn_formato_data == 1) ? " selected" : ""; ?>>dia</option>
                    <option value="2" <?php print ($vn_formato_data == 2) ? " selected" : ""; ?>>mês/ano</option>
                    <option value="3" <?php print ($vn_formato_data == 3) ? " selected" : ""; ?>>ano</option>
                    <option value="4" <?php print ($vn_formato_data == 4) ? " selected" : ""; ?>>década</option>
                    <option value="5" <?php print ($vn_formato_data == 5) ? " selected" : ""; ?>>século</option>
                    <option value="6" <?php print ($vn_formato_data == 6) ? " selected" : ""; ?>>intervalo livre</option>
                </select>
            </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    else
        print $vs_label_campo;
    ?>

    <input type="hidden" class="form-control input" name="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>" value="_data_"
    <?php
        if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
            print ' disabled';
    ?>
    >

    <div class="row" id="linha_data_1_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
        <?php
            if ( (isset($pa_parametros_campo["nao_exibir"]) && $pa_parametros_campo["nao_exibir"]) || ($vs_modo == "lote") )
                print ' style="display:none"';

            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled ';
        ?>
    >
        <div class="col-6">
            <div class="row">
                <div class="col-4" id="dia_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                <?php
                if (!in_array($vn_formato_data, [1,6]))
                    print ' style="display:none"';
                ?>
                >
                    <input type="number" class="form-control input" size="2" max="31" placeholder="dd" name="<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vn_dia_inicial; ?>"
                    <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                        print ' disabled';
                    ?>
                    >
                </div>

                <div class="col-4" id="mes_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                <?php
                if (!in_array($vn_formato_data, [1,2,6]))
                print ' style="display:none"';
                ?>
                >
                    <input type="number" class="form-control input" size="2" max="12" placeholder="mm" name="<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vn_mes_inicial; ?>"
                    <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                        print ' disabled';
                    ?>
                    >
                </div>

                <div class="col-4" id="ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                <?php
                if (in_array($vn_formato_data, [4,5]))
                print ' style="display:none"';
                ?>
                >
                    <input type="number" class="form-control input" size="4" min="1000" max="<?php print $vn_ano_maximo; ?>" placeholder="aaaa" name="<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vn_ano_inicial; ?>"
                    <?php
                        if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                            print ' disabled';
                    ?>
                    >
                </div>

                <?php if (isset($pa_parametros_campo["permitir_escolha_formato"]) && $pa_parametros_campo["permitir_escolha_formato"])
                {
                ?>
                    <div class="col-3" id="decada_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                    <?php if ($vn_formato_data != 4)
                        print ' style="display:none"';
                    ?>
                    >
                        <select class="form-select" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_decada"
                        <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                            print ' disabled';
                        ?>                        
                        >
                            <?php
                            $vn_decada_selecionada = "";
                            if ($vn_ano_inicial)
                                $vn_decada_selecionada = $vn_ano_inicial - 1;

                            $vn_contador_decada = 170;
                            while ( $vn_contador_decada <= floor(date("Y")/10) )
                            {
                                $vn_decada = $vn_contador_decada*10;
                            ?>
                                <option value="<?php print $vn_decada ?>" <?php print ($vn_decada == $vn_decada_selecionada) ? " selected" : ""; ?>><?php print $vn_decada ?></option>
                            <?php 
                                $vn_contador_decada++;
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-4" id="seculo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                    <?php if ($vn_formato_data != 5)
                        print ' style="display:none"';
                    ?>
                    >
                        <?php
                            $vn_seculo_selecionado = "";
                            if ($vn_ano_inicial)
                                $vn_seculo_selecionado = $vn_ano_inicial - 1;
                        ?>

                        <select class="form-select" id="<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_seculo"
                        <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                            print ' disabled';
                        ?>
                        >
                            <option value="16" <?php print ($vn_seculo_selecionado == 1600) ? " selected" : ""; ?>>séc. XVII</option>
                            <option value="17" <?php print ($vn_seculo_selecionado == 1700) ? " selected" : ""; ?>>séc. XVIII</option>
                            <option value="18" <?php print ($vn_seculo_selecionado == 1800) ? " selected" : ""; ?>>séc. XIX</option>
                            <option value="19" <?php print ($vn_seculo_selecionado == 1900) ? " selected" : ""; ?>>séc. XX</option>
                            <option value="20" <?php print ($vn_seculo_selecionado == 2000) ? " selected" : ""; ?>>séc. XXI</option>
                        </select>
                    </div>
                <?php
                }
                ?>
            </div>            
        </div>
        
        <div class="col-6 <?php $vn_formato_data != 6 ? print 'd-none' : print ''; ?>" id="div_data_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>">
            <div class="row">
                <div class="col" id="dia_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                <?php
                if (($vn_formato_data != 6))
                    print ' style="display:none"';
                ?>
                >
                    <input type="number" class="form-control input" size="2" max="31" placeholder="dd" name="<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vn_dia_final; ?>"
                    <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                        print ' disabled';
                    ?>
                    >
                </div>

                <div class="col" id="mes_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                <?php
                if (($vn_formato_data != 6))
                    print ' style="display:none"';
                ?>
                >
                    <input type="number" class="form-control input" size="2" max="12" placeholder="mm" name="<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vn_mes_final; ?>"
                    <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                        print ' disabled';
                    ?>
                    >
                </div>

                <div class="col" id="ano_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
                <?php
                if (($vn_formato_data != 6))
                    print ' style="display:none"';
                ?>
                >
                    <input type="number" class="form-control input" size="4" min="1000" placeholder="aaaa" name="<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vn_ano_final; ?>"
                    <?php if ($vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                        print ' disabled';
                    ?>
                    >
                </div>
            </div>
        </div>
    </div>

    <?php if (($vs_modo == "listagem") && config::get(["f_filtros_busca_preenchimento_campo"]))
    {
    ?>
    <div>
        <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo ?>_com_valor" id="<?php print $vs_nome_campo ?>_com_valor" onclick="alterar_valor_filtro_<?php print $vs_nome_campo ?>(this.checked, 'com_valor')"
        <?php 
        if ($vb_marcar_com_valor)
            print " checked";
        ?>
        > preenchido
        
        <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo ?>_sem_valor" id="<?php print $vs_nome_campo ?>_sem_valor" onclick="alterar_valor_filtro_<?php print $vs_nome_campo ?>(this.checked, 'sem_valor')"
        <?php 
        if ($vb_marcar_sem_valor)
            print " checked";
        ?>
        > não preenchido
    </div>
    <?php
    }
    ?>
    
    <?php if ($vs_modo != "listagem")
    {
    ?>
    
        <div class="row linha_checkbox_espacamento" id="linha_data_2_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
        <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled style="display:none"';
        ?>
        >
            <?php
            if (!isset($pa_parametros_campo["exibir_presumido"]))
            {
            ?>

                <div class="col-2">
                    <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo ?>_presumido<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_presumido<?php print $vs_sufixo_nome_campo ?>" value="1"
                    <?php
                        if ( $vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                            print ' disabled';
                    ?>

                    <?php
                    if ($vb_presumido)
                        print " checked";

                    ?>>
                    <label class="form-check-label" for="flexCheckDefault">Presumida</label>
                </div>
            <?php
            }
            ?>

            <?php if (!isset($pa_parametros_campo["exibir_sem_data"])) { ?>

                <div class="col-2">
                    <input class="form-check-input" type="checkbox" name="<?php print $vs_nome_campo ?>_sem_data<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_sem_data<?php print $vs_sufixo_nome_campo ?>" value="1"
                    <?php
                        if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                            print ' disabled';
                    ?>

                    <?php
                    if ($vb_sem_data)
                        print " checked";

                    ?>>
                    <label class="form-check-label" for="flexCheckDefault">sem data</label>
                </div>
            <?php } ?>
        </div>
    <?php
    }
    ?>

    <?php
    if (
        (isset($pa_parametros_campo["exibir_complemento"]) && $pa_parametros_campo["exibir_complemento"])
        ||
        (isset($pa_parametros_campo["exibir_periodo"]) && $pa_parametros_campo["exibir_periodo"] && count($va_periodos_amplos))
    )
    {
    ?>
        <div class="row linha_checkbox_espacamento" id="linha_data_3_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>"
        <?php
            if (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"])
                print ' disabled style="display:none"';
        ?>
        >
            <?php
            if (isset($pa_parametros_campo["exibir_complemento"]) && $pa_parametros_campo["exibir_complemento"]) 
            {
            ?>
                <label class="form-check-label" for="flexCheckDefault">Complemento</label>

                <div class="col-12">
                    <input class="form-control input" type="text" name="<?php print $vs_nome_campo ?>_complemento<?php print $vs_sufixo_nome_campo ?>" id="<?php print $vs_nome_campo ?>_complemento<?php print $vs_sufixo_nome_campo ?>" value="<?php print $vs_complemento; ?>"
                    <?php
                        if ( $vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                            print ' disabled';
                    ?>
                    >                
                </div>
            <?php
            }
            ?>

            <?php
            if (isset($pa_parametros_campo["exibir_periodo"]) && $pa_parametros_campo["exibir_periodo"] && count($va_periodos_amplos))
            {
            ?>
                <label class="form-check-label" for="flexCheckDefault">Período</label>

                <div class="col-12">
                    <?php
                        $vn_seculo_selecionado = "";
                        if ($vn_ano_inicial)
                            $vn_seculo_selecionado = $vn_ano_inicial - 1;
                    ?>

                    <select class="form-select" name="<?php print $vs_nome_campo ?>_periodo<?php print $vs_sufixo_nome_campo ?>"
                    <?php
                        if ( $vb_sem_data || (isset($pa_parametros_campo["desabilitar"]) && $pa_parametros_campo["desabilitar"]))
                            print ' disabled';
                    ?>
                    >
                        <option value=""></option>

                        <?php foreach ($va_periodos_amplos as $vs_key_periodo => $vs_periodo_amplo)
                        {
                        ?>
                            <option value="<?php print $vs_periodo_amplo ?>" <?php print ($vs_periodo == $vs_periodo_amplo) ? " selected" : ""; ?>><?php print $vs_periodo_amplo ?></option>
                        <?php
                        }
                        ?>
                    </select>          
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>
</div>

<script>

$(document).on('click', "#chk_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>", function()
{
    $("#linha_data_1_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").toggle();
    $("#linha_data_2_<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").toggle();

    $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo . $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_presumido<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_presumido<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_sem_data<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_sem_data<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
});

$(document).on('click', "#<?php print $vs_nome_campo ?>_sem_data<?php print $vs_sufixo_nome_campo ?>", function()
{
    $("#<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_presumido<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_presumido<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_decada<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_decada<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
    $("#<?php print $vs_nome_campo ?>_seculo<?php print $vs_sufixo_nome_campo ?>").prop("disabled", !$('#<?php print $vs_nome_campo ?>_seculo<?php print $vs_sufixo_nome_campo ?>').prop('disabled'));
});

$(document).on('change', "#formato_data_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>", function()
{
    $("#dia_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();
    $("#mes_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();

    $("#div_data_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").addClass("d-none");
    $("#dia_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();
    $("#mes_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();
    $("#ano_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();

    $("#decada_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();
    $("#seculo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();

    $("#<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").val("");

    $("#<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>").val("");

    switch ($(this).val())
    {
        case "1":
            $("#dia_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#mes_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();

            break;

        case "2":
            $("#mes_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();

            break;

        case "3":
            $("#ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();

            break;

        case "4":
            $("#ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();
            $("#decada_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            
            $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_decada").trigger("change");

            break;

        case "5":
            $("#ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").hide();
            $("#seculo_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();

            $("#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_seculo").trigger("change");
            break;

        case "6":
            $("#dia_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#mes_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#ano_inicial_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();

            $("#div_data_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").removeClass("d-none");
            $("#dia_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#mes_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();
            $("#ano_final_<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>").show();

            break;
    }
});

$(document).on('change', "#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_decada", function()
{
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").val($(this).val()*1+1);
    $("#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>").val($(this).val()*1+10);
});

$(document).on('change', "#<?php print $vs_nome_campo . $vs_sufixo_nome_campo; ?>_seculo", function()
{
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").val($(this).val()*100+1);
    $("#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>").val($(this).val()*100+100);
});

function alterar_valor_filtro_<?php print $vs_nome_campo ?>(pb_checked, ps_valor)
{
    if (ps_valor == "sem_valor")
        $("#<?php print $vs_nome_campo ?>_com_valor").prop("checked", false);
    else if (ps_valor == "com_valor")
        $("#<?php print $vs_nome_campo ?>_sem_valor").prop("checked", false);

    $("#<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").val("");

    $("#<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>").val("");
    $("#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>").val("");

    $("#<?php print $vs_nome_campo ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo ?>_dia_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo ?>_mes_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo ?>_ano_inicial<?php print $vs_sufixo_nome_campo ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo ?>_dia_final<?php print $vs_sufixo_nome_campo ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo ?>_mes_final<?php print $vs_sufixo_nome_campo ?>").prop("disabled", pb_checked);
    $("#<?php print $vs_nome_campo ?>_ano_final<?php print $vs_sufixo_nome_campo ?>").prop("disabled", pb_checked);
};

</script>