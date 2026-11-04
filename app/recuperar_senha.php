<?php

    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../autoload.php";
    }

    session::start_session();

    // Resposta uniforme: não revela se o login existe, se há e-mail cadastrado
    // ou se o envio falhou.
    $vs_resposta_uniforme = "Se os dados informados conferirem, enviaremos as instruções para o e-mail cadastrado.";

    if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST")
    {
        http_response_code(405);
        print $vs_resposta_uniforme;
        exit();
    }

    session::validar_csrf();

    $vs_usuario_login = trim((string) ($_POST['usuario_login'] ?? ""));
    $vs_ip = $_SERVER["REMOTE_ADDR"] ?? "desconhecido";

    if ($vs_usuario_login === "" || strlen($vs_usuario_login) > 250)
    {
        print $vs_resposta_uniforme;
        exit();
    }

    if (!limite_tentativas::permitir("recuperar_senha:" . strtolower($vs_usuario_login) . ":" . $vs_ip, 5, 900))
    {
        // Mesma resposta, evitando enumeração por diferença de mensagem.
        print $vs_resposta_uniforme;
        exit();
    }

    $vo_usuario = new usuario;
    $va_usuario = $vo_usuario->ler_lista(["usuario_login" => $vs_usuario_login, "usuario_ativo" => 1], "ficha", 0, 1);

    if (count($va_usuario) && !empty($va_usuario[0]["usuario_email"]))
    {
        $vo_usuario->recuperar_senha(
            $va_usuario[0]["usuario_codigo"],
            $va_usuario[0]["usuario_email"],
            $va_usuario[0]["usuario_nome"]
        );
    }

    print $vs_resposta_uniforme;
?>