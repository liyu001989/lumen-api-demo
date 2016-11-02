<?php

namespace App\Jobs;

use App\Models\User;

class SendRegisterEmail extends Job
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $user = $this->user;
        $text = strtr('hello :name, hope you like this demo', [':name' => $user->name]);
        app('mailer')->raw($text, function ($message) use ($user) {
            $message->to($user->email);
        });
    }
}
