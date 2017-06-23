<?php

namespace App\Services;

class ExceptionService
{
    private $message = [
        0 => '请求成功！',
        40000 => '请求失败！',
        40001 => '操作未授权！',
        40002 => '已尝试 :time 次，将在 :min 分钟后解锁！',
        40003 => '路由解析失败！',
        42000 => '参数不合法！',
        44000 => '对象不可用！',
        46000 => '对象已存在！',
        48000 => '对象不存在！',
        50000 => '内部服务器错误！',
        50001 => '数据添加失败！',
        50002 => '数据删除失败！',
        50003 => '数据更新失败！',
        50004 => '数据查询失败！',
    ];

    public function getErrorMessage($code, $params = [])
    {
        $message = $this->message[$code];

        if (count($params)) {
            foreach ($params as $key => $val) {
                $message = str_replace(':' . $key, $params[$key], $message);
            }
        }
        return $message;
    }
}
