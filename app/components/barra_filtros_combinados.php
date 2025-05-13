<?php
    if (!isset($va_parametros_filtros_form))
        $va_parametros_filtros_form = array();
    
    if (!isset($va_parametros_filtros))
        $va_parametros_filtros = array();

    $va_contador_filtros_busca = array();

    $va_objeto = $va_parametros_filtros_form;

    $vs_modo = "listagem";
    require dirname(__FILE__) . "/../functions/configurar_campos_tela.php";

    foreach ($va_campos as $vs_campo_key => $va_campo_filtro)
    {
        $va_filtros_navegacao[$vs_campo_key] = $va_campo_filtro["label"];
    }

    if (isset($va_filtros_navegacao))
    {
    ?>

    <div class="row filtro no-margin-side hidden" id="filtro_combinado"
    <?php
    if (!$vb_busca_combinada)
        print ' style="display:none"; ';
    ?>
    >
        <div class="col-2"></div>

        <div class="col-8" id="filtros_combinados">
            <div class="row">
            <div class="col-11">
                <?php
                    $va_parametros_campo = [
                        "html_combo_input",
                        "nome" => "campo_sistema_nome",
                        "label" => "Adicionar filtro",
                        "sem_valor" => false,
                        "valores" => $va_filtros_navegacao
                    ];

                    $vo_combo_campos_busca = new html_combo_input($vs_id_objeto_tela, "campo_sistema_nome");
                    $vo_combo_campos_busca->build($va_parametros_filtros_form, $va_parametros_campo);
                ?>
            </div>

            <div class="col-1" style="margin-top:32px">
                <button class="btn btn-primary" type="button" onclick="adicionar_filtro_busca()">
                    <svg class="icon">
                        <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-plus"></use>
                    </svg>
                </button>
            </div>
            </div>

            <div id="filtros_disponiveis">
            <?php
                $vb_multiplas_instancias_campo = true;
                $va_contador_filtros_busca = array();

                $vn_contador = 0;
                $vn_contador_filtros_adicionados = 0;

                foreach ($va_parametros_filtros_form as $vs_id_campo => $va_filtro)
                {
                    $va_objeto[$vs_id_campo] = $va_filtro;

                    $vs_novo_id_campo = $vs_id_campo;

                    if (preg_match('/\w+(_F_\d+)_com_valor$/', $vs_id_campo))
                        $vs_novo_id_campo = str_replace("_com_valor", "", $vs_id_campo);
                    elseif (preg_match('/\w+(_F_\d+)_sem_valor$/', $vs_id_campo))
                        $vs_novo_id_campo = str_replace("_sem_valor", "", $vs_id_campo);

                    if ( preg_match('/\w+(_F_\d+)$/', $vs_id_campo) || preg_match('/\w+(_F_\d+)_com_valor$/', $vs_id_campo) || preg_match('/\w+(_F_\d+)_sem_valor$/', $vs_id_campo))
                        $vs_id_campo = substr($vs_id_campo, 0, strpos($vs_id_campo, "_F_"));

                    if (!is_array($va_filtro))
                        $va_filtro = array($va_filtro);

                    if ($vs_id_campo != $vs_novo_id_campo)
                    {
                        foreach ($va_filtro as $vs_filtro)
                        {
                            unset($va_campos);

                            if (!in_array($vs_id_campo, array_keys($va_contador_filtros_busca)))
                                $va_contador_filtros_busca[$vs_id_campo] = 1;
                            else
                                $va_contador_filtros_busca[$vs_id_campo] = $va_contador_filtros_busca[$vs_id_campo] + 1;

                            if (isset($va_parametros_filtros_consulta["concatenadores"][$vn_contador]))
                            {
                                $vs_valor_concatenador = $va_parametros_filtros_consulta["concatenadores"][$vn_contador];

                                // TODO: falta pensar melhor na extensão deste controle de acesso
                                /////////////////////////////////////////////////////////////////

                                if (in_array($vs_id_campo, $vo_objeto->controlador_acesso))
                                {
                                    $vs_key_controlador = array_search($vs_id_campo, $vo_objeto->controlador_acesso);

                                    if (isset($va_parametros_controle_acesso[$vs_key_controlador]) && $va_parametros_controle_acesso[$vs_key_controlador] != "")
                                    {
                                        if (!isset($va_parametros_filtros_consulta[$vs_novo_id_campo]))
                                        {
                                            $va_parametros_filtros_consulta[$vs_novo_id_campo] = [$va_parametros_controle_acesso[$vs_key_controlador], "="];
                                        }
                                        elseif (
                                            isset($va_parametros_filtros_consulta[$vs_novo_id_campo][0])
                                            &&
                                            !in_array($va_parametros_filtros_consulta[$vs_novo_id_campo][0], explode("|", $va_parametros_controle_acesso[$vs_key_controlador]))
                                        )
                                        {
                                            $vb_pode_editar = false;
                                        }
                                    }
                                }

                                require dirname(__FILE__)."/../functions/montar_filtro_combinado.php";

                                $vn_contador_filtros_adicionados++;
                            }

                            $vn_contador++;
                        }
                    }
                }
            ?>
            </div>

            <div class="row">
                <div class="col-6 text-right">
                    <button class="btn btn-primary px-4" type="button" id="btn_buscar_combinado">Buscar</button>
                </div>

                <div class="col-6 text-left">
                <button class="btn btn-outline-primary px-4" type="button" id="btn_limpar_filtro_combinado">Limpar</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    else
    {
    ?>
        <div class="row filtro no-margin-side hidden" id="filtro_combinado" style="display:none">
            <span style="margin-left:10px">Nenhum filtro configurado.</span>
        </div>
    <?php
    }
    ?>

<script>

var va_filtros_busca = <?php print json_encode(array_keys($va_contador_filtros_busca)); ?>;
var va_contador_filtros_busca = [];

<?php foreach ($va_contador_filtros_busca as $vs_filtro_busca => $vn_contador)
{
?>
    va_contador_filtros_busca["<?php print $vs_filtro_busca; ?>"] = <?php print $vn_contador; ?>;
<?php
}
?>

function adicionar_filtro_busca()
{
    //Para chamar corretamente o $.get mais de uma vez
    jQuery.ajaxSetup({async:false});

    vs_campo = $("#campo_sistema_nome").val();
    
    if (va_filtros_busca.indexOf(vs_campo) >= 0)
        va_contador_filtros_busca[vs_campo] += 1;
    else
    {
        va_filtros_busca.push(vs_campo);
        va_contador_filtros_busca[vs_campo] = 1;
    }

    vs_url_filtro_busca = 'functions/montar_filtro_combinado.php?obj=<?php print $vs_id_objeto_tela; ?>&campo='+vs_campo+'&multiplas_instancias=1&modo=listagem&instancia='+va_contador_filtros_busca[vs_campo]+"&numero_filtros="+document.getElementById("filtros_disponiveis").children.length;

    $.get(vs_url_filtro_busca, function(data, status)
    {
        $("#filtros_disponiveis").append(data);
    });
}

function remover_filtro_busca(ps_campo_id, ps_filtro_id)
{
    $("#div_"+ps_campo_id).remove();

    va_contador_filtros_busca[ps_filtro_id] = va_contador_filtros_busca[ps_filtro_id] - 1;
}

function atualizar_filtro(select, ps_filtro_id)
{
    var vs_field_text = "";

    if (select.options[select.selectedIndex].value == "_SEM_VALOR_")
        vs_field_text = "NÃO PREENCHIDO";
    else if (select.options[select.selectedIndex].value == "_COM_VALOR_")
        vs_field_text = "PREENCHIDO";

    if (vs_field_text != "")
    {
        $("#"+ps_filtro_id).append('<option value="'+select.options[select.selectedIndex].value+'">'+vs_field_text+'</option>');
        $("#"+ps_filtro_id).val(select.options[select.selectedIndex].value);
        $("#"+ps_filtro_id + " option:not(:selected)").attr("disabled", true);
    }
    else
    {
        $("#"+ps_filtro_id + " option[value='_SEM_VALOR_']").remove();
        $("#"+ps_filtro_id + " option[value='_COM_VALOR_']").remove();

        $("#"+ps_filtro_id + " option:not(:selected)").attr("disabled", false);
    }
}

function toggle_filtro_combinado()
{
    $("#filtro_combinado").toggle();
    $("#filtro").hide();

    if ($("#filtro_combinado").is(":visible"))
    {
        $("#btn_filtro_combinado").removeClass("dropdown-toggle");
        $("#btn_filtro_combinado").addClass("dropdown-toggle-revert");
    }
    else
    {
        $("#btn_filtro_combinado").removeClass("dropdown-toggle-revert");
        $("#btn_filtro_combinado").addClass("dropdown-toggle");
    }

    return false;
}

$(document).on('click', "#btn_buscar_combinado", function()
{
    $("#filtros").empty();
    
    $("#form_lista").attr('action', '<?php print $vs_form_action; ?>');
    $("#form_lista").attr('method', 'get');
    $("#form_lista").submit();
});

$(document).on('click', "#btn_limpar_filtro_combinado", function()
{
    $("#filtros_disponiveis").empty();
    va_filtros_busca.push = [];
    va_contador_filtros_busca = [];

    $("#form_lista").submit();

});

</script>