<?php
    $_GET["start_time"] = $_GET["start_time"] ?? microtime(true);
    
    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../../autoload.php";
    }

    require dirname(__FILE__) . "/debug.php";

    require_once dirname(__FILE__) . "/../functions/autenticar_usuario.php";

