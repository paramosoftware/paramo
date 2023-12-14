<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";
set_time_limit(0);

$vs_file_name = "exportar-" . date("Y-m-d-H-i-s") . ".csv";
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
require dirname(__FILE__). "/montar_listagem.php";

$vs_file_path = config::get(["pasta_media", "temp"]) . $vs_file_name;

$vn_numero_maximo_paginas = $vn_numero_maximo_paginas ?? 0;

$vr_file = fopen($vs_file_path, "w");

add_to_csv($vr_file, $va_itens_listagem ?? []);

for ($vn_pagina_atual = 2; $vn_pagina_atual <= $vn_numero_maximo_paginas; $vn_pagina_atual++)
{
    require dirname(__FILE__) . "/montar_listagem.php";

    add_to_csv($vr_file, $va_itens_listagem ?? []);

    utils::callback_progress($vs_file_name, $vn_pagina_atual / $vn_numero_maximo_paginas * 100);
}

fclose($vr_file);
utils::callback_progress($vs_file_name, 100);
utils::clear_temp_folder("-5 minutes");
exit();

function add_to_csv($pr_file, $pa_itens_listagem)
{
    foreach ($pa_itens_listagem as $va_item_listagem)
    {
        $va_atributos = array();

        foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["valor"] != "" && $va_atributos_item_listagem["exibir"])
            {
                $va_atributos[] = $va_atributos_item_listagem["valor"];
            }
        }

        fputcsv($pr_file, $va_atributos);
    }
}

?>