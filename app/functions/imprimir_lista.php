<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";
set_time_limit(0);

$vs_file_name = "listagem-" . date("Y-m-d-H-i-s") . ".pdf";
utils::callback_progress($vs_file_name, 0);
require_once dirname(__FILE__) . "/../components/terminar_requisicao.php";

$vs_modo = $_POST["modo"] ?? null;

if ($vs_modo == "ficha")
{
    $vn_objeto_codigo = $_POST["cod"];
}

if (!defined("NUMERO_ITENS_PAGINA_LISTAGEM"))
{
    define("NUMERO_ITENS_PAGINA_LISTAGEM", 50);
}

$vn_pagina_atual = 1;
$vb_expandir_niveis_hierarquicos = true;

require dirname(__FILE__). "/montar_listagem.php";

$vs_file_path = config::get(["pasta_media", "temp"]) . $vs_file_name;

try {
    $vo_label = new report_list($vs_file_path);
} catch (Exception $e) {
    utils::callback_progress($vs_file_name, "Não foi possível criar o relatório");
    utils::log("Ocorreu um erro inicializar o relatório.", $e->getMessage());
    exit();
}

$vs_acervo = $vs_recurso_sistema_nome ?? $vs_recurso_sistema_nome_plural ?? "não informado";

$vo_label->vs_title = $vs_modo == "ficha" ? "Ficha" : "Lista";
$vo_label->vb_include_image = $_POST["incluir_representante_digital"] ?? true;
$vo_label->vs_image_position = $_POST["posicao_representante_digital"] ?? 'left_side';
$vo_label->vb_break_row =  $_POST["quebrar_linha"] ?? false;
$vo_label->va_subheadings[] = ["label" => "Acervo", "value" => $vs_acervo];
if ($vs_modo != "ficha")
{
    $vo_label->va_subheadings[] = ["label" => "Número de registros", "value" => $vn_numero_registros ?? 0];
}
$vo_label->va_itens = $va_itens_listagem ?? [];
$vo_label->process();

$vn_numero_maximo_paginas = $vn_numero_maximo_paginas ?? 0;

$va_itens_listagem_temp = [];

for ($vn_pagina_atual = 2; $vn_pagina_atual <= $vn_numero_maximo_paginas; $vn_pagina_atual++)
{
    require dirname(__FILE__) . "/montar_listagem.php";

    $va_itens_listagem_temp = array_merge($va_itens_listagem_temp, $va_itens_listagem ?? []);

    utils::callback_progress($vs_file_name, $vn_pagina_atual / $vn_numero_maximo_paginas * 100);

    if (($vn_pagina_atual % (1000 / NUMERO_ITENS_PAGINA_LISTAGEM)) == 0 || $vn_pagina_atual == $vn_numero_maximo_paginas)
    {
        $vo_label->va_itens = $va_itens_listagem_temp;
        $vo_label->process();

        $va_itens_listagem_temp = [];
    }
}

$vo_label->Output();

utils::callback_progress($vs_file_name, 100);
utils::clear_temp_folder("-5 minutes");
exit();

