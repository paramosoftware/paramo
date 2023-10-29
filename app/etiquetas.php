<?php

require_once dirname(__FILE__) . "/components/entry_point.php";

$vn_pagina_etiquetas_codigo = $_POST["pagina_etiquetas"] ?? null;

if (!$vn_pagina_etiquetas_codigo)
{
    print "Nenhuma página de etiquetas foi configurada.";
    header("refresh:4;url=index.php");
    exit();
}

$vs_modo = $_GET["modo"] ?? null;

if ($vs_modo == "ficha")
{
    $vn_objeto_codigo = $_GET["cod"] ?? null;
}

require_once dirname(__FILE__) . "/functions/montar_listagem.php";

$vo_pagina_etiquetas = new pagina_etiquetas;
$va_pagina_etiquetas = $vo_pagina_etiquetas->ler($vn_pagina_etiquetas_codigo, "ficha");

if (!isset($va_pagina_etiquetas) || !isset($va_itens_listagem))
{
    echo "Ocorreu um erro ao gerar as etiquetas.";
    header("refresh:4;url=index.php");
    exit();
}

$vn_page_width = $va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_largura"];
$vn_page_height = $va_pagina_etiquetas["pagina_etiquetas_formato_codigo"]["formato_pagina_altura"];

if (class_exists("label_custom"))
{
    $vo_label = new label_custom('P', 'mm', [$vn_page_width, $vn_page_height]);
}
else
{
    $vo_label = new label('P', 'mm', [$vn_page_width, $vn_page_height]);
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
$vo_label->va_itens = $va_itens_listagem;

$vo_label->process();
$vo_label->Output();

?>