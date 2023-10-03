<?php

    require_once dirname(__FILE__) . "/components/entry_point.php";

    require_once dirname(__FILE__) . '/../src/vendors/dompdf/autoload.inc.php';
    use Dompdf\Dompdf;

    $vs_data_inicial = "";
    if (isset($_POST["data_inicial"]))
        $vs_data_inicial = $_POST["data_inicial"];

    $vs_data_final = "";
    if (isset($_POST["data_final"]))
        $vs_data_final = $_POST["data_final"];

    $vs_tipo_operacao = "";
    if (isset($_POST["tipo_operacao"]))
        $vs_tipo_operacao = $_POST["tipo_operacao"];

    if (isset($_POST["agrupador"]))
        $ps_agrupador = $_POST["agrupador"];
    else
    {
        print "Parâmetros inválidos.";
        //header("refresh:4;url=index.php");
        //exit();
    }

    $vn_ordenacao_relatorio = 1;
    if (isset($_POST["ordenacao_relatorio_catalogacao"]))
        $vn_ordenacao_relatorio = $_POST["ordenacao_relatorio_catalogacao"];

    $vb_ordenar_por_quantidade = false;
    if ($vn_ordenacao_relatorio == 2)
        $vb_ordenar_por_quantidade = true;

    $va_labels_agrupadores = [
        "dia" => "Dia",
        "mes" => "Mês",
        "ano" => "Ano",
        "usuario" => "Usuário",
    ];

    $va_labels_tipos_operações = [
        "1" => "Criação de registros",
        "2" => "Atualização de registros",
    ];

    $vo_objeto = new $vs_id_objeto_tela;

    $va_parametros_filtros_consulta = array();
    //require_once dirname(__FILE__) . "/functions/montar_filtros_busca.php";

    $va_itens_listagem = $vo_objeto->ler_estatisticas_catalogacao($vs_data_inicial, $vs_data_final, $vs_tipo_operacao, $ps_agrupador, $vb_ordenar_por_quantidade);

    $va_html = array();
    $va_html[] = '<!DOCTYPE html>';
    $va_html[] = '<html>';
    $va_html[] = '<head>';
    $va_html[] = '<title>Relatório de indexação</title>';

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
    $va_html[] = '<p class="m-1"><b>Itens: </b>' . $vs_acervo . '</p>';
    //$va_html[] = '<p class="m-1"><b>Tipo: </b>' . $ps_agrupador . '</p>';

    if ($vs_data_inicial)
    {
        $vo_periodo = new periodo;
        $vo_periodo->set_data_inicial($vs_data_inicial);

        if ($vs_data_final)
            $vo_periodo->set_data_final($vs_data_final);
        else
            $vo_periodo->set_data_final($vs_data_inicial);

        $va_html[] = '<p class="m-1"><b>Período: </b>' . $vo_periodo->get_data_exibicao();
        $va_html[] = '</p>';
    }

    if (isset($va_labels_tipos_operações[$vs_tipo_operacao]))
        $va_html[] = '<p class="m-1"><b>Operação: </b>' . $va_labels_tipos_operações[$vs_tipo_operacao] . '</p>';

    //$va_html[] = '<p class="m-1"><b>Ordenado por: </b>' . ($vb_ordenar_por_quantidade ? "Quantidade" : "Valor ou termo agrupador") . '</p>';

    $va_html[] = '</td>';

    $va_html[] = '<td class="w-50 align-right">';
    $va_html[] = '<img src="'. utils::get_image_base64(config::get(["logo"])) . '" width="100px">';
    $va_html[] = '</td>';
    $va_html[] = '</tr>';

    $va_html[] = '</div>';

    $va_html[] = '<div class="listagem">';

    $va_html[] = '<table class="w-100">';
    $va_html[] = '<tr>';
    $va_html[] = '<th class="w-70 b align-left">'. $va_labels_agrupadores[$ps_agrupador] .'</th>';
    $va_html[] = '<th class="w-30 b align-left">Quantidade</th>';
    $va_html[] = '</tr>';

    $vn_contador = 0;
    $vn_total = 0;
    foreach ($va_itens_listagem as $va_item_listagem)
    {
        $vn_contador++;
        $va_html[] = '<tr class="'. ($vn_contador % 2 == 0 ? "bg-gray" : "") .'">';
        $va_html[] = '<td class="w-70">'. $va_item_listagem["agrupador"] .'</td>';
        $va_html[] = '<td class="w-30 ">'. $va_item_listagem["Q"] .'</td>';
        $va_html[] = '</tr>';

        $vn_total = $vn_total + $va_item_listagem["Q"];
    }

    $va_html[] = '<tr class="'. ($vn_contador % 2 != 0 ? "bg-gray" : "") .'">';
    $va_html[] = '<td class="w-70">Total</td>';
    $va_html[] = '<td class="w-30 ">'. $vn_total .'</td>';
    $va_html[] = '</tr>';

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