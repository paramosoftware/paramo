<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";
set_time_limit(600);

$vs_modo = $_POST["modo"] ?? null;

if ($vs_modo == "ficha")
{
    $vn_objeto_codigo = $_POST["cod"];
}

if (!defined("NUMERO_ITENS_PAGINA_LISTAGEM"))
{
    define("NUMERO_ITENS_PAGINA_LISTAGEM", 500);
}

$vn_pagina_atual = 1;

require dirname(__FILE__). "/montar_listagem.php";

$vs_file_name = "listagem-" . date("Y-m-d-H-i-s") . ".pdf";
$vs_file_path = config::get(["pasta_media", "temp"]) . $vs_file_name;

$report = null;

try {
    $report = new report_list($vs_file_path);
} catch (Exception $e) {
    session::log_and_redirect_error(
        "Ocorreu um erro ao imprimir o relatório.",
        $e->getMessage(),
        true
    );
}

$vs_acervo = $vs_recurso_sistema_nome ?? $vs_recurso_sistema_nome_plural ?? "não informado";

$report->vs_title = "Lista";
$report->vb_include_image = $_POST["incluir_representante_digital"] ?? true;
$report->vs_image_position = $_POST["posicao_representante_digital"] ?? 'left_side';
$report->vb_break_row =  $_POST["quebrar_linha"] ?? false;
$report->va_subheadings[] = ["label" => "Acervo", "value" => $vs_acervo];
$report->va_subheadings[] = ["label" => "Número de registros", "value" => $vn_numero_registros ?? 0];
$report->va_itens = $va_itens_listagem ?? [];
$report->process();

$vn_numero_maximo_paginas = $vn_numero_maximo_paginas ?? 0;

for ($vn_pagina_atual = 2; $vn_pagina_atual <= $vn_numero_maximo_paginas; $vn_pagina_atual++)
{
    require dirname(__FILE__) . "/montar_listagem.php";
    $report->va_itens = $va_itens_listagem ?? [];
    $report->process();
}

$report->Output();

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $vs_file_name . '"');
header('Content-Length: ' . filesize($vs_file_path));
readfile($vs_file_path);

utils::clear_temp_folder();