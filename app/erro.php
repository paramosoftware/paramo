<?php

if (!defined("AUTOLOAD"))
{
    require_once dirname(__FILE__) . "/../autoload.php";
}

session::start_session();
if (!isset($_SESSION["usuario_token"]) || !$_GET["codigo"])
{
    session::redirect();
}

$vs_erro_mensagem = "";
$vs_erro_codigo = "";
$vs_erro_stacktrace = "";

$pasta_logs = config::get(["pasta_logs"]);
$vs_erro_codigo = $_GET["codigo"];

$vs_ultima_linha = "";
if (function_exists("popen"))
{
    $fp = popen("tail -n 1 " . $pasta_logs . date("Y-m-d") . ".log", "r");
    if ($fp)
    {
        $vs_ultima_linha = fgets($fp);
        pclose($fp);
    }
}
else
{
    $fp = fopen($pasta_logs . date("Y-m-d") . ".log", "r");
    while($result = fgets($fp)) {
        if (!feof($fp)) {
            $vs_ultima_linha = $result;
        }
    }
}

if ($vs_ultima_linha)
{
    $va_dados = explode("*-*", $vs_ultima_linha);

    $vd_erro_timestamp = strtotime($va_dados[0] ?? "");
    $vs_erro_mensagem = $va_dados[2] ?? "";
    $vs_erro_stacktrace = $va_dados[3] ?? "";

    $vd_current_time = strtotime(date("Y-m-d H:i:s"));

    if (abs($vd_current_time - $vd_erro_timestamp) > 120)
    {
        $vs_erro_mensagem = "Não foi possível recuperar a mensagem de erro.";
    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>
<div class="bg-light min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Aconteceu algum problema</h4>
                    <p>Por favor, informe o código do erro abaixo para a equipe de suporte.</p>
                    <hr>
                    <p>Código do erro: <?php echo $vs_erro_codigo; ?></p>
                    <?php echo $vs_erro_mensagem; ?>
                    <hr>

                    <?php config::get(["debug"]) && print_r($vs_erro_stacktrace . "<hr>"); ?>



                    <p class="mb-0 align-items-end-end text-right">
                        Voltar para a <a href="index.php">página inicial</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

