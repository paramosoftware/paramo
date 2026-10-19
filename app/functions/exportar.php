<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";
set_time_limit(0);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$vb_include_image = (isset($_POST["incluir_representante_digital_exportacao"]) && $_POST["incluir_representante_digital_exportacao"] == "1") ? true : false;
$vs_file_extension = (!empty($_POST["formato_exportacao"]) && utils::is_phpspreadsheet_installed()) ? ("." . $_POST["formato_exportacao"]) : ".csv";
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

if($vs_file_extension == ".xlsx")
{
    $spreadsheet = new Spreadsheet();
    $vr_file = $spreadsheet->getActiveSheet();
    if($vb_include_image)
    {
        $vr_file->getDefaultRowDimension()->setRowHeight(120);
        $vr_file->getRowDimension('1')->setRowHeight(20);
    }
}
else
    $vr_file = fopen($vs_file_path, "w");

create_header($vr_file, $va_itens_listagem ?? [], $vs_file_extension, $vb_include_image);

add_to_file($vr_file, $va_itens_listagem ?? [], ps_file_extension: $vs_file_extension, pb_include_image: $vb_include_image);

for ($vn_pagina_atual = 2; $vn_pagina_atual <= $vn_numero_maximo_paginas; $vn_pagina_atual++)
{
    require dirname(__FILE__) . "/montar_listagem.php";

    add_to_file($vr_file, $va_itens_listagem ?? [], ps_file_extension: $vs_file_extension, pb_include_image: $vb_include_image);

    utils::callback_progress($vs_file_name, $vn_pagina_atual / $vn_numero_maximo_paginas * 100);
}

if($vs_file_extension == ".xlsx" && utils::is_phpspreadsheet_installed())
{
    $writer = new Xlsx($spreadsheet);
    $writer->save($vs_file_path);
}
else
    fclose($vr_file);
utils::callback_progress($vs_file_name, 100);
utils::clear_temp_folder("-5 minutes");
exit();

function add_to_file($pr_file, $pa_itens_listagem, $pb_adicionar_cabecalho = false, $ps_file_extension = ".csv", $pb_include_image = false)
{
    $vn_contador = 1;

    $max_width = 0;
    foreach ($pa_itens_listagem as $index => $va_item_listagem)
    {
        $va_atributos = array();
        $vb_image_included = false;
        if($ps_file_extension == ".xlsx" && $pb_include_image)
        {
            $va_atributos[] = "";
            $vs_thumb_path = realpath("../media/images/thumb/");
            $vs_real_path = $vs_thumb_path . ($va_item_listagem["representante_digital"] ?? "");
            if(file_exists($vs_real_path) && $vs_thumb_path != $vs_real_path)
            {
                $drawing = new Drawing();
                $drawing->setName($va_item_listagem["representante_digital"]);
                $drawing->setDescription($va_item_listagem["representante_digital"]);
                $drawing->setPath($vs_real_path);
                $drawing->setHeight(120);
                $drawing->setCoordinates('A' . ($index+2));
                $drawing->setWorksheet($pr_file);
                $max_width = $max_width > $drawing->getWidth() ? $max_width : $drawing->getWidth();
                $vb_image_included = true;
            }
        }
        $va_atributos[] = $va_item_listagem["id_field"] ?? "";
        $va_atributos[] = $va_item_listagem["main_field"] ?? "";
        $va_atributos[] = $va_item_listagem["descriptive_field"] ?? "";

        foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["exibir"] && $va_atributos_item_listagem["exibir"] != 0)
            {
                $va_atributos[] = $va_atributos_item_listagem["valor"];
            }
        }

        if($ps_file_extension == ".xlsx")
        {
            $newRow = $pr_file->getHighestRow() + ($vb_image_included ? 0 : 1);
            $pr_file->fromArray($va_atributos, null, 'A' . $newRow);
        }
        else
            fputcsv($pr_file, $va_atributos, escape: "\\");

        $vn_contador++;
    }
    if($ps_file_extension == ".xlsx" && $pb_include_image)
        $pr_file->getColumnDimension('A')->setWidth($max_width / 7);
}

function create_header($pr_file, $pa_itens_listagem, $ps_file_extension = ".csv", $pb_include_image = false)
{
    foreach ($pa_itens_listagem as $va_item_listagem)
    {
        $va_labels = array();

        if($ps_file_extension == ".xlsx" && $pb_include_image)
            $va_labels[] = "Representante digital";
        $va_labels[] = $va_item_listagem["id_field_label"] ?? "";
        $va_labels[] = $va_item_listagem["main_field_label"] ?? "";
        $va_labels[] = $va_item_listagem["descriptive_field_label"] ?? "";

        foreach($va_item_listagem["atributos"] as $key => $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["exibir"] && $va_atributos_item_listagem["exibir"] != 0)
            {
                $va_labels[] = $va_atributos_item_listagem["label"];
            }

        }

        if($ps_file_extension == ".xlsx")
            $pr_file->fromArray($va_labels, null, 'A1');
        else
            fputcsv($pr_file, $va_labels, escape: "\\");

        break;
    }
}

?>