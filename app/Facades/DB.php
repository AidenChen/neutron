<?php

namespace App\Facades;

use App\Services\PdoService;

class DB extends Facade
{
    public static function getFacadeAccessor()
    {
        return PdoService::class;
    }
}
