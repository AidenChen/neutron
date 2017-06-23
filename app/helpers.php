<?php

if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return $default;
        }

        $arr = explode('.', $key);
        if (file_exists(ROOTPATH . '/config/' . $arr[0] . '.php')) {
            $config = require(ROOTPATH . '/config/' . $arr[0] . '.php');
        }
        unset($arr[0]);

        foreach ($arr as $item) {
            if (isset($config[$item])) {
                $config = $config[$item];
            } else {
                return $default;
            }
        }

        return $config;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if (!$key) {
            return $default;
        }

        $dotenv = new Dotenv\Dotenv(ROOTPATH);
        $dotenv->load();
        $env = getenv($key);

        if ($env === false) {
            return $default;
        }
        return $env;
    }
}
