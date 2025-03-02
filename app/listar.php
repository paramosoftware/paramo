<?php

    $vb_montar_menu = true;
    require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>
<?php require_once dirname(__FILE__) . "/components/modal_progresso.php"; ?>

<body>

<?php
    if (!$vb_pode_inserir && !$vb_pode_editar && !$vb_pode_ler)
        exit();

    if (!$vs_id_objeto_tela)
    {
        print "Não é possível carregar a listagem. (objeto)";
        exit();
    }

    $vn_pagina_atual = 1;
    if (isset($_GET['campo_paginacao']))
    {
        $vs_campo_paginacao = $_GET['campo_paginacao'];

        if (isset($_GET[$vs_campo_paginacao]))
            $vn_pagina_atual = $_GET[$vs_campo_paginacao];
    }

    if (isset($_GET['visualizacao_codigo']))
        $vs_visualizacao = $_GET['visualizacao_codigo'];
    else
        $vs_visualizacao = "navegacao";

    $vb_back_from_editing = false;
    if (isset($_GET['back']) && $_GET['back'])
        $vb_back_from_editing = true;
        
    require_once dirname(__FILE__)."/functions/montar_filtros_busca.php";

    if ($vb_aplicar_controle_acesso)
        require_once "components/sidebar.php";
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">

    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <?php 
        $vs_form_action = "listar.php";
    ?>
    
    <form method="get" action="listar.php" id="form_lista">
        <input type="hidden" name="modo" id="modo" value="listagem">
        <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
        
        <input type="hidden" name="campo_paginacao" id="campo_paginacao" value="">

        <?php if (isset($va_instituicao_visualizar_como_parametros) && $va_instituicao_visualizar_como_parametros)
        {
            foreach ($va_instituicao_visualizar_como_parametros as $vs_parametro => $vs_valor)
            {
                print '<input type="hidden" name="'.$vs_parametro.'" id="'.$vs_parametro.'" value="'.$vs_valor.'">';
            }
        }
        ?>



        <?php if (isset($vn_bibliografia_codigo) && $vn_bibliografia_codigo)
        {
        ?>
            <input type="hidden" name="bibliografia" id="bibliografia" value="<?php print $vn_bibliografia_codigo; ?>">
        <?php
        }
        ?>

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header"><?php print htmlspecialchars($vs_recurso_sistema_nome_plural); ?></div>

                            <div class="card-body">
                                <?php
                                    $vb_acesso_invalido_cadastro = false;

                                    foreach ($vo_objeto->controlador_acesso as $vs_parametro_controlador => $vs_atributo_controlador)
                                    {
                                        if ((trim($va_parametros_controle_acesso[$vs_parametro_controlador]) == "")  && ($vo_objeto->get_chave_primaria()[0] != $vs_parametro_controlador))
                                        {
                                            //if (!isset($va_parametros_controle_acesso["_combinacao_"]) || (isset($va_parametros_controle_acesso["_combinacao_"]) && $va_parametros_controle_acesso["_combinacao_"] != "OR") )
                                            //    $a = 1;
                                            //else 
                                                $vb_acesso_invalido_cadastro = true;
                                        }
                                        elseif (isset($va_parametros_controle_acesso["_combinacao_"]) && $va_parametros_controle_acesso["_combinacao_"] == "OR")
                                        {
                                            $vb_acesso_invalido_cadastro = false;
                                            break;
                                        }
                                    }
                                    
                                    if ($vb_acesso_invalido_cadastro && !isset($_SESSION["instituicao_visualizar_como"]))
                                    {
                                        print '<div class="alert alert-danger">';
                                        print 'Não é possível criar um novo cadastro: não há acervo cadastrado para este setor ou o usuário não tem permissões de acesso.';
                                        print '</div>';
                                        $vb_pode_inserir = false;
                                    }
                                ?>
                                <div class="row">
                                    <div class="filter-documents col-md-8">
                                        <?php if ($vb_pode_inserir)
                                        {
                                        ?>
                                            <button class="btn btn-primary px-4" type="button" id="btn_novo"><?php print $vs_nome_botao_novo; ?></button>
                                        <?php
                                        }
                                        ?>

                                        <div class="btn-group me-2 espacamento-esquerda-10 flex-wrap mt-4 mt-sm-0" role="group" aria-label="First group">
                                            <?php if (!$vb_usuario_externo) : ?>
                                                <button class="btn btn-outline-primary btn-modal" type="button" data-button-id="imprimir">Imprimir</button>

                                                <button class="btn btn-outline-primary" type="button" id="btn_exportar">Exportar</button>
                                            <?php endif; ?>

                                            <?php
                                                $va_objetos_upload = config::get(["upload_lote_permitido"]) ?? [];
                                                
                                                if (in_array($vs_id_objeto_tela, $va_objetos_upload) && $vb_pode_editar)
                                                {
                                                ?>
                                                    <button class="btn btn-outline-primary" id="btn_upload" type="button">Upload</button>
                                                <?php
                                                }
                                            ?>

                                            <?php if ($vb_usuario_administrador && false)
                                            {
                                            ?>
                                                <button class="btn btn-outline-primary" type="button" id="btn_importar">Importar</button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-4 text-right">
                                        <button class="btn btn-primary dropdown-toggle filtros" type="button" id="btn_filtro" onclick="toggle_filtro()">
                                            Busca básica
                                        </button>

                                        <button class="btn btn-primary dropdown-toggle" type="button" id="btn_filtro_combinado" onclick="toggle_filtro_combinado()">
                                            Busca avançada
                                        </button>
                                    </div>
                                </div>

                                <div id="modal-imprimir" class="modal fade" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="btn-group" role="group" aria-label="First group">
                                                    <button class="btn btn-tab-rel btn-outline-primary active" id="tab_lista" type="button">Lista</button>
                                                    <button class="btn btn-tab-rel btn-outline-primary" id="tab_relatorios" type="button">Relatórios</button>

                                                    <?php if (config::get(["f_geracao_etiquetas"]) ?? false)
                                                    {
                                                    ?>
                                                        <button class="btn btn-tab-rel btn-outline-primary" id="tab_etiquetas" type="button">Etiquetas</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>

                                                <button class="btn-close" data-modal-id="imprimir"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="tab" id="div_tab_lista">
                                                    <?php require_once dirname(__FILE__)."/components/barra_opcoes_lista.php"; ?>
                                                </div>

                                                <div class="tab" id="div_tab_relatorios" style="display:none">
                                                    <?php require_once dirname(__FILE__)."/components/barra_opcoes_relatorios.php"; ?>
                                                </div>

                                                <div class="tab" id="div_tab_etiquetas" style="display:none">
                                                    <?php require_once dirname(__FILE__)."/components/barra_paginas_etiquetas.php"; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- FILTRO-->

                                <?php require_once dirname(__FILE__)."/components/barra_filtros_navegacao.php"; ?>

                                <?php require_once dirname(__FILE__)."/components/barra_filtros_combinados.php"; ?>
                                
                                <!-- / FILTRO-->

                                <?php require_once dirname(__FILE__)."/components/barra_selecao.php"; ?>

                                <?php
                                    $vb_primeiro_carregamento = true;

                                    require_once dirname(__FILE__)."/components/listagem.php";
                                ?>

                                <div class="row footer-documentos">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<script>

$(document).on('click', ".btn-tab-rel", function() {
    $('.tab').hide();
    $('#div_'+$(this).attr('id')).show();
    
    $('.btn-tab-rel').removeClass("active");
    $(this).addClass("active");
});

$(document).on('click', ".btn-modal", function () {
    const modalId = $(this).data('button-id');
    $("#modal-" + modalId).modal('show');
    return false;
});

$(document).on('click', ".btn-close", function () {
    const modalId = $(this).data('modal-id');
    $("#modal-" + modalId).modal('hide');
    return false;
});

$(document).on('click', "#btn_novo", function () {
    vs_url_editar = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>";

    <?php if ($vn_bibliografia_codigo)
    {
    ?>

    vs_url_editar = vs_url_editar + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"

    <?php
    }
    ?>

    window.location.href = vs_url_editar;
});

$(document).on('click', "#btn_atualizar", function () {
    $("#form_lista").submit();
});

$(document).on('click', "#btn_exportar", function()
{
    const form = $("#form_lista");
    $.ajax({
        url: 'functions/exportar.php',
        type: "POST",
        data: form.serialize(),
        processData: false,
        success: function (data) {
            getProgress(data, true);
        }
    });
});

$(document).on('click', "#btn_upload", function()
{
    window.location.href = "upload_lote.php?obj=<?php print $vs_id_objeto_tela; ?>";
});

$(document).on('click', "#btn_importar", function()
{
    window.location.href = "importar.php?obj=<?php print $vs_id_objeto_tela; ?>";
});


</script>

</body>
</html>