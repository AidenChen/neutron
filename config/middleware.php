<?php

return [
    'auth' => \App\Middlewares\GetUserFromToken::class,
    'init.request' => \App\Middlewares\InitRequest::class,
];
