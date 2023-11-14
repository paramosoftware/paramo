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


        let numeroRegistros = $("#listagem-numero-registros") ? $("#listagem-numero-registros")[0].innerText : 0;
        numeroRegistros = numeroRegistros.split(" ")[0];
        if (numeroRegistros > 2000) {
            if ($("#incluir_representante_digital_chk").is(":checked")) {
                if (!confirm("A impressão possui mais de 2000 registros com a opção para incluir representantes digitais marcada. " +
                    "O arquivo gerado pode ficar muito grande e difícil de ser manuseado. " +
                    "Considere não incluir os representantes digitais para diminuir o tamanho do arquivo. Deseja continuar?")) {
                    return;
                }
            }
        }

        const form = $("#form_lista");
        $.ajax({
            url: 'functions/imprimir_lista.php',
            type: "POST",
            data: form.serialize(),
            processData: false,
            success: function (data) {
                $("#modal-imprimir").modal("hide");
                getProgress(data);
            }
        });
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