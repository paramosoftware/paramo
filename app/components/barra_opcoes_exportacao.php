<div class="filtro-row row" id="div_opcoes_exportacao">

    <?php
        $va_valores_formato_exportacao = [];

        if (utils::is_phpspreadsheet_installed()) 
        {
            $va_valores_formato_exportacao[utils::FORMATO_EXPORTACAO_XLSX] = "Planilha Excel (" . utils::EXTENSAO_EXPORTACAO_XLSX . ")";
        }

        $va_valores_formato_exportacao[utils::FORMATO_EXPORTACAO_CSV] = "Arquivo CSV (" . utils::EXTENSAO_EXPORTACAO_CSV . ")";

        $va_parametros_campo = [
            "html_combo_input",
            "nome" => "formato_exportacao",
            "label" => "Formato do arquivo?",
            "sem_valor" => false,
            "valores" => $va_valores_formato_exportacao,
        ];

        $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "formato_exportacao");
        $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

        $va_script_name = explode("/",$_SERVER['SCRIPT_NAME']);

        if (utils::is_phpspreadsheet_installed() && end($va_script_name) != "ficha.php")
        {
            $va_parametros_campo = [
                "html_checkbox_input",
                "nome" => "incluir_representante_digital_exportacao",
                "label" => "Incluir miniatura do representante digital?",
                "valor_padrao" => "1"
            ];

            $vo_checkbox_incluir_porcentagem = new html_checkbox_input($vs_id_objeto_tela, "incluir_representante_digital_exportacao");
            $vo_checkbox_incluir_porcentagem->build($va_valores, $va_parametros_campo);

            // Quantidade de arquivos em que a exportação será dividida.
            // Só é exibido para XLSX com miniaturas (ver atualizarOpcoesPartesExportacao).
            $va_parametros_campo = [
                "html_number_input",
                "nome" => "numero_partes_exportacao",
                "label" => "Dividir a exportação em quantas partes?",
                "valor_padrao" => "1",
                "tamanho_maximo" => 50
            ];

            $vo_campo_numero_partes = new html_number_input($vs_id_objeto_tela, "numero_partes_exportacao");
            $vo_campo_numero_partes->build($va_valores, $va_parametros_campo);

            ?>
            <div class="form-text mb-3" id="ajuda_numero_partes_exportacao"></div>
            <?php
        }
    ?>

    <div class="text-end">
        <button type="button" class="btn btn-outline-primary" id="btn_exportar">
            Exportar
        </button>
    </div>

</div>

<script>
    function obterNumeroRegistrosExportacao() {
        const elemento = $("#listagem-numero-registros");
        if (!elemento.length) {
            return 0;
        }

        const numero = parseInt((elemento[0].innerText || "").replace(/[^\d]/g, ""), 10);
        return isNaN(numero) ? 0 : numero;
    }

    function atualizarOpcoesPartesExportacao() {
        const formatoXlsx = $("#formato_exportacao").val() === "<?= utils::FORMATO_EXPORTACAO_XLSX ?>";
        const incluirImagens = $("#incluir_representante_digital_exportacao_chk").is(":checked");
        const usarPartes = formatoXlsx && incluirImagens;

        $("#div_numero_partes_exportacao").toggle(usarPartes);
        $("#ajuda_numero_partes_exportacao").toggle(usarPartes);

        if (!usarPartes) {
            $("#numero_partes_exportacao").val(1);
            return;
        }

        const registros = obterNumeroRegistrosExportacao();
        const campoPartes = $("#numero_partes_exportacao");

        // Sugere uma parte a cada 2.000 registros, sem sobrescrever a escolha do usuário
        if (!campoPartes.data("alterado")) {
            campoPartes.val(Math.max(1, Math.ceil(registros / 2000)));
        }

        const partes = Math.max(1, parseInt(campoPartes.val(), 10) || 1);
        const textoRegistros = registros > 0
            ? "Esta exportação tem " + registros + " registros."
            : "";

        $("#ajuda_numero_partes_exportacao").text(
            "Sugestão: até 2.000 registros por parte com miniaturas. " + textoRegistros +
            (partes > 1 ? " Serão gerados " + partes + " arquivos." : "")
        );
    }

    $(document).on("input", "#numero_partes_exportacao", function () {
        $(this).data("alterado", true);
        atualizarOpcoesPartesExportacao();
    });

    $(document).on("change", "#incluir_representante_digital_exportacao_chk", atualizarOpcoesPartesExportacao);

    $(document).on('click', "#btn_exportar", function () {
        const registros = obterNumeroRegistrosExportacao();
        const incluirImagens = $("#incluir_representante_digital_exportacao_chk").is(":checked");
        const partes = Math.max(1, parseInt($("#numero_partes_exportacao").val(), 10) || 1);

        if (incluirImagens && partes === 1 && registros > 5000) {
            if (!confirm("Esta exportação possui " + registros + " registros em um único arquivo com miniaturas. " +
                "O arquivo pode ficar muito grande e demorar para ser gerado. " +
                "Considere dividir a exportação em partes. Deseja continuar?")) {
                return;
            }
        }

        const form = $("#form_lista");
        $.ajax({
            url: 'functions/exportar.php',
            type: "POST",
            data: form.serialize(),
            processData: false,
            success: function (data) {
                $("#modal-exportar").modal("hide");
                startExportProgress(data);
            },
            error: function () {
                alert("Não foi possível iniciar a exportação.");
            }
        });
    });

    if($("#formato_exportacao").val() == "<?= utils::FORMATO_EXPORTACAO_CSV ?>" && $("#div_incluir_representante_digital_exportacao").length > 0)
        $("#div_incluir_representante_digital_exportacao").hide();
    $(document).on('change', "#formato_exportacao", function () {
        if ($(this).val() == "<?= utils::FORMATO_EXPORTACAO_XLSX ?>" && $("#div_incluir_representante_digital_exportacao").length > 0) {
            $("#div_incluir_representante_digital_exportacao").show();
        } else if ($("#div_incluir_representante_digital_exportacao").length > 0) {
            $("#div_incluir_representante_digital_exportacao").hide();
        }

        atualizarOpcoesPartesExportacao();
    });

    atualizarOpcoesPartesExportacao();
</script>