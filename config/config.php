<?php
class config
{
    protected static array $configs = array();

    private function __construct() {}

    private static function load() : void
    {
        $constants = dirname(__FILE__) . "/constants.php";
        $default_config = dirname(__FILE__) . "/settings.php";
        $custom_config = dirname(__FILE__) . "/custom/settings.php";
        $env_config = dirname(__FILE__) . "/custom/envs.php";

        if (!file_exists($default_config) || !file_exists($env_config) || !file_exists($constants))
        {
            require_once dirname(__FILE__) . "/redirect.php";
            exit();
        }

        self::$configs = array_merge(require_once $env_config, require_once $default_config, require_once $constants);

        if (file_exists($custom_config))
        {
            $va_custom_config = require_once $custom_config;
            self::$configs = array_replace_recursive(self::$configs, $va_custom_config);
        }
    }

    public static function get($pa_config_path)
    {
        if (empty(self::$configs))
        {
            self::load();
        }

        $va_configs = self::$configs;

        foreach ($pa_config_path as $vs_config)
        {
            if (!isset($va_configs[$vs_config]))
                return null;
            else
                $va_configs = $va_configs[$vs_config];
        }

        return $va_configs;
    }
}