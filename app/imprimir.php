<?php
    use Dompdf\Dompdf;
    
    if (!isset($vb_public))
        $vb_public = false;

    require_once dirname(__FILE__) . '/../src/vendors/dompdf/autoload.inc.php';

    if (!$vb_public)
    {
        require_once dirname(__FILE__) . "/components/entry_point.php";

        if (isset($_POST["modo"]))
            $vs_modo = $_POST["modo"];

        if ($vs_modo == "ficha")
            $vn_objeto_codigo = $_POST["cod"];

        $vb_incluir_representante_digital = false;

        require_once dirname(__FILE__). "/functions/montar_listagem.php";

        if ($vn_numero_registros < 100)
        {
            $vb_incluir_representante_digital = true;
        }
    }
    else
        $vb_incluir_representante_digital = true;

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
    $va_html[] = '<p class="m-1">' . date("d/m/Y H:i:s") . '</p>';
    $va_html[] = '</td>';
    $va_html[] = '<td class="w-50 align-right">';
    $va_html[] = '<p class="m-1 page-number"></p>';
    $va_html[] = '</td>';
    $va_html[] = '</tr>';
    $va_html[] = '</table>';
    $va_html[] = '</div>';

    $va_html[] = '<div class="mb-30 b-bottom">';
    $va_html[] = '<table class="w-100">';
    $va_html[] = '<tr>';
    $va_html[] = ' <td class="w-50">';

    $vs_acervo = $vs_recurso_sistema_nome ?? $vs_recurso_sistema_nome_plural ?? "não informado";

    $vs_tipo_impressao = "listagem";

    if ($vs_visualizacao == "ficha")
    {
        $va_html[] = '<h2 class="m-1"> Ficha de item </h2>';
        $va_html[] = '<p class="m-1"><b>Acervo: </b>' . $vs_acervo . '</p>';

        $vs_tipo_impressao = "ficha";
    }
    elseif ($vs_visualizacao == "extroversao_ficha")
    {
        $va_html[] = '<h2 class="m-1">Fazenda do Pinhal</h2>';
    }
    else
    {
        $va_html[] = '<h2 class="m-1">Relatório</h2>';
        $va_html[] = '<p class="m-1"><b>Acervo: </b>' . $vs_acervo . '</p>';
        $va_html[] = '<p class="m-1"><b>Número de registros: </b>' . $vn_numero_registros . '</p>';
    }

    $va_html[] = '</td>';

    $va_html[] = '<td class="w-50 align-right">';
    $va_html[] = '<img src="'. utils::get_image_base64(config::get(["logo"])) . '" width="100px">';
    $va_html[] = '</td>';
    $va_html[] = '</tr>';
                  
    $va_html[] = '</div>';

    $va_html[] = '<div class="listagem">';

    foreach($va_itens_listagem as $va_item_listagem)
    {
        if ($vb_incluir_representante_digital)
        {
            $va_html[] = '<table class="mb-20 w-100 b">';
            $va_html[] =  '<tr>';
            $va_html[] =  '<td class="w-15">';

            $vs_image_base64 = "";
            if ($va_item_listagem["representante_digital"] != "")
            {
                $vs_path = config::get(["pasta_media", "images", "thumb"]) . $va_item_listagem["representante_digital"];
                $vs_image_base64 = utils::get_image_base64($vs_path);
            }

            if ($vs_image_base64 == "")
            {
                $va_html[] = 'Sem';
                $va_html[] =  '<br>';
                $va_html[] = 'imagem';
            }
            else
            {
                $va_html[] =  '<img src="'. $vs_image_base64 . '" width="100px">';
            }

            $va_html[] =  '</td>';
        }

        $va_html[] = '<table class="w-100 ' . ($vb_incluir_representante_digital ? '' : 'mb-20') . '">';

        if (isset($va_item_listagem["main_field"]))
        {
            $va_html[] = '<tr col="2">';
            $va_html[] = '<td colspan="2" class="b">';
            $va_html[] = $va_item_listagem["main_field"];
            $va_html[] = '</td>';
            $va_html[] = '</tr>';
        }

        foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["valor"] != "" && $va_atributos_item_listagem["exibir"])
            {
                $va_html[] = '<tr>';
                $va_html[] = '<td class="w-30 b">';
                $va_html[] = '<b>' . $va_atributos_item_listagem["label"] . ': </b>';
                $va_html[] = '</td>';
                $va_html[] = '<td class="w-70 b">';
                $va_html[] = $va_atributos_item_listagem["valor"];
                $va_html[] = '</td>';
                $va_html[] = '</tr>';
            }          
        }

        $va_html[] = '</table>';

        if ($vb_incluir_representante_digital)
        {
            $va_html[] =  '</tr>';
            $va_html[] =  '</table>';
        }
    }

    $va_html[] = '</div>';

    $va_html[] = '</body>';    
    $va_html[] = '</html>';

    $vs_conteudo_impressao = implode('', $va_html);

    //if (!$vb_public)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($vs_conteudo_impressao);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream($vs_tipo_impressao.'-'.date("Y-m-d-H-i-s").'.pdf');
    }
    //else
        //return $vs_conteudo_impressao;
?>