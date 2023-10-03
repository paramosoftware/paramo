<div class="filtro-row row" id="div_opcoes_relatorios">

    <?php
        $va_valores = array();

        $va_parametros_campo = [
            "html_combo_input", 
            "nome" => "campo_sistema_codigo",
            "label" => "Agrupado por",
            "objeto" => "campo_sistema",
            "atributos" => ["campo_sistema_codigo", "campo_sistema_alias"],
            "sem_valor" => false,
            "filtro" => [
                [
                    "atributo" => "campo_sistema_recurso_sistema_codigo",
                    "valor" => $vn_recurso_sistema_codigo            
                ],
                [
                    "atributo" => "campo_sistema_objeto_chave_estangeira_codigo",
                    "valor" => NULL,
                    "operador" => "NOT"            
                ],
                [
                    "atributo" => "campo_sistema_exibir_lista_agrupadores",
                    "valor" => 1
                ]
            ]
        ];
    
        $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "campo_sistema_codigo");
        $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);

        $vb_relatorios_disponiveis = true;
        if (!count($vo_combo_paginas_etiquetas->get_itens()))
        {
            print '<div class="pl-2">Relatórios quantificadores não configurados.</div>';
            $vb_relatorios_disponiveis = false;
        }

        if ($vb_relatorios_disponiveis)
        {
            $va_parametros_campo = [
                "html_combo_input", 
                "nome" => "ordenacao_relatorio", 
                "label" => "Ordenar por", 
                "sem_valor" => false,
                "valores" => [
                    "1" => "Nome/Denominação",
                    "2" => "Quantidade"
                ]
            ];

            $vo_combo_paginas_etiquetas = new html_combo_input($vs_id_objeto_tela, "ordenacao_relatorio");
            $vo_combo_paginas_etiquetas->build($va_valores, $va_parametros_campo);
        ?>

            <div class="text-end">
                <button type="button" class="btn btn-outline-primary" id="btn_imprimir_relatorio">
                    Imprimir
                </button>
            </div>
        <?php
        }
        ?>
</div>

<script>
    $(document).on('click', "#btn_imprimir_relatorio", function()
    {
        $("#form_lista").attr('action', 'relatorio.php');
        $("#form_lista").attr('method', 'post');
        $("#form_lista").attr('target', '_blank');
        $("#form_lista").submit();

        $("#form_lista").attr('action', 'listar.php');
        $("#form_lista").attr('method', 'get');
        $("#form_lista").attr('target', '');
    });
</script>