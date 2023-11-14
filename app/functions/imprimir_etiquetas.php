<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";

$vs_file_name = "etiquetas-" . date("Y-m-d-H-i-s") . ".pdf";
utils::callback_progress($vs_file_name, 0);
require_once dirname(__FILE__) . "/../components/terminar_requisicao.php";

$vn_pagina_etiquetas_codigo = $_POST["pagina_etiquetas"] ?? null;
if (!$vn_pagina_etiquetas_codigo)
{
    utils::callback_progress($vs_file_name, "Nenhuma página de etiquetas foi configurada");
    utils::log("Nenhuma página de etiquetas foi configurada", var_export($_POST, true));
    exit();
}

$vs_modo = $_GET["modo"] ?? null;

if ($vs_modo == "ficha")
{
    $vn_objeto_codigo = $_GET["cod"] ?? null;
}

$vo_pagina_etiquetas = new pagina_etiquetas;
$va_pagina_etiquetas = $vo_pagina_etiquetas->ler($vn_pagina_etiquetas_codigo, "ficha");

if (!isset($va_pagina_etiquetas))
{
    utils::callback_progress($vs_file_name, "Não foi possível encontrar a página de etiquetas");
    utils::log(
        "Ocorreu um erro ao gerar as etiquetas.",
        "Não foi possível encontrar a página de etiquetas: " . var_export($_POST, true)
    );
    exit();
}

$vn_page_width = $va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_largura"];
$vn_page_height = $va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_altura"];

$vs_file_path = config::get(["pasta_media", "temp"]) . $vs_file_name;

$vn_modelo_etiqueta_codigo = $_POST["modelo_etiqueta"] ?? 1;
$vs_class_name = $vn_modelo_etiqueta_codigo == 1 ? "label" : "label_box";
$vs_custom_class_name = $vs_class_name."_custom";

if (class_exists($vs_custom_class_name))
{
    $vo_label = new $vs_custom_class_name($vs_file_path, 'P', 'mm', [$vn_page_width, $vn_page_height]);
}
else
{
    $vo_label = new $vs_class_name($vs_file_path, 'P', 'mm', [$vn_page_width, $vn_page_height]);
}


$vo_label->vn_margin_top = $va_pagina_etiquetas["pagina_etiquetas_margem_superior"];
$vo_label->vn_margin_left = $va_pagina_etiquetas["pagina_etiquetas_margem_esquerda"];
$vo_label->vn_gap = $va_pagina_etiquetas["pagina_etiquetas_intervalo_etiquetas"] == 0 ? 0.1 : $va_pagina_etiquetas["pagina_etiquetas_intervalo_etiquetas"];
$vo_label->vn_label_width = $va_pagina_etiquetas["pagina_etiquetas_largura_etiqueta"];
$vo_label->vn_label_height = $va_pagina_etiquetas["pagina_etiquetas_altura_etiqueta"];
$vo_label->vn_label_internal_left_margin = 1;
$vo_label->vn_label_internal_top_margin = 1;
$vo_label->vb_has_barcode = isset($_POST["codigo_barras"]) && boolval($_POST["codigo_barras"]);
$vo_label->vn_start_row = $_POST["linha_inicial"] ?? 1;
$vo_label->vn_start_col = $_POST["coluna_inicial"] ?? 1;

$vn_pagina_atual = 1;
require dirname(__FILE__) . "/montar_listagem.php";

$vn_numero_maximo_paginas = $vn_numero_maximo_paginas ?? 0;

$va_itens_listagem_temp = $va_itens_listagem ?? [];

for ($vn_pagina_atual = 2; $vn_pagina_atual <= $vn_numero_maximo_paginas; $vn_pagina_atual++)
{
    require dirname(__FILE__) . "/montar_listagem.php";
    $va_itens_listagem_temp = array_merge($va_itens_listagem_temp, $va_itens_listagem ?? []);
    utils::callback_progress($vs_file_name, $vn_pagina_atual / $vn_numero_maximo_paginas * 100);
}

$vo_label->va_itens = $va_itens_listagem_temp;
$vo_label->process();
$vo_label->Output();

utils::callback_progress($vs_file_name, 100);
utils::clear_temp_folder("1 minute", '.png');
utils::clear_temp_folder("-5 minutes");
exit();

?>