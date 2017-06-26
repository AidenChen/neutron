<?php

return [
    ['pattern' => 'post /v1/auth/login', 'controller' => 'AuthController', 'function' => 'login', 'middleware' => ['init.request']],
    ['pattern' => 'get /v1/user/index', 'controller' => 'UserController', 'function' => 'index', 'middleware' => ['init.request', 'auth']],
    ['pattern' => 'get /v1/user/lesson/index', 'controller' => 'UserController', 'function' => 'indexLesson', 'middleware' => ['init.request', 'auth']],
];
