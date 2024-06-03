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

        if ($va_parametros_filtros_form && !$vb_busca_combinada)
        {
            $vb_exibir_filtro = true;
        }
    }

    //var_dump($va_objeto);
?>

<div class="row filtro no-margin-side hidden" id="filtro"
<?php
if (!$vb_exibir_filtro)
    print ' style="display:none"; ';
?>
>
    <div class="col-2"></div>

    <div class="col-8" id="filtros">
        <?php
            $vb_autenticar_usuario = false;
            require_once dirname(__FILE__)."/../functions/montar_campos.php";
        ?>

        <!-- Barra de busca por log de indexação -->

        <?php 
            require_once dirname(__FILE__)."/barra_filtros_log.php";

            $vs_class_button_exibir_filtros_log = "dropdown-toggle btn btn-outline-primary btn-sm";
            if ($vb_existe_filtro_log)
                $vs_class_button_exibir_filtros_log = "dropdown-toggle-revert btn btn-outline-primary btn-sm";
        ?>

        <!-- Barra de busca por log de indexação -->

        <div class="row">
            <div class="text-right">
                <button class="<?php print $vs_class_button_exibir_filtros_log; ?>" type="button" onclick="toggle_filtros_log(this)">Filtros de indexação&nbsp</button>
            </div>
        </div>

        <div class="row">
            <div class="col-6 text-right">
                <button class="btn btn-primary px-4" type="button" id="btn_buscar">Buscar</button>
            </div>

            <div class="col-6 text-left">
                <button class="btn btn-outline-primary px-4" type="button" id="btn_limpar">Limpar</button>
            </div>
        </div>
    </div>
</div>

<script>

function toggle_filtros_log(pbutton)
{
    if ($("#div_filtros_catalogacao").is(":visible"))
    {
        pbutton.classList.add("dropdown-toggle");
        pbutton.classList.remove("dropdown-toggle-revert");

        $("#log_data_inicial").val('');
        $("#log_data_final").val('');
        $("#tipo_operacao_log_codigo").val('');
        $("#log_usuario_codigo").val('');
    }
    else
    {
        pbutton.classList.remove("dropdown-toggle");
        pbutton.classList.add("dropdown-toggle-revert");
    }

    $("#div_filtros_catalogacao").toggle();
}

function toggle_filtro()
{
    $("#filtro_combinado").hide();
    $("#btn_filtro_combinado").removeClass("dropdown-toggle-revert");
    $("#btn_filtro_combinado").addClass("dropdown-toggle");

    var div = document.getElementById('filtro');

    vb_exists_hidden_element = false;
    vb_exists_visible_element = false;
    vb_filtro_log_visible = false;

    $("#filtros").children("div").each(function () 
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

    if (vb_exists_visible_element && vb_exists_hidden_element)
        div.style.display = "flex";

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
    $("#filtros_combinados").empty();

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
    var frm_elements = $("#filtros").find(".input").each(function ()
    {
        field_type = $(this).prop('type');

        switch (field_type)
        {
            case "text":
                $(this).val("");
                break;
            case "number":
                $(this).val("");
                break;
            case "hidden":
                $(this).val("");
                break;
            case "radio":
            case "checkbox":
                $(this).prop("checked", false);
                break;
            case "select-one":
                $(this).prop("selectedIndex", 0);
                break;
            case "date":
                $("input[type=date]").val("");
                break;    
            default:
                break;
        }
    });
}
);

</script>