<div class="filtro-row row" id="div_paginas_etiquetas">

<?php
    $va_valores = array();

    $va_parametros_campo = [
        "html_combo_input", 
        "nome" => "pagina_etiquetas", 
        "label" => "Página",
        "objeto" => "pagina_etiquetas",
        "sem_valor" => false
    ];
   
    $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "pagina_etiquetas");
    $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

    $va_parametros_campo = [
        "html_combo_input", 
        "nome" => "modelo_etiqueta", 
        "label" => "Modelo de etiqueta",
        "objeto" => "modelo_etiqueta", 
        "sem_valor" => false
    ];

    $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "pagina_etiquetas");
    $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

    $va_parametros_campo = [
        "html_combo_input",
        "nome" => "codigo_barras",
        "label" => "Código de barras",
        "valor_padrao" => "0",
        "valores" => [
            "1" => "Sim",
            "0" => "Não"
        ],
        "sem_valor" => false
    ];
   
    $vo_combo_modelo_etiqueta = new html_combo_input($vs_id_objeto_tela, "codigo_barra");
    $vo_combo_modelo_etiqueta->build($va_valores, $va_parametros_campo);

    $va_parametros_campo = [
        "html_number_input", 
        "nome" => "linha_inicial", 
        "label" => "Linha inicial",
        "valor_padrao" => 1
    ];
   
    $vo_texto_linha_inicial = new html_number_input($vs_id_objeto_tela, "linha_inicial");
    $vo_texto_linha_inicial->build($va_valores, $va_parametros_campo);

    $va_parametros_campo = array();
    $va_parametros_campo = [
        "html_number_input", 
        "nome" => "coluna_inicial", 
        "label" => "Coluna inicial",
        "valor_padrao" => 1
    ];
   
    $vo_texto_coluna_inicial = new html_number_input($vs_id_objeto_tela, "coluna_inicial");
    $vo_texto_coluna_inicial->build($va_valores, $va_parametros_campo);
?>
</div>

<div class="text-end">
    <button class="btn btn-outline-primary" type="button" id="btn_imprimir_etiquetas">Imprimir</button>
</div>

<script>
    $(document).on('click', "#btn_etiquetas", function()
    {
        if ($("#div_paginas_etiquetas").is(":visible"))
        {
            $("#div_paginas_etiquetas").hide();
            $("#btn_imprimir").show();
        }
        else
        {
            $("#div_paginas_etiquetas").show();
            $("#btn_imprimir").hide();
        }
    });

    $(document).on('click', "#btn_imprimir_etiquetas", function() {
        const form = $("#form_lista");
        $.ajax({
            url: 'functions/imprimir_etiquetas.php',
            type: "POST",
            data: form.serialize(),
            processData: false,
            success: function (data) {
                $("#modal-imprimir").modal("hide");
                getProgress(data);
            }
        });
    });
</script>