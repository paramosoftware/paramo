<?php
    if (!isset($va_parametros_filtros_form))
        $va_parametros_filtros_form = array();
    
    if (!isset($va_parametros_filtros))
        $va_parametros_filtros = array();

    $va_objeto = $va_parametros_filtros_form;
    
    // Vou permitir que a exibição do filtro também
    // possa ser controlada por fora
    //////////////////////////////////////////////

    $vb_filtro_oculto_instituicao = $vb_filtro_oculto_instituicao ?? false;
    $vb_filtro_oculto_acervo = $vb_filtro_oculto_acervo ?? false;
    
    if (!isset($vb_exibir_filtro))
    {
        $vb_exibir_filtro = false;

        if ($va_parametros_filtros_form)
        {
            $vb_exibir_filtro = true;
        }
    }

    $vb_exibir_filtro = true;
?>

<div class="row filtro no-margin-side hidden" id="filtro"
<?php
if (!$vb_exibir_filtro)
    print ' style="display:none"; ';
?>
>
    <div class="col-2"></div>

    <div class="col-8" id="filtros">
        <div class="row">
        <div class="col-11">
            <?php
                $vs_modo = "listagem";
                require dirname(__FILE__) . "/../functions/configurar_campos_tela.php";

                foreach ($va_campos as $vs_campo_key => $va_campo)
                {
                    $va_filtros_navegacao[$vs_campo_key] = $va_campo["label"];
                }

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
                if (strpos($vs_id_campo, "_F_") !== false)
                    $vs_id_campo = substr($vs_id_campo, 0, strpos($vs_id_campo, "_F_"));
                    
                if (!is_array($va_filtro))
                    $va_filtro = array($va_filtro);

                foreach ($va_filtro as $vs_filtro)
                {
                    unset($va_campos);

                    if (!in_array($vs_id_campo, array_keys($va_contador_filtros_busca)))
                        $va_contador_filtros_busca[$vs_id_campo] = 1;
                    else
                        $va_contador_filtros_busca[$vs_id_campo] = $va_contador_filtros_busca[$vs_id_campo] + 1;

                    $vs_novo_id_campo = $vs_id_campo . "_F_" . $va_contador_filtros_busca[$vs_id_campo];

                    $va_objeto[$vs_novo_id_campo] = $vs_filtro;
                                            
                    if (isset($va_parametros_filtros_consulta["concatenadores"][$vn_contador]))
                    {
                        $vs_valor_concatenador = $va_parametros_filtros_consulta["concatenadores"][$vn_contador];

                        require dirname(__FILE__)."/../functions/montar_filtro_combinado.php";

                        $vn_contador_filtros_adicionados++;
                    }

                    $vn_contador++;
                }
            }
        ?>
        </div>

        <div class="row">
            <div class="col-6 text-right">
                <button class="btn btn-primary px-4" type="button" id="btn_buscar">Buscar</button>
            </div>

            <div class="col-6 text-left">
                <button class="btn btn-outline-primary px-4 bg-cor-branca" type="button" id="btn_limpar">Limpar</button>
            </div>
        </div>
    </div>
</div>

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
        document.getElementById("filtros_disponiveis").innerHTML += data;
    });
}

function remover_filtro_busca(ps_campo_id, ps_filtro_id)
{
    $("#div_"+ps_campo_id).remove();

    va_contador_filtros_busca[ps_filtro_id] = va_contador_filtros_busca[ps_filtro_id] - 1;
}

function toggle_filtro()
{
    var div = document.getElementById('filtro');

    vb_exists_hidden_element = false;
    vb_exists_visible_element = false;
    vb_filtro_log_visible = false;

    $("#filtros").find("div").each(function () 
    {
        if ($(this).is(":visible"))
            vb_exists_visible_element = true;
        else if ($(this).attr('hidden') != "hidden")
        {
            if ( ($(this).parent().prop("id") != "") && ($(this).parent().prop("id") != "div_filtros_catalogacao") && ($(this).prop("id") != "div_filtros_catalogacao") )
            {
                vb_exists_hidden_element = true;
            }
        }
    });

    div.style.display = div.style.display == 'none' ? 'flex ': 'none';

    var elems = document.querySelectorAll(".filtros");

    if(div.style.display === 'flex')
    {
        [].forEach.call(elems, function(el) 
        {
            el.classList.remove("dropdown-toggle");
            el.classList.add("dropdown-toggle-revert");
        });
    }
    else
    {
        [].forEach.call(elems, function(el) 
        {
            el.classList.remove("dropdown-toggle-revert");
            el.classList.add("dropdown-toggle");
        });
    }

    if (vb_exists_visible_element && vb_exists_hidden_element)
        div.style.display = "none";

    if (vb_exists_hidden_element)
    {
        if ($("#div_filtros_catalogacao").is(":visible"))
            vb_filtro_log_visible = true;
        
        $("#filtros").find("div").show();
        
        if (!vb_filtro_log_visible)
            $("#div_filtros_catalogacao").hide();
    }

    var select_elements = $("#filtros").find("select");
    select_elements.each(function()
    {
        var select_element = $(this);
        var select_options = select_element.find('option');

        if (select_options.length <= 1)
        {
            select_element.parent().hide();
        }

    });

    $("#filtro").find(".input").first().focus();

    return false;
}

$(document).on('click', "#btn_buscar", function()
{
    $("#form_lista").attr('action', '<?php print $vs_form_action; ?>');
    $("#form_lista").attr('method', 'get');
    $("#form_lista").submit();
}
);

$(document).on('keyup', ".form-control", function(event)
{
    if ( (event.key == "Enter") && ($(this).attr('name') != "busca") )
    {
        $("#btn_buscar").trigger("click");
    }
});

$(document).on('click', "#btn_limpar", function()
{
    var frm_elements = form_lista.elements;

    for (i = 0; i < frm_elements.length; i++)
    {
        field_type = frm_elements[i].type.toLowerCase();

        switch (field_type)
        {
            case "text":
            case "number":
                frm_elements[i].value = "";
                break;
            case "radio":
            case "checkbox":
                if (frm_elements[i].checked)
                {
                    frm_elements[i].checked = false;
                }
                break;
            case "select-one":
                frm_elements[i].selectedIndex = -1;
                break;
            default:
                break;
        }
    }
}
);

</script>