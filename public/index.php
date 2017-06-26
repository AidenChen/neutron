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
    $requestBody = json_decode($json, true);
} else {
    $requestBody = $_POST;
}
$requestParams = $_GET;

// 整理请求数据
$request = new StdClass();
$util = new App\Services\UtilService();
if ($method == 'GET') {
    $requestData = $util->dataDefenseSqlInsert($requestParams);
} elseif (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
    $requestData = $util->dataDefenseSqlInsert($requestBody);
}
foreach ($requestData as $key => $val) {
    $request->$key = $val;
}

try {
    // 匹配路由
    $router = new App\Services\RouterService();
    $route = $router->dispatch($method, $path);
    $controller = $route['controller'];
    $function = $route['function'];
    $middleware = isset($route['middleware']) && count($route['middleware']) ? $route['middleware'] : [];
    $routeParams = $route['params'];

    // 获取响应数据
    $className = 'App\Controllers\\' . $controller;
    $response = (new \App\Services\MiddlewareService($middleware))->then($request, function() use ($className, $function, $request, $routeParams) {
        return \App\Services\IocService::getInstance($className)->$function($request, ...$routeParams);
    });
} catch (\App\Exceptions\ApplicationException $e) {
    $response['code'] = $e->getCode();
}

// 整理响应数据
$return = [];
if (! isset($response['code'])) {
    $return['code'] = 0;
    $return['data'] = $response;
} else {
    $return['code'] = $response['code'];
}
$responseParams = isset($response['params']) ? $response['params'] : [];
$responseDetails = isset($response['details']) ? $response['details'] : [];
$return['message'] = (new \App\Services\ExceptionService())->getErrorMessage($return['code'], $responseParams, $responseDetails);

// 日志记录
$logger = new \Monolog\Logger('API');
$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(ROOTPATH . '/storage/logs/neutron.log', 5, \Monolog\Logger::DEBUG));
$logger->addDebug('API调用记录', $requestData);

// 响应
echo json_encode($return);
