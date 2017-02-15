<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class AuthorizationTransformer extends TransformerAbstract
{
    public function transform($token)
    {
        return [
            'token' => $token
        ];
    }
}
