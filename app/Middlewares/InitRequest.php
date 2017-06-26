<?php

namespace App\Middlewares;

use Closure;

class InitRequest
{
    public function handle($request, Closure $next)
    {
        $request->headers['App-Version-Code'] = isset($_SERVER['HTTP_APP_VERSION_CODE']) ? intval($_SERVER['HTTP_APP_VERSION_CODE']) : 0;
        $request->headers['Platform'] = isset($_SERVER['HTTP_PLATFORM']) ? intval($_SERVER['HTTP_PLATFORM']) : 0;
        $request->headers['Client-Type'] = isset($_SERVER['HTTP_CLIENT_TYPE']) ? intval($_SERVER['HTTP_CLIENT_TYPE']) : 0;
        $request->headers['Timestamp'] = isset($_SERVER['HTTP_TIMESTAMP']) ? intval($_SERVER['HTTP_TIMESTAMP']) : 0;
        $request->headers['Signature'] = isset($_SERVER['HTTP_SIGNATURE']) ? $_SERVER['HTTP_SIGNATURE'] : '';

        return $next($request);
    }
}
