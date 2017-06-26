<?php

return [
    ['pattern' => 'post /v1/auth/login', 'controller' => 'AuthController', 'function' => 'login'],
    ['pattern' => 'get /v1/user/index', 'controller' => 'UserController', 'function' => 'index'],
    ['pattern' => 'get /v1/user/lesson/index', 'controller' => 'UserController', 'function' => 'indexLesson'],
];
