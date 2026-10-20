<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";
set_time_limit(0);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$vb_include_image = (isset($_POST["incluir_representante_digital_exportacao"]) && $_POST["incluir_representante_digital_exportacao"] == "1") ? true : false;
$vs_formato_exportacao = $_POST["formato_exportacao"] ?? "";
$vs_file_extension = (($vs_formato_exportacao === utils::FORMATO_EXPORTACAO_XLSX) && utils::is_phpspreadsheet_installed())
    ? utils::EXTENSAO_EXPORTACAO_XLSX
    : utils::EXTENSAO_EXPORTACAO_CSV;
$vs_file_name = "exportar-" . date("Y-m-d-H-i-s") . $vs_file_extension;
utils::callback_progress($vs_file_name, 0);
require_once dirname(__FILE__) . "/../components/terminar_requisicao.php";

if (!defined("NUMERO_ITENS_PAGINA_LISTAGEM"))
{
    define("NUMERO_ITENS_PAGINA_LISTAGEM", 50);
}

$vs_modo = $_POST["modo"] ?? null;

if ($vs_modo == "ficha")
{
    $vn_objeto_codigo = $_POST["cod"];
}

$vn_pagina_atual = 1;
$vb_retornar_valores_vazios = true;


$vs_visualizacao = "ficha";

require dirname(__FILE__). "/montar_listagem.php";

$vs_file_path = config::get(["pasta_media", "temp"]) . $vs_file_name;

$vn_numero_maximo_paginas = $vn_numero_maximo_paginas ?? 0;

if($vs_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX)
{
    $spreadsheet = new Spreadsheet();
    $vr_file = $spreadsheet->getActiveSheet();
    if($vb_include_image)
        $vr_file->getRowDimension('1')->setRowHeight(20);
}
else
    $vr_file = fopen($vs_file_path, "w");

create_header($vr_file, $va_itens_listagem ?? [], $vs_file_extension, $vb_include_image);

// linha 1 é o cabeçalho; o contador precisa atravessar todas as páginas
$vn_proxima_linha = 2;

add_to_file($vr_file, $va_itens_listagem ?? [], $vn_proxima_linha, $vs_file_extension, $vb_include_image);

for ($vn_pagina_atual = 2; $vn_pagina_atual <= $vn_numero_maximo_paginas; $vn_pagina_atual++)
{
    require dirname(__FILE__) . "/montar_listagem.php";

    add_to_file($vr_file, $va_itens_listagem ?? [], $vn_proxima_linha, $vs_file_extension, $vb_include_image);

    utils::callback_progress($vs_file_name, $vn_pagina_atual / $vn_numero_maximo_paginas * 100);
}

if($vs_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX && $vb_include_image)
    ajustar_largura_coluna_imagens($vr_file);

if($vs_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX && utils::is_phpspreadsheet_installed())
{
    $writer = new Xlsx($spreadsheet);
    $writer->save($vs_file_path);
}
else
    fclose($vr_file);
utils::callback_progress($vs_file_name, 100);
utils::clear_temp_folder("-5 minutes");
exit();

function add_to_file($pr_file, $pa_itens_listagem, &$pn_proxima_linha, $ps_file_extension = utils::EXTENSAO_EXPORTACAO_CSV, $pb_include_image = false)
{
    $vn_tamanho_maximo_imagem = 120;

    foreach ($pa_itens_listagem as $va_item_listagem)
    {
        $va_atributos = array();
        $vn_linha = $pn_proxima_linha;

        if($ps_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX && $pb_include_image)
        {
            // Coluna reservada ao representante digital (vazia quando o item não tem imagem)
            $va_atributos[] = "";

            $vs_image_name = basename(str_replace("\\", "/", (string) ($va_item_listagem["representante_digital"] ?? "")));

            $vs_thumb_dir = rtrim(config::get(["pasta_media", "images", "thumb"]), "/\\");
            $vs_real_path = ($vs_image_name !== "") ? $vs_thumb_dir . DIRECTORY_SEPARATOR . $vs_image_name : "";

            if($vs_real_path !== "" && is_file($vs_real_path))
            {
                $drawing = new Drawing();
                $drawing->setName($vs_image_name);
                $drawing->setDescription($vs_image_name);
                $drawing->setPath($vs_real_path);

                // Redimensiona proporcionalmente para caber inteira na célula
                $vn_fator_escala = min(
                    $vn_tamanho_maximo_imagem / max(1, $drawing->getWidth()),
                    $vn_tamanho_maximo_imagem / max(1, $drawing->getHeight())
                );

                $drawing->setWidthAndHeight(
                    max(1, (int) round($drawing->getWidth() * $vn_fator_escala)),
                    max(1, (int) round($drawing->getHeight() * $vn_fator_escala))
                );

                // Cada imagem fica ancorada na coluna A da linha do seu próprio registro
                $drawing->setCoordinates('A' . $vn_linha);
                $drawing->setOffsetX(0);
                $drawing->setOffsetY(0);
                $drawing->setWorksheet($pr_file);

                // Altura da linha acomoda a imagem, sem invadir a linha seguinte
                $pr_file->getRowDimension($vn_linha)->setRowHeight($drawing->getHeight() + 2, 'px');
            }
        }

        $va_atributos[] = $va_item_listagem["id_field"] ?? "";
        $va_atributos[] = $va_item_listagem["main_field"] ?? "";

        // Só escreve o campo descritivo quando o cabeçalho também o possui,
        // para que os valores não fiquem em colunas diferentes dos títulos
        if (isset($va_item_listagem["descriptive_field_label"])) 
        {
            $va_atributos[] = $va_item_listagem["descriptive_field"] ?? "";
        }

        foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["exibir"] && $va_atributos_item_listagem["exibir"] != 0)
            {
                $va_atributos[] = $va_atributos_item_listagem["valor"];
            }
        }

        if($ps_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX)
            $pr_file->fromArray($va_atributos, null, 'A' . $vn_linha);
        else
            fputcsv($pr_file, $va_atributos, escape: "\\");

        $pn_proxima_linha = $vn_linha + 1;
    }
}

function ajustar_largura_coluna_imagens($pr_file)
{
    $vn_largura_maxima = 0;

    foreach ($pr_file->getDrawingCollection() as $vo_drawing)
        $vn_largura_maxima = max($vn_largura_maxima, $vo_drawing->getWidth());

    if($vn_largura_maxima > 0)
        $pr_file->getColumnDimension('A')->setWidth($vn_largura_maxima + 5, 'px');
}

function create_header($pr_file, $pa_itens_listagem, $ps_file_extension = utils::EXTENSAO_EXPORTACAO_CSV, $pb_include_image = false)
{
    foreach ($pa_itens_listagem as $va_item_listagem)
    {
        $va_labels = array();

        if($ps_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX && $pb_include_image)
            $va_labels[] = "Representante digital";

        $va_labels[] = $va_item_listagem["id_field_label"] ?? "";
        $va_labels[] = $va_item_listagem["main_field_label"] ?? "";

        if (isset($va_item_listagem["descriptive_field_label"])) 
        {
            $va_labels[] = $va_item_listagem["descriptive_field_label"] ?? "";
        }

        foreach($va_item_listagem["atributos"] as $key => $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["exibir"] && $va_atributos_item_listagem["exibir"] != 0)
            {
                $va_labels[] = $va_atributos_item_listagem["label"];
            }

        }

        if($ps_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX)
            $pr_file->fromArray($va_labels, null, 'A1');
        else
            fputcsv($pr_file, $va_labels, escape: "\\");

        break;
    }
}

?>