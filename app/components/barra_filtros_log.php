<div class="filtro-row row" id="div_filtros_catalogacao" 
<?php if (!$vb_exibir_filtro || !$vb_existe_filtro_log)
    print ' style="display:none"'; 
?>
>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <h5>Filtros de logs de indexação</h5>
            </div>
        </div>
    </div>
    <?php
        if (!isset($va_parametros_filtros_form))
            $va_parametros_filtros_form = array();

        $va_parametros_campo = [
            "html_text_input",
            "nome" => "log_data_inicial",
            "label" => "de",
            "formato" => "date",
        ];

        $vo_data = new html_text_input($vs_id_objeto_tela, "catalogacao_data_inicial", "linha");
        $vo_data->build($va_parametros_filtros_form, $va_parametros_campo, "linha");

        $va_parametros_campo = [
            "html_text_input",
            "nome" => "log_data_final",
            "label" => "a",
            "formato" => "date",
        ];

        $vo_data = new html_text_input($vs_id_objeto_tela, "catalogacao_data_final", "linha");
        $vo_data->build($va_parametros_filtros_form, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input", 
            "nome" => "tipo_operacao_log_codigo",
            "label" => "Atividade",
            "objeto" => "tipo_operacao_log",
            "atributos" => ["tipo_operacao_log_codigo", "tipo_operacao_log_nome"],
            "atributo" => "tipo_operacao_log_codigo",
            "sem_valor" => true
        ];
    
        $vo_combo = new html_combo_input($vs_id_objeto_tela, "tipo_operacao_log_codigo");
        $vo_combo->build($va_parametros_filtros_form, $va_parametros_campo);

        $va_parametros_campo = [
            "html_combo_input", 
            "nome" => "log_usuario_codigo", 
            "label" => "Catalogador",
            "objeto" => "usuario",
            "atributos" => ["usuario_codigo", "usuario_nome"],
            "atributo" => "usuario_codigo",
            "sem_valor" => true
        ];

        $vo_combo = new html_combo_input($vs_id_objeto_tela, "log_usuario_codigo");
        $vo_combo->build($va_parametros_filtros_form, $va_parametros_campo);
    ?>
</div>