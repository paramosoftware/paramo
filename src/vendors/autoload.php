<?php

spl_autoload_register(function ($class)
{
    $vs_file_to_require = __DIR__ . '/' . (str_replace('\\', '/', $class) . '.php');
    if(file_exists($vs_file_to_require))
        require_once $vs_file_to_require;
});

?>