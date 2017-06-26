<?php

namespace App\Facades;

use App\Services\TokenService;

class Token extends Facade
{
    public static function getFacadeAccessor()
    {
        return TokenService::class;
    }
}
