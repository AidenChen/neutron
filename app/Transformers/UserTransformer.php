<?php

namespace App\Transformers;

class UserTransformer extends Transformer
{
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'nick' => $item['nick'],
            'name' => $item['name'] ? $item['name'] : '',
            'phone' => $item['phone'] ? $item['phone'] : '',
            'email' => $item['email'] ? $item['email'] : '',
            'avatar_path' => $item['avatar_path'] ? $item['avatar_path'] : '',
            'is_active' => $item['is_active'],
            'created_at' => strtotime($item['created_at']),
            'updated_at' => strtotime($item['updated_at']),
        ];
    }
}
