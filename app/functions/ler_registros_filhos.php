<?php
    require_once dirname(__FILE__) . "/autenticar_usuario.php";
    require_once dirname(__FILE__) . "/../components/ler_valor.php";
    
    if (count($_POST))
        $va_parametros = $_POST;
    else
        $va_parametros = $_GET;

    if (!isset($va_parametros['obj']))
    {
        print "erro";
        exit();
    }
    else
        $vs_id_objeto = $va_parametros['obj'];

    if (!isset($va_parametros['cod']))
    {
        print "erro";
        exit();
    }
    else
        $vn_objeto_codigo = $va_parametros['cod'];

    $vo_objeto = new $vs_id_objeto;
    $va_registros_filhos = $vo_objeto->ler_lista([$vo_objeto->get_campo_hierarquico() => $vn_objeto_codigo], "navegacao", null, null, null, null, null, 1, false);

    $va_visualizacao_lista = $vo_objeto->get_visualizacao("navegacao");
    
    if (isset($va_visualizacao_lista["ordem_campos"]))
        $va_campos_visualizacao = $va_visualizacao_lista["ordem_campos"];
    else
        $va_campos_visualizacao = array_keys($va_visualizacao_lista["campos"]);

    foreach($va_campos_visualizacao as $vs_key_campo_visualizacao => $vs_label_campo_visualizacao)
    {
        if (isset($vs_label_campo_visualizacao["id_field"]))
            $vs_id_field = $vs_key_campo_visualizacao;

        elseif (isset($vs_label_campo_visualizacao["main_field"]))
            $vs_main_field = $vs_key_campo_visualizacao;
    }

    foreach ($va_registros_filhos as $va_item_listagem)
    {
        $vn_objeto_codigo = $va_item_listagem[$vo_objeto->get_chave_primaria()[0]];
    ?>
        <div class="card mb-3">
            <div class="card-header row no-margin-side card-titulo" id="div_<?php print reset($va_item_listagem) ?>">

                <div class="col-sm-6">
                    <h5 style="display: inline-flex;">
                        <?php
                        if (isset($va_item_listagem["_number_of_children"]) && ($va_item_listagem["_number_of_children"] > 0))
                        {
                        ?>
                            <button class="btn btn-transparent p-0" id="btn_show_chidren_<?php print $vn_objeto_codigo; ?>" type="button" onclick="show_child_records(<?php print $vn_objeto_codigo; ?>)">
                            <svg class="icon text-cor-laranja">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-plus"></use>
                            </svg>
                            </button>

                            <button class="btn btn-transparent p-0" style="display:none" id="btn_hide_chidren_<?php print $vn_objeto_codigo; ?>" type="button" onclick="hide_child_records(<?php print $vn_objeto_codigo; ?>)">
                            <svg class="icon text-cor-laranja">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-minus"></use>
                            </svg>
                            </button>
                        <?php
                        }
                        ?>

                        <?php
                            $vs_url_editar = "ficha.php?obj=" . $vs_id_objeto . "&cod=" . $vn_objeto_codigo;

                            if ($vb_pode_editar)
                            {
                                $vs_url_editar = "editar.php?obj=" . $vs_id_objeto . "&cod=" . $vn_objeto_codigo;

                                if ($vn_bibliografia_codigo)
                                    $vs_url_editar .= "&bibliografia=" . $vn_bibliografia_codigo;
                            }
                        ?>

                        <a class="main_field_listagem botao_editar" href="<?php print htmlspecialchars($vs_url_editar); ?>">
                            <?php
                                print ler_valor1($vs_main_field, $va_item_listagem);
                            ?>
                        </a>
                    </h5>
                </div>
                
                <div class="col-sm-6 text-right">
                    <div class="documento-nome-titulo">
                        <span class="texto-nome-titulo">
                            <?php
                                if (isset($vs_id_field))
                                    print ler_valor1($vs_id_field, $va_item_listagem);
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
                                $vs_url_ficha = "ficha.php?obj=" . $vs_id_objeto . "&cod=" . $vn_objeto_codigo;

                                if ($vn_bibliografia_codigo)
                                    $vs_url_ficha .= "&bibliografia=" . $vn_bibliografia_codigo;
                            ?>
                                
                            <a class="dropdown-item botao_ficha" id="btn_ficha_<?php print $vn_objeto_codigo; ?>" href="<?php print htmlspecialchars($vs_url_ficha); ?>">Ficha</a>

                            <?php
                            if ($vb_pode_editar)
                            {
                            ?>
                                <a class="dropdown-item botao_editar" id="btn_editar_<?php print $vn_objeto_codigo; ?>" href="<?php print htmlspecialchars($vs_url_editar); ?>">Editar</a>
                            <?php
                            }
                            ?>

                            <?php if ($vb_pode_substituir)
                            {
                            ?>
                                <a class="dropdown-item botao_substituir" id="btn_substituir_<?php print $vn_objeto_codigo; ?> href="#">Substituir</a>
                            <?php
                            }
                            ?>

                            <?php if ($vb_pode_excluir)
                            {
                            ?>
                                <a class="dropdown-item botao_excluir" id="btn_excluir_<?php print $vn_objeto_codigo; ?>" href="confirmar_exclusao.php?obj=<?php print $vs_id_objeto; ?>&cod=<?php print $vn_objeto_codigo; ?>">Excluir</a>
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
                </div>
                
            </div>
        </div>

        <div style="margin-left:30px" id="children_<?php print $vn_objeto_codigo; ?>"></div>
    <?php
    }
?>