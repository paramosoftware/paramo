<?php
    $_GET["start_time"] = $_GET["start_time"] ?? microtime(true);
    $_GET["num_queries"] = $_GET["num_queries"] ?? 0;
    $_GET["queries_execution_time"] = $_GET["queries_execution_time"] ?? 0;
    
    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../../autoload.php";
    }

    require dirname(__FILE__) . "/debug.php";

    require_once dirname(__FILE__) . "/../functions/autenticar_usuario.php";

