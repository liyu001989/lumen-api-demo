<?php

namespace App\Models;

use Carbon\Carbon;

class Authorization
{
    protected $token;

    public function __construct($token = null)
    {
        $this->token = $token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getPayload()
    {
        return \Auth::setToken($this->token)->getPayload();
    }

    public function toArray()
    {
        $payload = $this->getPayload();
        // get expired time
        $expiredAt = Carbon::createFromTimestamp($payload->get('exp'))->toDateTimeString();
        // refresh expired time
        $refreshExpiredAt = Carbon::createFromTimestamp($payload->get('iat'))
            ->addMinutes(config('jwt.refresh_ttl'))
            ->toDateTimeString();

        return [
            // maybe you want a id for jsonapi.org format
            //'id' => hash('md5', $this->token),
            'token' => $this->token,
            'expired_at' => $expiredAt,
            'refresh_expired_at' => $refreshExpiredAt,
        ];
    }
}
