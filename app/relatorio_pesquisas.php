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

    $vn_setor_sistema_codigo = "";
    if (isset($_POST["setor_sistema_codigo"]))
        $vn_setor_sistema_codigo = $_POST["setor_sistema_codigo"];

    $vo_objeto = new objeto_base;

    $va_parametros_filtros_consulta = array();
 
    $va_itens_listagem = $vo_objeto->ler_atividades_pesquisa_usuario($vs_data_inicial, $vs_data_final, $vn_setor_sistema_codigo);

    $va_html = array();
    $va_html[] = '<!DOCTYPE html>';
    $va_html[] = '<html>';
    $va_html[] = '<head>';
    $va_html[] = '<title>Relatório de pesquisas do usuário</title>';

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
    $va_html[] = ' <td class="w-70">';


    $va_html[] = '<h2 class="m-1">Relatório de pesquisas do usuário</h2>';

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

    $vs_acervo = "Todos";

    if ($_POST["setor_sistema_codigo"] != "")
    {
        $vo_setor = new setor_sistema;
        $va_setor = $vo_setor->ler($_POST["setor_sistema_codigo"]);
        $vs_acervo = $va_setor["setor_sistema_nome"];
    }


    $va_html[] = '<p class="m-1"><b>Acervo: </b>' . $vs_acervo . '</p>';

    $va_html[] = '</td>';

    $va_html[] = '<td class="w-30 align-right">';
    $va_html[] = '<img src="'. utils::get_image_base64(config::get(["logo"])) . '" width="100px">';
    $va_html[] = '</td>';
    $va_html[] = '</tr>';

    $va_html[] = '</div>';

    $vs_setor_sistema_nome = "";

    $va_secoes = array();

    foreach ($va_itens_listagem as $va_item_listagem)
    {

        $vs_setor_sistema_nome = $va_item_listagem["setor"];
        $vs_campo_nome = $va_item_listagem["campo"];
        $vs_valor = $va_item_listagem["valor"];
        $vn_frequencia = $va_item_listagem["Q"];

        if (!isset($va_secoes[$vs_setor_sistema_nome]))
            $va_secoes[$vs_setor_sistema_nome] = array();

        if (!isset($va_secoes[$vs_setor_sistema_nome][$vs_campo_nome]))
            $va_secoes[$vs_setor_sistema_nome][$vs_campo_nome] = array();

        if (!isset($va_secoes[$vs_setor_sistema_nome][$vs_campo_nome][$vs_valor]))
            $va_secoes[$vs_setor_sistema_nome][$vs_campo_nome][$vs_valor] = 0;

        $va_secoes[$vs_setor_sistema_nome][$vs_campo_nome][$vs_valor] += $vn_frequencia;
    }


    $va_html[] = '<div class="listagem" style="margin-top:20px">';
    $va_html[] = '<table class="w-100">';
    $va_html[] = '<tr>';


    if (count($va_secoes) == 0)
    {
        $va_html[] = '<td class="w-100 align-left">';
        $va_html[] = '<h3 class="m-1">Nenhum registro encontrado</h3>';
        $va_html[] = '</td>';
        $va_html[] = '</tr>';
        $va_html[] = '</table>';
        $va_html[] = '</div>';
    }
    else
    {

        $va_html[] = '<th class="w-70 b align-left">Valor pesquisado</th>';
        $va_html[] = '<th class="w-30 b align-left">Frequência</th>';
        $va_html[] = '</tr>';
    }

    foreach ($va_secoes as $va_setor_sistema_nome => $va_campos)
    {

        if ($vs_acervo == "Todos")
        {
            $va_html[] = '<tr>';
            $va_html[] = '<td colspan="2" class="w-100 b align-left bg-gray">';
            $va_html[] = '<b class="m-1">' . $va_setor_sistema_nome . '</b>';
            $va_html[] = '</td>';
            $va_html[] = '</tr>';
        }

        foreach ($va_campos as $vs_campo_nome => $va_valores)
        {
            $va_html[] = '<tr>';
            $va_html[] = '<td colspan="2" class="w-100 b align-left ml-2 bg-light-gray">';
            $va_html[] = '<b class="m-1 pl-10">' .  ($vs_campo_nome == "" ? "Sem label" : $vs_campo_nome) . '</b>';
            $va_html[] = '</td>';
            $va_html[] = '</tr>';

            foreach ($va_valores as $vs_valor => $vn_frequencia)
            {
                $va_html[] = '<tr>';
                $va_html[] = '<td class="w-70">';
                $va_html[] = '<p class="m-1 pl-20">' . $vs_valor . '</p>';
                $va_html[] = '</td>';
                $va_html[] = '<td class="w-15 align-right">';
                $va_html[] = '<p class="m-1">' . $vn_frequencia . '</p>';
                $va_html[] = '</td>';
                $va_html[] = '</tr>';
            }
        }
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