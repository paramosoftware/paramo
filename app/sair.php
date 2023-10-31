<?php

    require_once dirname(__FILE__) . "/components/entry_point.php";

    if (!session::is_same_site())
    {
        exit();
    }

    if (isset($_POST["desconectar_google_drive"]))
    {
        google_drive::save_token("", 'drive', $_SESSION["usuario_logado_codigo"]);
    }
    else
    {
        session::logout();
    }

    exit();


