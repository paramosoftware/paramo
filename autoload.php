<?php

const AUTOLOAD = true;

spl_autoload_register(function ($class) {

    if ($class == 'config') {
        require_once __DIR__ . '/config/config.php';
        return;
    }

    $base_dir = __DIR__ . '/src/';

   $classes_folder = config::get(["classes"]);
   $classes_subfolders = ['aggregate', 'auxiliary', 'core'];

   $classes_subfolders = array_map(function($folder) use ($classes_folder) {
       return 'lib/'. $classes_folder . '/' . $folder;
    }, $classes_subfolders);

    $dirs = array_merge($classes_subfolders, ['database', 'functions', 'lib/html', 'lib/system']);

    foreach ($dirs as $dir) {
        $file = $base_dir . $dir . '/' . strtolower($class) . '.php';
        if (file_exists($file)) {
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