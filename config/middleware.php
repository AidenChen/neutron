<?php

return [
    'auth' => \App\Middleware\GetUserFromToken::class,
    'init.request' => \App\Middleware\InitRequest::class,
];
