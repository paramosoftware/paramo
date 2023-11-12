<div class="filtro-row row" id="div_opcoes_lista">

    <?php

        $va_parametros_campo = [
            "html_checkbox_input",
            "nome" => "incluir_representante_digital",
            "label" => "Incluir miniatura do representante digital?",
            "valor_padrao" => "1"
        ];

        $vo_checkbox_incluir_porcentagem = new html_checkbox_input($vs_id_objeto_tela, "incluir_representante_digital");
        $vo_checkbox_incluir_porcentagem->build($va_valores, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input",
            "nome" => "posicao_representante_digital",
            "label" => "Posição da miniatura?",
            "sem_valor" => false,
            "valores" => [
                "left_side" => "Ao lado",
                "first_row" => "Na primeira linha"
            ]
        ];

        $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "posicao_representante_digital");
        $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

        $va_parametros_campo = [
            "html_checkbox_input",
            "nome" => "quebrar_linha",
            "label" => "Quebrar linha na divisão de páginas?",
            "valor_padrao" => "0"
        ];

        $vo_checkbox_incluir_porcentagem = new html_checkbox_input($vs_id_objeto_tela, "quebrar_linha");
        $vo_checkbox_incluir_porcentagem->build($va_valores, $va_parametros_campo);


    ?>

    <div class="text-end">
        <button type="button" class="btn btn-outline-primary" id="btn_imprimir_lista">
            Imprimir
        </button>
    </div>

</div>

<script>
    $(document).on('click', "#btn_imprimir_lista", function () {
        const form = $("#form_lista");
        form.attr('action', 'functions/imprimir_lista.php')
        form.attr('method', 'post');
        form.attr('target', '_blank');
        form.submit();

        form.attr('action', 'listar.php');
        form.attr('method', 'get');
        form.attr('target', '');
    });


    $(document).on('change', "#incluir_representante_digital_chk", function () {
        const checked = $(this).is(":checked");
        if (checked) {
            $("#div_posicao_representante_digital").show();
        } else {
            $("#div_posicao_representante_digital").hide();
        }
    });
</script>