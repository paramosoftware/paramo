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

// Quantidade de arquivos em que a exportação será dividida. Só faz sentido no XLSX.
$vn_numero_partes = (int) ($_POST["numero_partes_exportacao"] ?? 1);
$vn_numero_partes = max(1, min(100, $vn_numero_partes));

if ($vs_file_extension !== utils::EXTENSAO_EXPORTACAO_XLSX)
    $vn_numero_partes = 1;

// Nome base: também é a chave de acompanhamento (progresso/parada)
$vs_file_name = "exportar-" . date("Y-m-d-H-i-s");

// Partes já concluídas, publicadas no manifesto para download imediato
$va_partes_exportacao = [];

// As miniaturas reduzidas existem apenas durante a exportação: são criadas
// sob demanda na pasta temp e removidas ao final (inclusive em erro/cancelamento)
register_shutdown_function(function () use ($vs_file_name)
{
    utils::remover_thumbs_exportacao_temporarias($vs_file_name);
});

utils::callback_progress($vs_file_name, 0, $va_partes_exportacao, false);
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

// Limpa os temporários das demais operações, preservando as exportações,
// e só então remove as exportações que já passaram do prazo de retenção.
utils::clear_temp_folder("-5 minutes");
utils::clear_temp_folder(config::get(["retencao_exportacao"]) ?? "-24 hours", "exportar-");

// Miniaturas temporárias que sobraram de uma execução interrompida
utils::clear_temp_folder("-3 hours", "-thumb-");

require dirname(__FILE__). "/montar_listagem.php";

$vn_numero_maximo_paginas = max(1, (int) ($vn_numero_maximo_paginas ?? 0));

if ($vn_numero_partes > $vn_numero_maximo_paginas)
    $vn_numero_partes = $vn_numero_maximo_paginas;

$vn_paginas_por_parte = (int) ceil($vn_numero_maximo_paginas / $vn_numero_partes);
$vn_largura_sufixo_parte = strlen((string) $vn_numero_partes);

// Página atualmente carregada em $va_itens_listagem (a primeira já foi carregada acima)
$vn_pagina_carregada = 1;
$vn_total_paginas_processadas = 0;

for ($vn_parte = 1; $vn_parte <= $vn_numero_partes; $vn_parte++)
{
    $vn_pagina_inicial = ($vn_parte - 1) * $vn_paginas_por_parte + 1;

    if ($vn_pagina_inicial > $vn_numero_maximo_paginas)
        break;

    $vn_pagina_final = min($vn_numero_maximo_paginas, $vn_pagina_inicial + $vn_paginas_por_parte - 1);

    if ($vn_pagina_carregada !== $vn_pagina_inicial)
    {
        $vn_pagina_atual = $vn_pagina_inicial;
        require dirname(__FILE__) . "/montar_listagem.php";
        $vn_pagina_carregada = $vn_pagina_inicial;
    }

    $vs_sufixo_parte = "";
    $vs_rotulo_parte = "Arquivo completo";

    if ($vn_numero_partes > 1)
    {
        $vs_sufixo_parte = "-parte-" . str_pad((string) $vn_parte, $vn_largura_sufixo_parte, "0", STR_PAD_LEFT) . "-de-" . $vn_numero_partes;
        $vs_rotulo_parte = "Parte " . $vn_parte . " de " . $vn_numero_partes;
    }

    $vs_parte_file_name = $vs_file_name . $vs_sufixo_parte . $vs_file_extension;
    $vs_file_path = config::get(["pasta_media", "temp"]) . $vs_parte_file_name;

    if ($vs_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX)
    {
        $spreadsheet = new Spreadsheet();
        $vr_file = $spreadsheet->getActiveSheet();
        if($vb_include_image)
            $vr_file->getRowDimension('1')->setRowHeight(20);
    }
    else
        $vr_file = fopen($vs_file_path, "w");

    create_header($vr_file, $va_itens_listagem ?? [], $vs_file_extension, $vb_include_image);

    // linha 1 é o cabeçalho; o contador recomeça em cada parte
    $vn_proxima_linha = 2;

    for ($vn_pagina = $vn_pagina_inicial; $vn_pagina <= $vn_pagina_final; $vn_pagina++)
    {
        if ($vn_pagina !== $vn_pagina_carregada)
        {
            $vn_pagina_atual = $vn_pagina;
            require dirname(__FILE__) . "/montar_listagem.php";
            $vn_pagina_carregada = $vn_pagina;
        }

        add_to_file($vr_file, $va_itens_listagem ?? [], $vn_proxima_linha, $vs_file_extension, $vb_include_image, $vs_file_name);

        $vn_total_paginas_processadas++;

        utils::callback_progress(
            $vs_file_name,
            $vn_total_paginas_processadas / $vn_numero_maximo_paginas * 100,
            $va_partes_exportacao,
            false
        );
    }

    if ($vs_file_extension === utils::EXTENSAO_EXPORTACAO_XLSX)
    {
        if($vb_include_image)
            ajustar_largura_coluna_imagens($vr_file);

        $writer = new Xlsx($spreadsheet);
        $writer->save($vs_file_path);

        // Libera as células e as imagens desta parte antes de montar a próxima
        unset($vr_file, $writer, $spreadsheet);
        gc_collect_cycles();
    }
    else
        fclose($vr_file);

    // Publica a parte assim que ela fica pronta, para download imediato
    $va_partes_exportacao[] = [
        "file" => $vs_parte_file_name,
        "label" => $vs_rotulo_parte,
        "size" => is_file($vs_file_path) ? filesize($vs_file_path) : 0,
    ];

    utils::callback_progress(
        $vs_file_name,
        $vn_total_paginas_processadas / $vn_numero_maximo_paginas * 100,
        $va_partes_exportacao,
        $vn_parte === $vn_numero_partes
    );
}

utils::callback_progress($vs_file_name, 100, $va_partes_exportacao, true);
utils::clear_temp_folder("-5 minutes");
exit();

function add_to_file($pr_file, $pa_itens_listagem, &$pn_proxima_linha, $ps_file_extension = utils::EXTENSAO_EXPORTACAO_CSV, $pb_include_image = false, $ps_tarefa = "")
{
    // Tamanho em que a miniatura é exibida e que orienta a versão reduzida
    // gerada temporariamente para esta exportação
    $vn_tamanho_maximo_imagem = (int) (config::get(["tamanho_thumb_exportacao"]) ?? 120);

    if ($vn_tamanho_maximo_imagem <= 0)
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

            // Gera (ou reaproveita) a miniatura reduzida temporária desta exportação
            $vs_real_path = utils::preparar_thumb_exportacao($vs_image_name, $ps_tarefa);

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