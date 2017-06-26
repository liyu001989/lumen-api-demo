<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $authorization;

    public function transform(User $user)
    {
        return $user->attributesToArray();
    }

    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;

        return $this;
    }

    public function includeAuthorization(User $user)
    {
        if (! $this->authorization) {
            return $this->null();
        }

        return $this->item($this->authorization, new AuthorizationTransformer());
    }
}
