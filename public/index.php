<?php

// 初始化
header('Content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE,OPTIONS');
header('Access-Control-Allow-Headers: DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Accept-Language,Origin,Accept-Encoding,Content-Disposition');
date_default_timezone_set('PRC');
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require __DIR__ . '/../bootstrap/autoload.php';

// 获取请求数据
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];
$json = file_get_contents('php://input');
if ($json) {
    $data = json_decode($json, true);
} else {
    $data = $_POST;
}
$params = $_GET;

// 整理请求数据
$request = [];
$util = new App\Services\UtilService();
if ($method == 'GET') {
    $request['data'] = $util->dataDefenseSqlInsert($params);
} elseif (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
    $request['data'] = $util->dataDefenseSqlInsert($data);
}

// 匹配路由
$router = new App\Services\RouterService();
$route = $router->dispatch($method, $path);
if (!$route['success']) {
    $response['code'] = 40004;
    echo json_encode($response);
    exit;
}
$controller = $route['controller'];
$function = $route['function'];
$request['params'] = $route['params'];

// 获取响应数据
$className = 'App\Controllers\\' . $controller;
try {
    $response = \App\Services\IocService::getInstance($className)->$function($request);
} catch (\App\Exceptions\ApplicationException $e) {
    $response['code'] = $e->getCode();
}

// 整理响应数据
$return = [];
if (!isset($response['code'])) {
    $return['code'] = 0;
    $return['data'] = $response;
} else {
    $return['code'] = $response['code'];
}
$parameters = isset($response['params']) ? $response['params'] : [];
$return['message'] = (new \App\Services\ExceptionService())->getErrorMessage($return['code'], $parameters);

// 响应
echo json_encode($return);
