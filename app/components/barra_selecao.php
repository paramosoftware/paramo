<div class="filtro-order" style="margin-top:10px">
<?php

    $vn_selecao = "";
    if (isset($_GET['selecao']))
        $vn_selecao = $_GET['selecao'];
    elseif (isset($_POST['selecao']))
        $vn_selecao = $_POST['selecao'];

    $va_itens_selecao = array();
    if ($vn_selecao)
    {
        $vo_selecao = new selecao($vs_id_objeto_tela);
        $va_selecao = $vo_selecao->ler($vn_selecao, "navegacao");

        if (isset($va_selecao["selecao_item_codigo"]))
        {
            foreach ($va_selecao["selecao_item_codigo"] as $va_item)
            {
                $va_itens_selecao[] = $va_item["selecao_item_codigo"];
            }
        }
    }

    $va_parametros_campo = [
        "html_combo_input", 
        "nome" => "selecao", 
        "label" => "Adicionar item(ns) à seleção", 
        "objeto" => "selecao",
        "atributos" => ["selecao_codigo", "selecao_nome"],
        "atributo" => "selecao_codigo",
        "sem_valor" => true,
        "css-class" => "form-select",
        "filtro" => [
            [
                "atributo" => "selecao_usuario_codigo",
                "valor" => $vn_usuario_logado_codigo
            ],
            [
                "atributo" => "selecao_recurso_sistema_codigo",
                "valor" => $vn_recurso_sistema_codigo
            ]
        ]
    ];
    
    $va_valores = $va_parametros_filtros_consulta;
    $va_valores["selecao"] = $vn_selecao;

    $vo_combo_selecoes = new html_combo_input($vs_id_objeto_tela, "selecao");
    $vo_combo_selecoes->build($va_valores, $va_parametros_campo);
?>

<div class="row" <?php if (!isset($vn_selecao) || !$vn_selecao) print ' style="display:none"'; ?>>
    <div class="text-right">
        <button class="btn btn-outline-primary" type="button" id="btn_adicionar_selecao">
            Adicionar todos os registros
        </button>
    </div>
</div>

</div>

<script>

$(document).on('change', "#selecao", function(event)
{
    $("#form_lista").submit();
});

$(document).on('click', ".check-selecao", function()
{
    vn_item_codigo = parseInt($(this).attr('id').replace('chk_selecao_', ''));

    vo_post_data = {};
    vo_post_data.item_codigo = vn_item_codigo;
    vo_post_data.selecao_codigo = $("#selecao").val();

    if ($(this).is(':checked'))
    {
        $.post('functions/adicionar_item_selecao.php', vo_post_data, function(response)
        { 
            if (response.trim() != '')
                alert(response);
        });
    }
    else
    {
        $.post('functions/remover_item_selecao.php', vo_post_data, function(response)
        { 
            if (response.trim() != '')
                alert(response);
        });
    }
});

$(document).on('click', "#btn_adicionar_selecao", function(event)
{
    $.post("functions/adicionar_listagem_selecao.php", $.param($("#form_lista").serializeArray()), function(response) {
        if (response == "")
        {
            $("#form_lista").submit();
        }
        else
        {
            console.log(response);
        }
    });
});

</script>