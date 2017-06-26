<?php

namespace App\Middlewares;

use App\Exceptions\ApplicationException;
use Closure;

class GetUserFromToken
{
    public function handle($request, Closure $next)
    {
        $request->token = 1;

        return $next($request);
    }
}
