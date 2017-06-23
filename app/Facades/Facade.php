<?php

namespace App\Facades;

class Facade
{
    public function __construct()
    {
        //
    }

    public static function getInstance($className)
    {
        if (! method_exists($className, 'getInstance')) {
            return new $className();
        }

        return $className::getInstance();
    }

    public static function getFacadeAccessor()
    {
        //
    }

    public static function __callstatic($method, $args)
    {
        $instance = static::getInstance(static::getFacadeAccessor());
        return call_user_func_array([$instance, $method], $args);
    }
}
