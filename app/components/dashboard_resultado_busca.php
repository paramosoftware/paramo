<?php

$va_objetos_itens_acervo = $va_objetos_itens_acervo ?? [];
$vs_termo_busca = $vs_termo_busca ?? "";
$vo_objeto = $vo_objeto ?? null;
$vb_montar_filtros_busca = true;

$vn_pagina_atual = 1;
$vb_houve_resultado = false;

foreach ($va_objetos_itens_acervo as $vs_id_objeto_tela => $va_recurso_sistema)
{
    if (!$vb_busca_id)
        $va_parametros_filtros_consulta[$vo_dashboard->get_filtro_busca_geral($vs_id_objeto_tela)] = [$vs_termo_busca, "LIKE"];

    $vs_formato_listagem = "default";
    require dirname(__FILE__) . "/../functions/montar_listagem.php";

    $va_itens_listagem = $va_itens_listagem ?? [];

    if ($vb_busca_id && count($va_itens_listagem))
    {
        print '<script>window.location="editar.php?obj=' . $vs_id_objeto_tela .'&cod=' . $va_itens_listagem[0][$vo_objeto->get_chave_primaria()[0]] . '";</script>';
    }

    if (count($va_itens_listagem))
    {
        $vb_houve_resultado = true;
        $vb_plural = false;
        if (count($va_itens_listagem) > 1)
            $vb_plural = true;

        $vb_feminino = false;
        if ($va_recurso_sistema["genero_gramatical"] == 1)
            $vb_feminino = true;

        if (!$vs_termo_busca)
        {
            $vs_header =
                ($vb_feminino ? "Últimas " : "Últimos ")
                . strtolower($va_recurso_sistema["nome_plural"])
                . ($vb_feminino ? " cadastradas" : " cadastrados");
        } 
        else
        {
            $vs_header = $va_recurso_sistema["nome_plural"];
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card deashboard-card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12 mt-2">
                                <span>
                                    <?php 
                                        print $vs_header . " (";
                                        
                                        if ($vn_numero_registros > 20)
                                            print 'Listando <u>20</u> resultados de <u>' . $vn_numero_registros . '</u>. Para refinar a busca, use os <a class="link" href="listar.php?obj=' . $vs_id_objeto_tela . '">filtros de busca avançada.</a>';
                                        else
                                            print $vn_numero_registros . " resultados";

                                        print ")";
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php
                        $va_colunas_resultado_busca = array();
                        if (method_exists($vo_dashboard, "get_colunas_resultado_busca"))
                            $va_colunas_resultado_busca = $vo_dashboard->get_colunas_resultado_busca($vs_id_objeto_tela);
                    ?>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border mb-0">
                                <thead>
                                    <tr>
                                    <?php
                                    foreach ($va_colunas_resultado_busca as $vs_label_resultado_busca => $vs_atributo_resultado_busca)
                                    {
                                        print "<th>" . $vs_label_resultado_busca . "</th>";
                                    }
                                    ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($va_itens_listagem as $va_item_listagem)
                                {

                                    $vs_identificador = "Sem identificador";

                                    $vn_objeto_codigo = $va_item_listagem[$vo_objeto->get_chave_primaria()[0]];

                                    $va_atributos = [];

                                    foreach ($va_colunas_resultado_busca as $vs_label_resultado_busca => $vs_atributo_resultado_busca)
                                    {
                                        if ($vs_atributo_resultado_busca == "item_acervo_identificador")
                                        {
                                           $vs_identificador = ler_valor1($vs_atributo_resultado_busca, $va_item_listagem);
                                        }
                                        else
                                        {
                                            $va_atributos[$vs_atributo_resultado_busca] = ler_valor1($vs_atributo_resultado_busca, $va_item_listagem);
                                        }
                                    }

                                    $vs_url_editar = "editar.php?obj=" . $vs_id_objeto_tela . "&cod=" . $vn_objeto_codigo;
                                    ?>
                                    <tr class="align-middle">
                                        <td>
                                            <a href="<?php print $vs_url_editar; ?>">
                                                <div></div>
                                                <span class="mx-auto">
                                                <?= $vs_identificador; ?>
                                                </span>

                                                <?php

                                                $vb_tem_representante_digital = isset($va_item_listagem["representante_digital_codigo"][0]["representante_digital_path"]);

                                                if ($vb_tem_representante_digital)
                                                {
                                                ?>
                                                <div class="card-content-img">
                                                        <div class="">
                                                            <?php

                                                            $vs_image = $va_item_listagem["representante_digital_codigo"][0]["representante_digital_path"];

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
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </a>
                                        </td>
                                        <?php
                                        foreach ($va_atributos as $vs_atributo_resultado_busca)
                                        {
                                            ?>
                                            <td>
                                                <?= $vs_atributo_resultado_busca; ?>
                                            </td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!$vb_houve_resultado && ($vs_termo_busca || $vs_busca_id))
{
    print "<div class='row'><div class='col-md-12'><div class='card deashboard-card mb-4'>";
    print "<div class='card-body'>Nenhum resultado encontrado.</div></div></div></div>";
}