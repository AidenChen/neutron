<?php

namespace App\Services;

class MiddlewareService
{
    private $stack = [];

    /**
     * MiddlewareService constructor.
     * @param array $stack
     */
    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }


    private function getSlice($request)
    {
        return function ($stack, $pipe) use ($request) {
            return function () use ($stack, $pipe, $request) {
                return $pipe->handle($request, $stack);
            };
        };
    }

    public function then($request, $firstSlice)
    {
        $pipe = array_map(function ($item) {
            return IocService::getInstance(config('middleware')[$item]);
        }, array_reverse($this->stack));

        $callback = array_reduce($pipe, $this->getSlice($request), $firstSlice);

        return call_user_func($callback, $request);
    }
}
