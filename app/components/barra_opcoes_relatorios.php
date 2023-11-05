<div class="btn-group nav-tabs nav mb-3" role="group">
    <button class="btn btn-tab-relatorios btn-outline-primary active" id="tab_quantitativo" type="button">
        Quantificadores
    </button>
    <button class="btn btn-tab-relatorios btn-outline-primary" id="tab_estatisticas_catalogacao" type="button">
        Logs de indexação
    </button>
</div>

<div class="tab-content">
    <div id="div_tab_quantitativo">
        <?php require_once dirname(__FILE__) . "/barra_opcoes_relatorios_quantitativo.php"; ?>
    </div>

    <div id="div_tab_estatisticas_catalogacao" style="display: none;">
        <?php require_once dirname(__FILE__) . "/barra_opcoes_relatorios_catalogacao.php"; ?>
    </div>
</div>

<script>
    $(document).on('click', ".btn-tab-relatorios", function()
    {
        $(".btn-tab-relatorios").removeClass("active");
        $(this).addClass("active");

        $(".tab-content > div").hide();
        $("#div_" + $(this).attr("id")).show();
    });
</script>