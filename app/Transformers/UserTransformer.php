<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $authorization;

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
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
