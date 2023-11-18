<?php

const AUTOLOAD = true;

spl_autoload_register(function ($class)
{
    if ($class == 'config')
    {
        require_once __DIR__ . '/config/config.php';
        return;
    }

    $base_dir = __DIR__ . '/src/';

    $business_folder = config::get(["pasta_business"]);
    $business_subfolders = array_filter(glob($base_dir . 'lib/' . $business_folder . '/*'), 'is_dir');
    $system_folders = ['database', 'functions', 'lib/html', 'lib/system'];

    $system_folders = array_map(function($folder) use ($base_dir) {
        return $base_dir . $folder;
    }, $system_folders);

    $dirs = array_merge($business_subfolders, $system_folders);

    foreach ($dirs as $dir)
    {
        $file = $dir . '/' . strtolower($class) . '.php';
        if (file_exists($file))
        {
            require_once $file;
        }
    }

});

register_shutdown_function(function() {
    $error = error_get_last();
    $error_report_level = config::get(["error_report"]);

    if ($error !== NULL && $error['type'] <= $error_report_level) {
        unset($_SERVER);
        $stacktrace = $error['message'] . " - " . $error['file'] . " - " . $error['line'];
        $vs_summary = array_search($error["type"], get_defined_constants(true)["Core"]);
        $vs_codigo = utils::log($vs_summary, $stacktrace);

        if ($error["type"] == E_ERROR) {
            session::redirect("erro.php?codigo=" . $vs_codigo);
        }
    }
});