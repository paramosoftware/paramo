<?php

require_once dirname(__FILE__) . "/components/entry_point.php";

$vo_banco = Banco::get_instance();
$vn_times = $_GET["times"] ?? 50;
$vb_only_codigo = isset($_GET["only_codigo"]);

$va_select["tabela"] = "documento";
$va_select["campos"][] = $vb_only_codigo ? "documento.codigo" : "*";
$va_select["joins"][] = " JOIN item_acervo on documento.item_acervo_codigo = item_acervo.codigo";

$va_exs = [];

$vn_execution_time_sum = 0;
$script_start_time = microtime(true);
for ($i = 0; $i < $vn_times; $i++)
{
    $start_time = microtime(true);
    $va_resultado = $vo_banco->consultar([$va_select]);
    $end_time = microtime(true);
    $execution_time = $end_time - $start_time;
    $vn_execution_time_sum += $execution_time;
    $va_exs[] = ($i + 1) . " - Tempo de execução: " . $execution_time . "s";
}
$script_end_time = microtime(true);

$ping_db = ping_domain("localhost", 3306, $vn_times);

print "<h2>Informações da Execução</h2>";

print "Tempo total de carregamento: " . ($script_end_time - $_GET["start_time"]) . "<br>";
print "Número de consultas: " . $vn_times . "<br>";
print "Tempo médio: " . ($vn_execution_time_sum / $vn_times) . "<br>";
print "Tempo de execução das queries: " . $_GET["queries_execution_time"] . "<br>";
print "Tempo de execução das desconsiderando ping: " . ($_GET["queries_execution_time"] - $ping_db) . "<br>";

print "<h2>Informações de Uso de Memória</h2>";

print "Memória RAM do servidor: " . convert(memory_get_peak_usage(true)) . "<br>";
print "Limite de memória do script: " . ini_get('memory_limit') . "<br>";
print "Memória utilizada: " . convert(memory_get_peak_usage()) . "<br>";
print "Memória utilizada real: " . convert(memory_get_peak_usage(true)) . "<br>";
print "Memória disponível: " . convert(memory_get_usage()) . "<br>";
print "Memória disponível real: " . convert(memory_get_usage(true)) . "<br>";

print "<h2>Informações do Servidor</h2>";

print "Número de CPUs: " . get_cpu_info() . "<br>";
print "Memória RAM total: " . get_ram_info() . "<br>";

print "<h2>Informações de Rede</h2>";

print "Ping para google.com: " . ping_domain("google.com") . "<br>";
print "Ping para banco de dados: " . ping_domain("localhost", 3306) . "<br>";
print "Ping para banco de dados (soma $vn_times vezes): " . $ping_db . "<br>";
print "Ping para banco de dados (média): " . ($ping_db / $vn_times) . "<br>";

print "<h2>Informações do MySQL</h2>";

print "Versão do MySQL: " . get_mysql_version() . "<br>";

print "<div style='margin-left: 20px;'>";

print "<h3>Variáveis de Query</h3>";

print get_mysql_query_variables();

print "<h3>Variáveis InnoDB</h3>";

print get_mysql_innodb_variables();

print "</div>";

print "<h2>Execuções</h2>";

foreach ($va_exs as $vs_ex)
{
    print $vs_ex . "<br>";
}

function convert($size)
{
    if (!is_numeric($size)) {
        return $size;
    }

    $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}
function get_cpu_info()
{
    $vs_cpu_info = file_get_contents("/proc/cpuinfo");
    $va_cpu_info = explode("\n", $vs_cpu_info);
    $va_cpu_info = array_filter($va_cpu_info, function ($vs_line)
    {
        return strpos($vs_line, "processor") !== false;
    });

    return count($va_cpu_info);
}

function get_ram_info()
{
    $vs_mem_info = file_get_contents("/proc/meminfo");
    $va_mem_info = explode("\n", $vs_mem_info);
    $va_mem_info = array_filter($va_mem_info, function ($vs_line) {
        return strpos($vs_line, "MemTotal") !== false;
    });

    $vs_mem_info = preg_replace("/[^0-9]/", "", $va_mem_info[0]);
    return convert($vs_mem_info * 1024);
}

function ping_domain($domain, $port = 80, $times = 1)
{
    $vn_sum = 0;
    for ($i = 0; $i < $times; $i++)
    {
        $vn_sum += ping($domain, $port);
    }

    return $vn_sum;
}

function ping($domain, $port = 80)
{
    $start_time = microtime(true);
    $file = fsockopen($domain, $port, $errno, $errstr, 10);
    $stop_time = microtime(true);

    if (!$file) {
        if ($errno == 110) {
            $status = "Timeout";
        } else {
            $status = "Failed";
        }
    } else {
        fclose($file);
        $status = ($stop_time - $start_time);
    }

    return $status;
}

function get_mysql_version()
{
    $vo_banco = Banco::get_instance();
    $va_resultado = $vo_banco->executar_sql("SELECT VERSION() as version");
    return $va_resultado[0]["version"];
}

function get_mysql_query_variables()
{
    $vo_banco = Banco::get_instance();
    $va_resultado = $vo_banco->executar_sql("SHOW VARIABLES LIKE 'query_cache%'");
    $vs_result = "";
    foreach ($va_resultado as $va_result)
    {
        $vs_result .= $va_result["Variable_name"] . ": " . $va_result["Value"] . "<br>";
    }

    return $vs_result;
}

function get_mysql_innodb_variables()
{
    $vo_banco = Banco::get_instance();
    $va_resultado = $vo_banco->executar_sql("SHOW VARIABLES LIKE 'innodb_buffer%'");
    $vs_result = "";

    foreach ($va_resultado as $va_result)
    {
        $vs_result .= $va_result["Variable_name"] . ": " . $va_result["Value"] . "<br>";
    }

    return $vs_result;
}