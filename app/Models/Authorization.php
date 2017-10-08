<?php

namespace App\Models;

use Carbon\Carbon;

class Authorization
{
    protected $token;

    protected $payload;

    public function __construct($token = null)
    {
        $this->token = $token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getToken()
    {
        if (! $this->token) {
            throw new \Exception('请设置token');
        }

        return $this->token;
    }

    public function getPayload()
    {
        if (! $this->payload) {
            $this->payload = \Auth::setToken($this->getToken())->getPayload();
        }

        return $this->payload;
    }

    public function getExpiredAt()
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('exp'))
            ->toDateTimeString();
    }

    public function getRefreshExpiredAt()
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('iat'))
            ->addMinutes(config('jwt.refresh_ttl'))
            ->toDateTimeString();
    }

    public function user()
    {
        return \Auth::authenticate($this->getToken());
    }

    public function toArray()
    {
        return [
            // maybe you need a id when use jsonapi.org format
            //'id' => hash('md5', $this->getToken()),
            'token' => $this->getToken(),
            'expired_at' => $this->getExpiredAt(),
            'refresh_expired_at' => $this->getRefreshExpiredAt(),
        ];
    }
}
