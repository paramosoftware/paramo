<div class="filtro-order col-md-5" style="margin-top:10px">

<?php
if (isset($va_visualizacao_lista["order_by"]))
{
    $va_parametros_campo = [
        "html_combo_input", 
        "nome" => "ordenacao", 
        "label" => "Ordenar registros por", 
        "objeto" => "ordenacao",
        "atributos" => ["codigo", "nome"],
        "sem_valor" => false, 
        "parametros_inicializacao" => $va_visualizacao_lista["order_by"],
        "css-class" => "form-select"
    ];

    $va_valores["ordenacao"] = $vn_ordenacao;
    
    $vo_combo_selecoes = new html_combo_input($vs_id_objeto_tela, "ordenacao");
    $vo_combo_selecoes->build($va_valores, $va_parametros_campo);
    
    if (!count($va_visualizacao_lista["order_by"]))
        print "&nbsp;";
?>

<script>

$(document).on('change', "#ordenacao", function()
{
    $("#form_lista").submit();
}
);

</script>

<?php
}
?>

</div>

<div class="filtro-space --none col-md-1">
    &nbsp;
</div>