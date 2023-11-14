<?php

$va_objetos_itens_acervo = $va_objetos_itens_acervo ?? [];
$vs_termo_busca = $vs_termo_busca ?? "";
$vb_pode_editar = $vb_pode_editar ?? false;
$vo_objeto = $vo_objeto ?? null;

$vn_pagina_atual = 1;
$vb_houve_resultado = false;

foreach ($va_objetos_itens_acervo as $vs_id_objeto_tela => $va_recurso_sistema)
{
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border mb-0">
                                <thead class="table-light fw-semibold">
                                <tr class="align-middle">
                                    <th>Identificador</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php

                                $va_colunas_resultado_busca = array();
                                if (method_exists($vo_dashboard, "get_colunas_resultado_busca"))
                                    $va_colunas_resultado_busca = $vo_dashboard->get_colunas_resultado_busca($vs_id_objeto_tela);

                                foreach ($va_itens_listagem as $va_item_listagem)
                                {
                                    $vn_objeto_codigo = $va_item_listagem[$vo_objeto->get_chave_primaria()[0]];

                                    $va_atributos = [];

                                    foreach ($va_colunas_resultado_busca as $vs_coluna_resultado_busca)
                                    {
                                        $va_atributos[$vs_coluna_resultado_busca] = ler_valor1($vs_coluna_resultado_busca, $va_item_listagem);
                                    }

                                    $vs_url_editar = " #";
                                    if ($vb_pode_editar)
                                        $vs_url_editar = "editar.php?obj=" . $vs_id_objeto_tela . "&cod=" . $vn_objeto_codigo;
                                    ?>
                                    <tr class="align-middle">
                                        <td>
                                            <a href="<?php print $vs_url_editar; ?>">
                                                <div class="card-content-img">
                                                <?php 
                                                    if (isset($va_item_listagem["representante_digital_codigo"][0]["representante_digital_path"]))
                                                    {
                                                    ?>
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
                                                    <?php
                                                    }
                                                ?>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <div>
                                                <?php
                                                    foreach ($va_atributos as $vs_atributo => $vs_valor)
                                                    {
                                                        if ($vs_atributo == "item_acervo_identificador")
                                                        {
                                                            print "<a href='" . $vs_url_editar . "'>" . $vs_valor . "</a><br>";
                                                        }
                                                        else
                                                        {
                                                            print "<p>" . $vs_valor . "</span><br>";
                                                        }
                                                    }

                                                    if (count($va_atributos) == 0)
                                                    {
                                                        print "<a href='" . $vs_url_editar . "'>Sem identificador</a><br>";
                                                    }
                                                ?>
                                            </div>
                                        </td>
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