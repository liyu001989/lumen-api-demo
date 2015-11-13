<?php
namespace App\Transformer;
use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return $user->toArray();
    }
}
