<?php

namespace App\Services;

class IocService
{
    public static function getInstance($className)
    {
        $paramArr = self::getMethodParams($className);

        return (new \ReflectionClass($className))->newInstanceArgs($paramArr);
    }

    public static function make($className, $methodName, $params = [])
    {
        $instance = self::getInstance($className);
        $paramArr = self::getMethodParams($className, $methodName);

        return $instance->{$methodName}(...array_merge($paramArr, $params));
    }

    protected static function getMethodParams($className, $methodsName = '__construct')
    {
        $class = new \ReflectionClass($className);
        $paramArr = [];

        if ($class->hasMethod($methodsName)) {
            $construct = $class->getMethod($methodsName);

            $params = $construct->getParameters();
            if (count($params) > 0) {
                foreach ($params as $key => $param) {
                    if ($paramClass = $param->getClass()) {
                        $paramClassName = $paramClass->getName();
                        $args = self::getMethodParams($paramClassName);
                        $paramArr[] = (new \ReflectionClass($paramClass->getName()))->newInstanceArgs($args);
                    }
                }
            }
        }

        return $paramArr;
    }
}
