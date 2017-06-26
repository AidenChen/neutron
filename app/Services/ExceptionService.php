<?php

namespace App\Services;

class ExceptionService
{
    public function getErrorMessage($code, $params = [], $details = [])
    {
        $message = config('error')[$code];

        if (count($details)) {
            foreach ($details as $key => $val) {
                if (strlen($val)) {
                    $message = $val;
                }
            }
        }
        if (count($params)) {
            foreach ($params as $key => $val) {
                $message = str_replace(':' . $key, $params[$key], $message);
            }
        }
        return $message;
    }
}
