<?php
$vb_debug = config::get(["debug"]);
$error_report = config::get(["error_report"]);

ini_set('display_errors', $vb_debug);
ini_set('display_startup_errors', $vb_debug);
error_reporting($error_report);