<div class="filtro-row row" id="div_opcoes_estatisticas_catalogacao">

    <?php
        $va_valores = array();

        $va_parametros_campo = [
            "html_text_input",
            "nome" => "data_inicial",
            "label" => "de",
            "formato" => "date",
        ];

        $vo_data = new html_text_input($vs_id_objeto_tela, "data_inicial", "linha");
        $vo_data->build($va_valores, $va_parametros_campo, "linha");

        $va_parametros_campo = [
            "html_text_input",
            "nome" => "data_final",
            "label" => "a",
            "formato" => "date",
        ];

        $vo_data = new html_text_input($vs_id_objeto_tela, "data_final", "linha");
        $vo_data->build($va_valores, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input", 
            "nome" => "agrupador",
            "label" => "Tipo",
            "sem_valor" => false,
            "valores" => [
                "dia" => "Diário",
                "mes" => "Mensal",
                "ano" => "Anual",
                "usuario" => "Por usuário"
            ]
        ];
    
        $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "agrupador");
        $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input", 
            "nome" => "tipo_operacao",
            "label" => "Operação",
            "sem_valor" => false,
            "valores" => [
                "1" => "Criação de registros",
                "2" => "Atualização de registros",
                "" => "Ambas",
            ]
        ];
    
        $vo_combo = new html_combo_input($vs_id_objeto_tela, "tipo_operacao");
        $vo_combo->build($va_valores, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input", 
            "nome" => "ordenacao_relatorio_catalogacao", 
            "label" => "Ordenar por", 
            "sem_valor" => false,
            "valores" => [
                "1" => "Valor ou termo agrupador",
                "2" => "Quantidade"
            ]
        ];

        $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "ordenacao_relatorio_catalogacao");
        $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);


        $va_parametros_campo = [
            "html_checkbox_input",
            "nome" => "incluir_porcentagem_catalogacao",
            "label" => "Incluir porcentagem?",
            "valor_padrao" => "1"
        ];

        $vo_checkbox_incluir_porcentagem = new html_checkbox_input($vs_id_objeto_tela, "incluir_porcentagem_catalogacao");
        $vo_checkbox_incluir_porcentagem->build($va_valores, $va_parametros_campo);
    ?>

    <div class="text-end">
        <button type="button" class="btn btn-outline-primary" id="btn_imprimir_relatorio_catalogacao">
            Imprimir
        </button>
    </div>

</div>

<script>
    $(document).on('click', "#btn_imprimir_relatorio_catalogacao", function() {
        const form = $("#form_lista");
        form.append('<input type="hidden" name="relatorio" value="estatisticas_catalogacao">');
        $.ajax({
            url: 'functions/imprimir_relatorio.php',
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