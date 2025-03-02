<div class="filtro-row row">

<?php
    $va_parametros_campo = [
        "html_combo_input", 
        "nome" => "visualizacao_codigo", 
        "label" => "Visualização", 
        "objeto" => "visualizacao", 
        "sem_valor" => true,
        "dependencia" => [
            [
                "campo" => "recurso_sistema_codigo",
                "atributo" => "visualizacao_recurso_sistema_codigo"
            ]
        ],
        "filtro" => [
            [
                "atributo" => "visualizacao_habilitado",
                "valor" => 1
            ]
        ]
    ];
   
    $vo_combo_visualizacoes = new html_combo_input($vs_id_objeto_tela, "visualizacao_codigo");

    if (isset($va_parametros_filtros_form))
        $va_valores = array_merge($_GET, $va_parametros_filtros_form);
    else
        $va_valores = $_GET;
        
    $va_valores["recurso_sistema_id"] = $vs_id_objeto_tela;
    $va_valores["recurso_sistema_codigo"] = $vn_recurso_sistema_codigo;

    $vo_combo_visualizacoes->build($va_valores, $va_parametros_campo);
?>

<script>

$(document).on('change', "#visualizacao_codigo", function()
{
    $("#form_lista").submit();
}
);

</script>

</div>