<?php

require_once dirname(__FILE__) . "/components/entry_point.php";

$vo_banco = Banco::get_instance();
$vn_times = $_GET["times"] ?? 50;
$vb_only_codigo = isset($_GET["only_codigo"]);

$va_select["tabela"] = "documento";
$va_select["campos"][] = $vb_only_codigo ? "documento.codigo" : "*";
$va_select["joins"][] = " JOIN item_acervo on documento.item_acervo_codigo = item_acervo.codigo";

for ($i = 0; $i < $vn_times; $i++)
{
    $start_time = microtime(true);
    $va_resultado = $vo_banco->consultar([$va_select]);
    $end_time = microtime(true);
    $execution_time = $end_time - $start_time;
    print "$i. " . $execution_time . "<br>";
}

require_once dirname(__FILE__) . "/components/footer.php";