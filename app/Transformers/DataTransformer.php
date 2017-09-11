<?php

namespace App\Transformers;

use App\Models\Data;

class DataTransformer extends BaseTransformer
{
    public function transform($data)
    {
        if ($data instanceof Data) {
            $data = $data->toArray();
        }

        return $data;
    }
}
