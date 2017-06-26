<?php

namespace App\Middlewares;

use App\Exceptions\ApplicationException;
use Closure;

class InitRequest
{
    public function handle($request, Closure $next)
    {
        $request->init = 1;

        return $next($request);
    }
}
