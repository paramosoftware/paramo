<?php

$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php require_once dirname(__FILE__)."/components/sidebar.php"; ?>

<?php require_once dirname(__FILE__) . "/components/modal_progresso.php"; ?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">

    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <form id="form_filtro_relatorio_pesquisas">
        <input type="hidden" name="relatorio" value="pesquisa_usuario">
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">Gerar relatório de atividades de pesquisa do usuário</div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                    </div>
                                    
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary btn-imprimir" type="button" id="btn_imprimir">
                                            Imprimir
                                        </button>
                                    </div>
                                </div>

                                <br>
                                
                                <!-- FORM-->
                                <div class="row no-margin-side" id="filtro">
                                    <div class="col-12">
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
                                            "nome" => "setor_sistema_codigo",
                                            "label" => "Acervo",
                                            "sem_valor" => true,
                                            "objeto" => "setor_sistema",
                                            "atributos" => ["setor_sistema_codigo", "setor_sistema_nome"],
                                            "atributo" => "setor_sistema_codigo"
                                        ];
                                    
                                        $vo_combo = new html_combo_input("setor_sistema", "setor_sistema_codigo");
                                        $vo_combo->build($va_valores, $va_parametros_campo);


                                        $va_parametros_campo = [
                                            "html_checkbox_input",
                                            "nome" => "incluir_porcentagem",
                                            "label" => "Incluir porcentagem?",
                                            "valor_padrao" => "1"
                                        ];

                                        $vo_checkbox = new html_checkbox_input($vs_id_objeto_tela, "incluir_porcentagem");
                                        $vo_checkbox->build($va_valores, $va_parametros_campo);

                                    ?>
                                    

                                    </div>
                                </div>
                                <!-- / FORM-->

                                <br>

                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- /.row-->
            </div>
        </div>
    </form>
</div>
<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<script>
    $(document).on('click', "#btn_imprimir", function () {
        const form = $("#form_filtro_relatorio_pesquisas");
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

</body>
</html>