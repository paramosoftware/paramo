<div class="filtro-row row" id="div_opcoes_exportacao">

    <?php

        $va_parametros_campo = [
            "html_checkbox_input",
            "nome" => "incluir_representante_digital_exportacao",
            "label" => "Incluir miniatura do representante digital?",
            "valor_padrao" => "1"
        ];

        $vo_checkbox_incluir_porcentagem = new html_checkbox_input($vs_id_objeto_tela, "incluir_representante_digital_exportacao");
        if(utils::is_phpspreadsheet_installed())
            $vo_checkbox_incluir_porcentagem->build($va_valores, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input",
            "nome" => "formato_exportacao",
            "label" => "Formato do arquivo?",
            "sem_valor" => false,
            "valores" => [
                "csv" => ".csv"
            ]
        ];
        if(utils::is_phpspreadsheet_installed())
            $va_parametros_campo["valores"]["xlsx"] = ".xlsx";

        $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "formato_exportacao");
        $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

    ?>

    <div class="text-end">
        <button type="button" class="btn btn-outline-primary" id="btn_exportar">
            Exportar
        </button>
    </div>

</div>

<script>
    $(document).on('click', "#btn_exportar", function () {


        let numeroRegistros = $("#listagem-numero-registros") ? $("#listagem-numero-registros")[0].innerText : 0;
        numeroRegistros = numeroRegistros.split(" ")[0];
        if (numeroRegistros > 2000 && false !== <?= print (utils::is_phpspreadsheet_installed() ? "true" : "false"); ?>) {
            if ($("#incluir_representante_digital_exportacao_chk").is(":checked")) {
                if (!confirm("A impressão possui mais de 2000 registros com a opção para incluir representantes digitais marcada. " +
                    "O arquivo gerado pode ficar muito grande e difícil de ser manuseado. " +
                    "Considere não incluir os representantes digitais para diminuir o tamanho do arquivo. Deseja continuar?")) {
                    return;
                }
            }
        }

        const form = $("#form_lista");
        $.ajax({
            url: 'functions/exportacao.php',
            type: "POST",
            data: form.serialize(),
            processData: false,
            complete: function (data) {
                $("#modal-exportar").modal("hide");
                getProgress(data);
            }
        });
    });
</script>