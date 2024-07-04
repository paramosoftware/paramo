<?php
    if (!defined('NUMERO_ITENS_PAGINA_LISTAGEM'))
        define("NUMERO_ITENS_PAGINA_LISTAGEM", 20);

    if (!isset($vb_primeiro_carregamento))
        $vb_primeiro_carregamento = false;

    if (!isset($vs_target_ui))
        $vs_target_ui = "";

    $vb_expandir_niveis_hierarquicos = $_GET["expandir"] ?? false;

    require dirname(__FILE__)."/../functions/montar_listagem.php";
?>

<?php
    if ($vb_primeiro_carregamento && $vn_numero_registros)
    {
    ?>
        <div class="filtro-row row" style="margin-top:10px" id="div_barra_paginacao_ordenacao_superior">
            <?php 
                require_once dirname(__FILE__)."/barra_ordenacao.php";
            ?>

            <?php
            if ($vn_numero_registros > NUMERO_ITENS_PAGINA_LISTAGEM)
            {
                $vs_tela = $vs_id_objeto_tela;
                $pa_parametros_campo["nome"] = "paginacao_topo";
                $pa_parametros_campo["numero_registros"] = $vn_numero_registros;
                
                require_once dirname(__FILE__) . "/barra_paginacao.php";
            }
            ?>
        </div>

        <?php 
            require_once dirname(__FILE__) ."/barra_visualizacoes.php";
        ?>

        <?php if ($vo_objeto->get_campo_hierarquico())
        {
        ?>
            <div class="mt-2 mb-2" style="margin-left:5px">
                <input type="checkbox" id="chk_exibir_niveis" name="expandir" onclick="document.getElementById('form_lista').submit();"
                <?php if ($vb_expandir_niveis_hierarquicos) print " checked"; ?>
                > Exibir todos os níveis expandidos
            </div>
        <?php
        }
        ?>

        <div style="overflow:auto; margin-bottom:20px">
            <div style="float:left; padding-top:10px; margin-left:5px" id="listagem-numero-registros">
            <?php
                print ($vn_numero_registros + $vn_numero_registros_filhos) . " registros encontrados<br>";
            ?>
            </div>

            <?php if ( ($vb_pode_editar_lote || $vb_pode_excluir_lote) && config::get(["f_operacoes_lote"]) && !isset($_SESSION["instituicao_visualizar_como"]))
            {
            ?>
            <div style="float:right">
                <?php if ($vb_pode_editar_lote)
                {
                ?>
                    <button class="btn btn-outline-primary" type="button" id="btn_editar_listagem">
                        <?php print "Editar em lote os " . $vn_numero_registros . " registros"; ?>
                    </button>
                <?php
                }
                ?>

                <?php if ($vb_pode_excluir_lote)
                {
                ?>
                    <button class="btn btn-outline-primary" type="button" id="btn_excluir_listagem">
                        <?php print "Excluir todos os " . $vn_numero_registros . " registros"; ?>
                    </button>
                <?php
                }
                ?>
            </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    elseif (!$vn_numero_registros)  
    {
    ?>
        <div class="row filtro no-margin-side hidden" id="filtro">
            <div style="margin-left:10px">Nenhum item encontrado.</div>
        </div>
    <?php
    }
?>

<?php
if ($vn_numero_registros)
{
?>
<div id="div_listagem_<?php print $vs_id_objeto_tela; ?>" style="clear:right">

<?php
    $vn_pagina_atual = $vn_pagina_atual ?? $vn_numero_registros;
    $contador = ($vn_pagina_atual -1)*20 + 1;
    
    foreach($va_itens_listagem as $va_item_listagem)
    {
        $vn_objeto_codigo = $va_item_listagem[$vo_objeto->get_chave_primaria()[0]];
    ?>
        <div class="card mb-3" style="margin-left:<?php print 30*($va_item_listagem["_nivel"]); ?>px">
            <div class="card-header row no-margin-side card-titulo" id="div_<?php print reset($va_item_listagem) ?>">

                <div
                <?php if ($vs_target_ui != "modal")
                    print ' class="col-sm-6"';
                else
                    print ' style="width:100%"';
                ?>
                >
                    <h5 style="display: inline-flex;">
                        <?php
                        if (isset($va_item_listagem["_number_of_children"]))
                        {
                        ?>
                            <button class="btn btn-transparent p-0" id="btn_show_chidren_<?php print $vn_objeto_codigo; ?>" type="button" onclick="show_child_records(<?php print $vn_objeto_codigo; ?>)"
                            <?php if ($vb_expandir_niveis_hierarquicos) print ' style="display:none"'; ?>
                            >
                            <svg class="icon text-cor-laranja">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-plus"></use>
                            </svg>
                            </button>

                            <button class="btn btn-transparent p-0"  id="btn_hide_chidren_<?php print $vn_objeto_codigo; ?>" type="button" onclick="hide_child_records(<?php print $vn_objeto_codigo; ?>)"
                            <?php print ' style="display:none"'; ?>
                            >
                            <svg class="icon text-cor-laranja">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-minus"></use>
                            </svg>
                            </button>
                        <?php
                        }
                        ?>

                        <?php
                            $vs_url_editar = "ficha.php?obj=" . $vs_id_objeto_tela . "&cod=" . $vn_objeto_codigo;

                            if ( ($vb_pode_editar || config::get(["f_acesso_leitura_form_cadastro"])) && ($vs_target_ui != "modal"))
                            {
                                $vs_url_editar = "editar.php?obj=" . $vs_id_objeto_tela . "&cod=" . $vn_objeto_codigo;

                                if ($vn_bibliografia_codigo)
                                    $vs_url_editar .= "&bibliografia=" . $vn_bibliografia_codigo;
                            }
                        ?>

                        <a class="main_field_listagem botao_editar"
                        <?php if ($vs_target_ui != "modal")
                        {
                        ?>
                            href="<?php print htmlspecialchars($vs_url_editar); ?>"
                        <?php
                        }
                        else
                        {
                        ?>
                            onClick="adicionar_<?php print $vs_campo_modal ?>('<?php print $vn_objeto_codigo; ?>', '<?php print $va_item_listagem["main_field"]; ?>'); return false;"

                        <?php 
                        }
                        ?>
                        >
                            <?php
                                if (isset($va_item_listagem["main_field"]))
                                    print $va_item_listagem["main_field"];
                            ?>
                        </a>
                    </h5>
                </div>

                <?php
                if ($vs_target_ui != "modal")
                {
                ?>
                    <div class="col-sm-6 text-right">
                        <div class="documento-nome-titulo">
                            <span class="texto-nome-titulo">
                                <?php
                                    if(isset($va_item_listagem["id_field"]))
                                        print $va_item_listagem["id_field"];
                                ?>
                            </span>
                        </div>

                        <div style="display: inline-flex;">
                            <button class="btn btn-transparent p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon text-cor-laranja">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-options"></use>
                            </svg>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end">
                                <?php
                                if ($vs_modo != "ficha")
                                {
                                    $vs_url_ficha = "ficha.php?obj=" . $vs_id_objeto_tela . "&cod=" . $vn_objeto_codigo;
                                    if ($vn_bibliografia_codigo)
                                        $vs_url_ficha .= "&bibliografia=" . $vn_bibliografia_codigo;
                                ?>
                                    <a class="dropdown-item botao_ficha" id="btn_ficha_<?php print $vn_objeto_codigo; ?>" href="<?php print htmlspecialchars($vs_url_ficha); ?>">Ficha</a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($vb_pode_editar)
                                {
                                ?>
                                    <a class="dropdown-item botao_editar" id="btn_editar_<?php print $vn_objeto_codigo; ?>" href="<?php print htmlspecialchars($vs_url_editar); ?>">Editar</a>
                                <?php
                                }
                                ?>

                                <?php if ($vb_pode_substituir && !in_array($vn_objeto_codigo, $vo_objeto->registros_protegidos))
                                {
                                ?>
                                    <a class="dropdown-item botao_substituir" id="btn_substituir_<?php print $vn_objeto_codigo; ?> href="#">Substituir</a>
                                <?php
                                }
                                ?>

                                <?php if ($vb_pode_excluir && !in_array($vn_objeto_codigo, $vo_objeto->registros_protegidos))
                                {
                                ?>
                                    <a class="dropdown-item botao_excluir" id="btn_excluir_<?php print $vn_objeto_codigo; ?>" href="confirmar_exclusao.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>">Excluir</a>
                                <?php
                                }
                                ?>

                                <?php if (false)
                                {
                                ?>
                                    <a class="dropdown-item botao_adicionar" id="btn_adicionar_<?php print $vn_objeto_codigo; ?>" href="#">Adicionar</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                        $vn_selected = false;

                        if (isset($va_itens_selecao))
                        {
                            $va_itens_codigo = array_column($va_itens_selecao, $vo_objeto->get_chave_primaria()[0]);
                            $vn_selected = in_array($vn_objeto_codigo, $va_itens_codigo);
                        }

                    ?>

                    <input type="checkbox" class="check-selecao" id="chk_selecao_<?php print $vn_objeto_codigo; ?>"
                    <?php if ($vn_selected)
                        print " checked";
                    ?>
                    <?php if (!isset($vn_selecao) || !$vn_selecao)
                        print ' style="display:none"';
                    ?>
                    >
                </div><?php
                }
                ?>
            </div>
            
            <?php  
            if (isset($va_item_listagem["descriptive_field"]) || count($va_item_listagem["atributos"]) || ($va_item_listagem["representante_digital"]))
            {
            ?>
                <div class="card-body infos-docs">
                    <p class="card-text texto-card">
                        <?php 
                        if (isset($va_item_listagem["descriptive_field"]))
                            print $va_item_listagem["descriptive_field"];
                        ?>
                    </p>

                    <div class="row">
                        <?php 
                        if ($va_item_listagem["representante_digital"])
                        {
                        ?>
                            <div class="card-content-img col-sm-3">
                                <?php

                                $vs_image = $va_item_listagem["representante_digital"];

                                if (strpos($vs_image, "https") !== false)
                                {
                                    echo utils::get_embedded_media($vs_image, 215);
                                }
                                else
                                {
                                    echo utils::get_img_html_element($vs_image, "thumb");
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>

                        <div 
                        <?php 
                            if ($va_item_listagem["representante_digital"])
                                print ' class="col-sm-9"';
                            else
                                print ' class="col-sm-12"';
                        ?>
                        >
                            <?php
                                foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
                                {
                                    if ($va_atributos_item_listagem["valor"] && $va_atributos_item_listagem["exibir"])
                                    {
                                    ?>
                                        <div class="card-body row linha-documento-top">
                                            <div class="col-sm-3">
                                                <div class="fw-semibold"><?php print $va_atributos_item_listagem["label"]; ?>:</div>
                                            </div>

                                            <div class="col">
                                                <div class="text-left text-medium-emphasis medium"><?php print $va_atributos_item_listagem["valor"]; ?></div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                            ?>
                        </div>
                         
                        <?php 
                        if ($vs_modo == "ficha")
                        {
                        ?>
                            <br>

                            <div class="col-sm-12" style="margin-top:20px">
                                <?php    
                                    $va_parametros_campo = [
                                        "html_representantes_digitais_input", 
                                        "nome" => "representante_digital_codigo", 
                                        "label" => "Representantes digitais", 
                                        "preview_only" => true,
                                        "tipo" => 1
                                    ];
                                    
                                    $vo_campo = new html_representantes_digitais_input($vs_id_objeto_tela, "representante_digital_codigo");
                                    $vo_campo->build($va_item, $va_parametros_campo);
                                ?>
                            </div>
                        <?php
                            }
                        ?>

                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div style="margin-left:30px" id="children_<?php print $vn_objeto_codigo; ?>"></div>
    <?php
    }
?>

<?php
}
?>

<script>

$(document).ready(function()
{
    <?php if (isset($vb_back_from_editing) && $vb_back_from_editing)
    {
    ?>
        $([document.documentElement, document.body]).animate({
            scrollTop: sessionStorage.getItem("<?php print $vs_id_objeto_tela; ?>_scroll") - 150
        }, 300);
    <?php
    }
    else
    {
    ?>
        sessionStorage.removeItem("<?php print $vs_id_objeto_tela; ?>_scroll");
    <?php
    }
    ?>
});

$(document).on('click', ".botao_editar", function()
{
    sessionStorage.setItem("<?php print $vs_id_objeto_tela; ?>_scroll", $(this).offset().top);
});

$(document).on('click', ".botao_substituir", function()
{
    event.preventDefault();
    vn_item_codigo = parseInt($(this).attr('id').replace('btn_substituir_', ''));
    window.open("substituir.php?obj=<?php print $vs_id_objeto_tela; ?>&cod="+vn_item_codigo, "_blank");
});

$(document).on('click', ".botao_ficha", function()
{
    event.preventDefault();
    vn_item_codigo = parseInt($(this).attr('id').replace('btn_ficha_', ''));
    window.location.href = "ficha.php?obj=<?php print $vs_id_objeto_tela; ?>&cod="+vn_item_codigo;
});

$(document).on('click', ".botao_adicionar", function()
{
    event.preventDefault();
    vn_item_codigo = parseInt($(this).attr('id').replace('btn_adicionar_', ''));
    vn_lista_codigo = $("#selecao").val();

    $.post('functions/adicionar_item_acervo_selecao.php', {item_codigo: vn_item_codigo, selecao_codigo: vn_lista_codigo}, function(response)
    { 
        if (response.trim() == '')
            alert("Documento adicionado com sucesso!");
        else
            alert(response);
    });
});

$(document).on('click', "#btn_editar_listagem", function()
{
    $("#modo").val('lote');
    $("#form_lista").attr('action', 'editar.php');
    $("#form_lista").attr('method', 'post');
    $("#form_lista").submit();
});

$(document).on('click', "#btn_excluir_listagem", function()
{
    //if (confirm('Tem certeza de que deseja excluir definitivamente este(s) registro(s)?'))
    {
        $("#modo").val('lote');
        $("#form_lista").attr('action', 'confirmar_exclusao.php');
        $("#form_lista").attr('method', 'post');
        $("#form_lista").submit();
    }
});

function show_child_records(pn_item_codigo)
{
    var vs_url_child_records = "functions/ler_registros_filhos.php?obj=<?php print $vs_id_objeto_tela; ?>&cod="+pn_item_codigo;

    $.get(vs_url_child_records, function(data, status)
    {
        $("#children_" + pn_item_codigo).html(data);
        
        $("#btn_show_chidren_" + pn_item_codigo).hide();
        $("#btn_hide_chidren_" + pn_item_codigo).show();
    });
}

function hide_child_records(pn_item_codigo)
{
    $("#children_" + pn_item_codigo).empty();
    
    $("#btn_show_chidren_" + pn_item_codigo).show();
    $("#btn_hide_chidren_" + pn_item_codigo).hide();
}

</script>

</div>

<?php
if ($vb_primeiro_carregamento && $vn_numero_registros)
{
?>
<div class="filtro-row row" id="div_barra_paginacao_inferior">
    <div class="filtro-order col-md-6">&nbsp;</div>

    <?php
    if ($vn_numero_registros > NUMERO_ITENS_PAGINA_LISTAGEM)
    {
        $vs_tela = $vs_id_objeto_tela;
        $pa_parametros_campo["nome"] = "paginacao_bottom";
        $pa_parametros_campo["numero_registros"] = $vn_numero_registros;
        
        require dirname(__FILE__) . "/barra_paginacao.php";
    }
    ?>
</div>
<?php
}
?>