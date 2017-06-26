<?php

namespace App\Services;

class UtilService
{
    public function dataDefenseSqlInsert($data, $ignore_magic_quotes = false)
    {
        if (is_string($data)) {
            // 防止被挂马、跨站攻击
            $data = trim(htmlspecialchars($data));
            if (($ignore_magic_quotes == true) || (! get_magic_quotes_gpc())) {
                // 防止sql注入
                $data = addslashes($data);
            }
            return $data;
        } elseif (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::dataDefenseSqlInsert($value);
            }
            return $data;
        } else {
            return $data;
        }
    }
}
