<?php

namespace App\Transformers;

class LessonTransformer extends Transformer
{
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'created_at' => strtotime($item['created_at']),
            'updated_at' => strtotime($item['updated_at']),
        ];
    }
}
