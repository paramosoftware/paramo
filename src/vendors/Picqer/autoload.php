<?php

spl_autoload_register(function ($class)
{
    var_dump($class);
    require_once __DIR__ . '/' . (str_replace('\\', '/', $class) . '.php');
});

?>