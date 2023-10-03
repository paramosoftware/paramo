<?php

    require_once dirname(__FILE__) . "/components/entry_point.php";

    require_once dirname(__FILE__) . '/../src/vendors/dompdf/autoload.inc.php';
    use Dompdf\Dompdf;

    if (isset($_POST["campo_sistema_codigo"]))
        $vn_campo_sistema_codigo = $_POST["campo_sistema_codigo"];
    else
    {
        print "Parâmetros inválidos.";
        header("refresh:4;url=index.php");
        exit();
    }

    $vn_ordenacao_relatorio = 1;
    if (isset($_POST["ordenacao_relatorio"]))
        $vn_ordenacao_relatorio = $_POST["ordenacao_relatorio"];

    $vb_ordenar_por_quantidade = false;
    if ($vn_ordenacao_relatorio == 2)
        $vb_ordenar_por_quantidade = true;

    $vo_campo_sistema = new campo_sistema;
    $va_campo_sistema = $vo_campo_sistema->ler($vn_campo_sistema_codigo, "ficha");

    $vs_campo_sistema_id = $va_campo_sistema["campo_sistema_nome"];

    if (isset($va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"]))
        $vs_campo_sistema_id = $va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"] . "_0_" . $vs_campo_sistema_id;

    $va_campos_sistema_objeto_relacionado = $vo_campo_sistema->ler_lista(["campo_sistema_recurso_sistema_codigo" => $va_campo_sistema["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_codigo"]]);
    
    $vs_objeto_relacionado_campo_identificador = "";
    foreach ($va_campos_sistema_objeto_relacionado as $va_campo_objeto_relacionado)
    {
        if ($va_campo_objeto_relacionado["campo_sistema_identificador_recurso_sistema"])
        {
            $vs_objeto_relacionado_campo_identificador = $va_campo_objeto_relacionado["campo_sistema_nome"];
        }
    }

    if (!$vs_objeto_relacionado_campo_identificador)
    {
        print "Parâmetros inválidos.";
        header("refresh:4;url=index.php");
        exit();
    }

    $vo_objeto = new $vs_id_objeto_tela;

    $va_parametros_filtros_consulta = array();
    require_once dirname(__FILE__) . "/functions/montar_filtros_busca.php";

    $va_itens_listagem = $vo_objeto->ler_lista_quantitativa($vs_campo_sistema_id, $vs_objeto_relacionado_campo_identificador, $va_parametros_filtros_consulta, $vb_ordenar_por_quantidade);

    $va_html = array();
    $va_html[] = '<!DOCTYPE html>';
    $va_html[] = '<html>';
    $va_html[] = '<head>';
    $va_html[] = '<title>Relatório para impressão</title>';

    $va_html[] = '<style>';
    $va_html[] = file_get_contents(dirname(__FILE__) . "/assets/css/relatorios.css");
    $va_html[] = '</style>';

    $va_html[] = '</head>';

    $va_html[] = '<body>';

    $va_html[] = '<div class="footer">';
    $va_html[] = '<table class="w-100">';
    $va_html[] = '<tr>';
    $va_html[] = '<td class="w-50">';
    $va_html[] = '<p class="m-1">Gerado em ' . date("d/m/Y H:i:s") . '</p>';
    $va_html[] = '</td>';
    $va_html[] = '<td class="w-50 align-right">';
    $va_html[] = '<p class="m-1 page-number"></p>';
    $va_html[] = '</td>';
    $va_html[] = '</tr>';
    $va_html[] = '</table>';
    $va_html[] = '</div>';

    $va_html[] = '<div class="b-bottom mb-20">';
    $va_html[] = '<table class="w-100">';
    $va_html[] = '<tr>';
    $va_html[] = ' <td class="w-50">';

    $vs_acervo = $vs_recurso_sistema_nome ?? $vs_recurso_sistema_nome_plural ?? "não informado";

    $va_html[] = '<h2 class="m-1">Relatório</h2>';
    $va_html[] = '<p class="m-1"><b>Acervo: </b>' . $vs_acervo . '</p>';
    $va_html[] = '<p class="m-1"><b>Agrupado por: </b>' . $va_campo_sistema["campo_sistema_alias"] . '</p>';
    $va_html[] = '<p class="m-1"><b>Ordenado por: </b>' . ($vb_ordenar_por_quantidade ? "Quantidade" : "Nome") . '</p>';

    $va_html[] = '</td>';

    $va_html[] = '<td class="w-50 align-right">';
    $va_html[] = '<img src="'. utils::get_image_base64(config::get(["logo"])) . '" width="100px">';
    $va_html[] = '</td>';
    $va_html[] = '</tr>';

    $va_html[] = '</div>';

    $va_html[] = '<div class="listagem">';

    $va_html[] = '<table class="w-100">';
    $va_html[] = '<tr>';
    $va_html[] = '<th class="w-70 b align-left">'. $va_campo_sistema["campo_sistema_alias"].'</th>';
    $va_html[] = '<th class="w-30 b align-left">Quantidade</th>';
    $va_html[] = '</tr>';

    $vn_contador = 0;
    foreach ($va_itens_listagem as $va_item_listagem)
    {
        $vn_contador++;
        $va_html[] = '<tr class="'. ($vn_contador % 2 == 0 ? "bg-gray" : "") .'">';
        $va_html[] = '<td class="w-70">'. $va_item_listagem[$vs_objeto_relacionado_campo_identificador] .'</td>';
        $va_html[] = '<td class="w-30 ">'. $va_item_listagem["Q"] .'</td>';
        $va_html[] = '</tr>';
    }

    $va_html[] = '</table>';

    $va_html[] = '</div>';

    $va_html[] = '</body>';
    $va_html[] = '</html>';

    $vs_conteudo_impressao = implode('', $va_html);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($vs_conteudo_impressao);
    $dompdf->setPaper('A4');
    $dompdf->render();
    $dompdf->stream('relatorio-'.date("Y-m-d-H-i-s").'.pdf');


?>    