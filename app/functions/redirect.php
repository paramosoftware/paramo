<?php

require_once dirname(__FILE__) . '/../../src/vendors/google-api-client/vendor/autoload.php';
require_once dirname(__FILE__) . '/../components/entry_point.php';

$redirect_uri = config::get(["drive_redirect_uri"]);

$client = google_drive::get_client();

$status = "success";

if (isset($_GET['code']))
{
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    google_drive::save_token(json_encode($token), 'drive', $_SESSION["usuario_logado_codigo"]);
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
elseif (isset($_GET['error']))
{
    $status = "error";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Redirecionando...</title>
</head>
<body>
<p>
    Caso não seja redirecionado automaticamente, feche esta janela e tente novamente.
</p>

<script>
    window.opener.changeGoogleDriveButton('<?= $status ?>');
    window.close();
</script>

</body>



