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
    if (!$vb_pode_editar && !$vb_pode_ler)
        exit();

    if (isset($_GET['obj']))
        $vs_id_objeto_tela = $_GET['obj'];
    else
    {
        print "Não é possível carregar a listagem. (objeto)";
        exit();
    }

    $vn_objeto_codigo = "";
    if (isset($_GET['cod']))
        $vn_objeto_codigo = $_GET['cod'];
    else
    {
        print "Não é possível ler objeto sem codigo.";
        exit();
    }

    $vn_pagina_atual = 1;
    $vs_modo = "ficha";

    if (isset($_GET['visualizacao_codigo']))
        $vs_visualizacao = $_GET['visualizacao_codigo'];
    else
        $vs_visualizacao = "ficha";

    $va_registros_filhos = array();

    $vn_ordenacao = "";
    $vs_ordem = "";
    define("NUMERO_ITENS_PAGINA_LISTAGEM", 20);

    if ($vn_objeto_codigo)
    {
        //require dirname(__FILE__)."/functions/montar_listagem.php";

        if (!isset($_SESSION["instituicao_visualizar_como"]))
            require_once "components/sidebar.php";
    }
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">

    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <form method="get" action="ficha.php" id="form_lista"> 
        <input type="hidden" name="modo" id="modo" value="ficha">
        <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
        <input type="hidden" name="cod" id="cod" value="<?php print $vn_objeto_codigo; ?>">

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="filter-documents col-md-9">
                                        <?php if ($vb_pode_inserir)
                                        {
                                        ?>
                                            <button class="btn btn-primary px-4" type="button" id="btn_novo"><?php print $vs_nome_botao_novo; ?></button>
                                        <?php
                                        }
                                        ?>
                                            
                                        <div class="btn-group me-2 espacamento-esquerda-10" role="group" aria-label="First group">
                                            <?php if ($vb_pode_editar)
                                            {
                                            ?>
                                                <button class="btn btn-outline-primary" type="button" id="btn_editar">Editar</button>
                                            <?php
                                            }
                                            ?>
                                            
                                            <?php if (!$vb_usuario_externo)
                                            {
                                            ?>
                                                <button class="btn btn-outline-primary btn-modal" type="button" data-button-id="imprimir">Imprimir</button>
                                                <button class="btn btn-outline-primary" type="button" id="btn_exportar">Exportar</button>
                                            <?php
                                            }
                                            ?>

                                            <button class="btn btn-outline-primary btn_fechar_imagem" type="button" id="btn_fechar_imagem" style="display:none">Fechar imagem</button>
                                            <?php if ($vb_pode_excluir) { ?>
                                                <button class="btn btn-outline-primary" type="button" id="btn_excluir">Excluir</button>
                                            <?php } ?>
                                            <button class="btn btn-outline-primary" type="button" id="btn_voltar_lista" onClick="history.back(-1);">Voltar</button>
                                        </div>                           
                                    </div>
                                </div>

                                <script>
                                    $(document).on('click', "#btn_editar", function()
                                    {
                                        vs_url_editar = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>";

                                        <?php if ($vn_bibliografia_codigo)
                                        {
                                        ?>
                                        
                                        vs_url_editar = vs_url_editar + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"
                                        
                                        <?php
                                        }
                                        ?>

                                        window.location.href= vs_url_editar;
                                    });

                                    $(document).on('click', "#btn_novo", function()
                                    {
                                        vs_url_editar = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>";

                                        <?php if ($vn_bibliografia_codigo)
                                        {
                                        ?>
                                        
                                        vs_url_editar = vs_url_editar + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"
                                        
                                        <?php
                                        }
                                        ?>

                                        window.location.href= vs_url_editar;
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

                                    $(document).on('click', "#btn_excluir", function()
                                    {
                                        window.location.href = "confirmar_exclusao.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>";
                                    });
                                </script>

                                <?php
                                    $vb_primeiro_carregamento = true;
                                    require dirname(__FILE__)."/components/listagem.php";
                                ?>

                                <?php
                                $va_registros_filhos = $vo_objeto->get_registros_filhos();

                                foreach($va_registros_filhos as $vs_key_registro_filho => $vs_campo_relacionamento_registro_filho)
                                {                                    
                                    if (isset($vs_campo_relacionamento_registro_filho["exibir_ficha_pai"]) && $vs_campo_relacionamento_registro_filho["exibir_ficha_pai"])
                                    {
                                        $vs_id_objeto_tela = $vs_key_registro_filho;
                                        $vs_modo = "listagem";
                                        $vs_visualizacao = "navegacao";
                                        $vn_pagina_atual = 1;

                                        $va_parametros_filtros_consulta = array();
                                        $va_parametros_filtros_consulta[$vs_campo_relacionamento_registro_filho["atributo_relacionamento"]] = $vn_objeto_codigo;
                                    
                                        if ($vb_pode_editar)
                                        {
                                        ?>
                                            <div class="row">
                                                <div class="filter-documents col-md-9">
                                                    <?php if ($vb_pode_editar)
                                                    {
                                                    ?>
                                                        <!--
                                                        <button class="btn btn-primary px-4" type="button" id="btn_novo_<?php print $vs_key_registro_filho ?>">Criar <?php print $vs_key_registro_filho ?></button>
                                                        -->
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).on('click', "#btn_novo_<?php print $vs_key_registro_filho ?>", function()
                                                {
                                                    vs_url_editar = "editar.php?obj=<?php print $vs_key_registro_filho; ?>&<?php print $vs_campo_relacionamento_registro_filho; ?>=<?php print $vn_objeto_codigo; ?>";

                                                    <?php if ($vn_bibliografia_codigo)
                                                    {
                                                    ?>

                                                    vs_url_editar = vs_url_editar + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"

                                                    <?php
                                                    }
                                                    ?>

                                                    window.location.href= vs_url_editar;
                                                });
                                            </script>
                                        <?php
                                        }

                                        require dirname(__FILE__)."/components/listagem.php";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="container-lg" id="div-image-container">
            </div>
        </div>

        <div id="modal-imprimir" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group" role="group" aria-label="First group">
                            <button class="btn btn-tab-rel btn-outline-primary active" id="tab_lista" type="button">Ficha</button>
                        </div>
                        <button class="btn-close" data-modal-id="imprimir"></button>
                    </div>

                    <div class="modal-body">
                        <div class="tab" id="div_tab_lista">
                            <?php require_once dirname(__FILE__)."/components/barra_opcoes_lista.php"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<script>

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

$(document).on('click', ".botao_adicionar", function()
{
    vn_item_codigo = parseInt($(this).attr('id').replace('btn_adicionar_', ''));
    vn_lista_codigo = $("#selecao").val();

    $.post('functions/adicionar_item_acervo_selecao.php', {item_codigo: vn_item_codigo, selecao_codigo: vn_lista_codigo}, function(response)
    { 
        if (response.trim() == '')
            alert("Item de acervo adicionado com sucesso!");
        else
            alert(response);
    });
});

</script>

</body>
</html>