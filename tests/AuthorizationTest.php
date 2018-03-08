<?php

namespace Tests;

class AuthorizationTest extends TestCase
{
    public function testDestroyToken()
    {
        $this->delete('/api/authorizations/current', [], $this->header)->assertResponseStatus(204);
    }

    public function testGetToken()
    {
        $this->post('/api/authorizations', ['email' => '123@gmail.com', 'password' => '123456'])->assertResponseStatus(201);
    }

    public function testRefreshToken()
    {
        $this->put('/api/authorizations/current', [], $this->header)->assertResponseOk();
    }
}
