<?php

namespace App\Middleware;

use App\Facades\Token;
use Closure;

class GetUserFromToken
{
    public function handle($request, Closure $next)
    {
//        if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
//            $request->headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
//        } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
//            $request->headers['Authorization'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
//        }
        $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        $token = explode('Bearer ', $token)[1];
        $uid = Token::validate($token);
        $request->user = (object) [
            'id' => $uid
        ];

        return $next($request);
    }
}
