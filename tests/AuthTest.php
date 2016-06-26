<?php

class AuthTest extends TestCase
{
    public function testSomethingIsTrue()
    {
        $response = $this->call('POST', 'api/auth/signin');

        dd($response->getContent());
    }
}
