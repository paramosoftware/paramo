<?php
    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../../autoload.php";
    }

    require dirname(__FILE__) . "/debug.php";

    utils::start_session();

    if (!utils::validate_user_session())
    {
        utils::logout();
    }

    require_once dirname(__FILE__) . "/../functions/autenticar_usuario.php";

