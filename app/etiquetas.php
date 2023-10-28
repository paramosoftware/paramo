<?php

    require_once dirname(__FILE__) . "/components/entry_point.php";

    require_once dirname(__FILE__) . "/../src/vendors/autoload.php";

    require_once dirname(__FILE__) . '/../src/vendors/dompdf/autoload.inc.php';

    use Dompdf\Dompdf;

    if (isset($_POST["modo"]))
        $vs_modo = $_POST["modo"];

    if (isset($_POST["pagina_etiquetas"]))
        $vn_pagina_etiquetas_codigo = $_POST["pagina_etiquetas"];
    else
    {
        print "Nenhuma página de etiquetas foi configurada.";
        header("refresh:4;url=index.php");
        exit();
    }

    if (isset($_POST["linha_inicial"]))
        $vn_linha_inicial = $_POST["linha_inicial"];
    else
        $vn_linha_inicial = 1;

    if (isset($_POST["coluna_inicial"]))
        $vn_coluna_inicial = $_POST["coluna_inicial"];
    else
        $vn_coluna_inicial = 1;

    $vb_codigo_barras = false;
    if (isset($_POST["codigo_barras"]))
        $vb_codigo_barras = $_POST["codigo_barras"] == "1";


    if ($vs_modo == "ficha")
        $vn_objeto_codigo = $_POST["cod"];

    require_once dirname(__FILE__). "/functions/montar_listagem.php";

    $vo_pagina_etiquetas = new pagina_etiquetas;
    $va_pagina_etiquetas = $vo_pagina_etiquetas->ler($vn_pagina_etiquetas_codigo, "ficha");


    $vs_custom_layout_path = config::get(["pasta_layouts"]) . "etiquetas.php";
    if (file_exists($vs_custom_layout_path))
    {
        require_once $vs_custom_layout_path;
        exit();
    }

    $vb_largura_disponivel_pagina = $va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_largura"] - $va_pagina_etiquetas["pagina_etiquetas_margem_esquerda"];
    $vn_numero_etiquetas_por_linha = intval($vb_largura_disponivel_pagina/$va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"]);

    $vs_margem_pagina = " margin: " . $va_pagina_etiquetas["pagina_etiquetas_margem_superior"] . "cm 0 0 " . $va_pagina_etiquetas["pagina_etiquetas_margem_esquerda"] . "cm";

    $vn_intervalo_etiquetas = 0;
    if (isset($va_pagina_etiquetas["pagina_etiquetas_intervalo_etiquetas"]))
        $vn_intervalo_etiquetas = $va_pagina_etiquetas["pagina_etiquetas_intervalo_etiquetas"];

    $vn_barcode_width = ( ($va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"]/2) - 1)*37.8 . 'px';

    $vo_bar_code_generator = new Picqer\Barcode\BarcodeGeneratorPNG();

    $va_html = array();

    $va_html[] = '<!DOCTYPE html>';
    $va_html[] = '<html>';
    $va_html[] = '<head>';
    $va_html[] = '<title>etiquetas</title>';

    $va_html[] = '<style type="text/css">
        @page 
        {'
            . $vs_margem_pagina .
        '}

        body 
        {
            font-family: font-family: Arial, Helvetica, sans-serif;
            text-align: justify;
            font-size: 10pt;
            width: ' . $va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_largura"] . 'cm;
        }
		
		table 
		{
			border-spacing: 0;
			border-collapse: collapse;
		}

        tr.linha_print
        {
            border: 0px solid;
            height: ' . $va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"] . 'cm;
            width: ' . ($va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_largura"] - $va_pagina_etiquetas["pagina_etiquetas_margem_esquerda"] - 0.1) . 'cm;
            /* page-break-inside: avoid; */
        }

        td.primeira_etiqueta_linha_print
        {
            overflow: auto;
            float: left;
            width: ' . $va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"] . 'cm;
            height: ' . $va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"] . 'cm;
            border: 0px solid;
        }

        td.etiqueta_print
        {
            overflow: auto;
            float: left;
            width: ' . $va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"] . 'cm;
            height: ' . $va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"] . 'cm;
            border: 0px solid;
            margin-left: ' . $vn_intervalo_etiquetas . 'cm;
        }

        td.barcode
        {
            overflow: auto;
            border: 0px solid;
            float: left;
            width: ' . (($va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"]/2) - 0.4) . 'cm;
            /* line-height: ' . ($va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"]) . 'cm; */
            text-align: center;
        }

        td.atributos_print
        {
            overflow: auto;
            border: 0px solid;
            float: left;
            width: ' . ( ($va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"]/2) + 0.2) . 'cm
            /*line-height: ' . ($va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"]) . 'cm;*/
            /* text-align: center; */
        }

        div.valor_atributo_print
        {
            overflow: auto;
            margin: auto;
            border: 0px solid;
            /* width: ' . ($va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"]/2) . 'cm; */
			padding-left: 1.5cm
        }

        img.barcode
        {
            width: ' . ( ($va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"]/2) - 1)*37.8 . 'px;
        }

        div.intervalo_etiquetas_print
        {
            float: left;
            border: 0px solid;
            width: ' . $vn_intervalo_etiquetas . 'cm;
            height: ' . ($va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"]) . 'cm;
        }
    </style>';

    $va_html[] = '</head>';

    $va_html[] = '<body>';
    $va_html[] = '<table>';
    
    $contador = 1;
	$contador_itens = 0;
    $contador_linha = 0;


    $va_itens_listagem_filtrados = array();

    foreach ($va_itens_listagem as $indice => $va_item_listagem)
    {
        $vb_tem_valor = false;

        if (isset($va_item_listagem["main_field"]))
        {
            $vb_tem_valor = true;
        }

        if (!$vb_tem_valor)
        {
            foreach ($va_item_listagem["atributos"] as $va_atributo)
            {
                if (!empty($va_atributo["valor"]))
                {
                    $vb_tem_valor = true;
                    break;
                }
            }

            if (count($va_item_listagem["atributos"]) == 0)
            {
                $vb_tem_valor = false;
            }
        }

        if ($vb_tem_valor)
        {
            $va_itens_listagem_filtrados[] = $va_item_listagem;
        }
    }
    
	while ($contador_itens < count($va_itens_listagem_filtrados))
    {
		$va_item_listagem = $va_itens_listagem_filtrados[$contador_itens];
		
	    if ( ($contador % $vn_numero_etiquetas_por_linha) == 1 )
        {
            $va_html[] = '<tr class="linha_print">';

            $contador_linha++;
            $contador_coluna = 1;
        }

        if ($contador_coluna == 1)
            $va_html[] = '<td class="primeira_etiqueta_linha_print">';
        else
            $va_html[] = '<td class="etiqueta_print">';

            // Vamos verificar se eu posso plotar a etiqueta nesta posição (linha, coluna)

            $vb_pode_plotar_etiqueta = false;
            if ( ($contador_linha > $vn_linha_inicial) )
                $vb_pode_plotar_etiqueta = true;
            elseif ( ($contador_linha == $vn_linha_inicial) && ($contador_coluna >= $vn_coluna_inicial) )
                $vb_pode_plotar_etiqueta = true;

            if ($vb_pode_plotar_etiqueta)
            {
                $va_html[] = '<table width=100%><tr>';
                if (isset($va_item_listagem["livro_codigo"]))
                {
                    if ($vb_codigo_barras)
                    {
                        $vs_valor_barcode = str_pad($va_item_listagem["livro_codigo"], 12, "0", STR_PAD_LEFT);

                        $va_html[] = '<td class="barcode">';

                        $va_html[] = '<img class="barcode" src="data:image/png;base64,' . base64_encode($vo_bar_code_generator->getBarcode($vs_valor_barcode, $vo_bar_code_generator::TYPE_CODE_128)) . '">';

                        foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
                        {
                            if ($va_atributos_item_listagem["valor"] && $va_atributos_item_listagem["label"] == "item_acervo_identificador")
                            {
                                $va_html[] = "<br>" . $va_atributos_item_listagem["valor"];
                            }
                        }

                        $va_html[] = '</td>';
                    }
                }

                $va_html[] = '<td class="atributos_print"><div">';

                if (isset($va_item_listagem["main_field"]))
                    $va_html[] = $va_item_listagem["main_field"] . "<br>";

                foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
                {

                    if ($va_atributos_item_listagem["valor"] && strtolower($va_atributos_item_listagem["label"]) == "exemplar")
                    {
                        $va_html[] = "ex. ". $va_atributos_item_listagem["valor"] . "<br>";
                        continue;
                    }

                    if ($va_atributos_item_listagem["valor"] && strtolower($va_atributos_item_listagem["label"]) == "volume")
                    {
                        $va_html[] = "vol.". $va_atributos_item_listagem["valor"] . "<br>";
                        continue;
                    }

                    if ($va_atributos_item_listagem["valor"] && $va_atributos_item_listagem["label"] != "item_acervo_identificador" && $va_atributos_item_listagem["exibir"])
                    {
                        //$va_html[] = '<div class="valor_atributo_print">';
                        $va_html[] = $va_atributos_item_listagem["valor"] . "<br>";
                        //$va_html[] = '</div>';
                    }          
                }
                
                $va_html[] = '</div></td>';
                $va_html[] = '</tr></table>';
				
				$contador_itens++;
            }

        $va_html[] = '</td>';

        if ( ($contador % $vn_numero_etiquetas_por_linha) == 0 || ($contador == count($va_itens_listagem_filtrados)) )
            $va_html[] = '</tr>';
			
		$contador++;
		$contador_coluna++;
    }

    $va_html[] = '</table>';
    $va_html[] = '</body>';
    $va_html[] = '</html>';

    $vs_conteudo_impressao = implode('', $va_html);

    //print $vs_conteudo_impressao;exit();
    
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $dompdf->loadHtml($vs_conteudo_impressao);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('letter', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream();
?>