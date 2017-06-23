<?php

namespace App\Services;

use App\Exceptions\ApplicationException;

class RouterService
{
    public function dispatch($method, $path)
    {
        $routes = require_once(ROOTPATH . '/routes/api.php');
        foreach ($routes as $route) {
            $pattern = $route['pattern'];
            $pats = explode(' ', $pattern);
            if (strcasecmp($pats[0], $method) == 0) {
                $params = $this->checkUrl($path, strtolower($pats[1]));
                if (!is_null($params)) {
                    array_shift($params);
                    $return = array_merge($route, [
                        'params' => $params
                    ]);
                    return $return;
                }
            }
        }

        throw new ApplicationException(40003);
    }

    private function checkUrl($path, $pattern)
    {
        $match = [];
        $pattern = ltrim(rtrim($pattern, '/'));
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        $pattern = str_replace(':id', '([0-9]+)', $pattern);
        if (preg_match($pattern, $path, $match) > 0) {
            return $match;
        }
    }
}
