<?php

    require_once dirname(__FILE__) . "/../components/entry_point.php";

    if (!session::is_same_site())
    {
        utils::log(
            "Tentativa de acesso externo",
            "Tentativa de acesso externo" . __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__
        );
        session::redirect();
    }

    if (isset($_POST["desconectar_google_drive"]))
    {
        google_drive::save_token("", 'drive', $_SESSION["usuario_logado_codigo"]);
    }
    elseif (isset($_POST["sair"]))
    {
        session::logout(false);
    }

    session::redirect();


