<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

class BaseTransformer extends TransformerAbstract
{
    public function transform(Model $object)
    {
        return $object->toArray();
    }
}
