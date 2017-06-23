<?php

namespace App\Transformers;

abstract class Transformer
{
    public function collection($items)
    {
        return array_map([$this, 'transform'], $items);
    }

    abstract public function transform($item);
}
