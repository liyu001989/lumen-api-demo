<?php

namespace Tests;

class AuthorizationTest extends TestCase
{
    public function testDestroyToken()
    {
        $this->delete("/api/authorizations/current", [], $this->header)->assertResponseStatus(204);
    }

    public function testGetToken()
    {
        $response = $this->call('POST', "/api/authorizations", ['email'=>'123@gmail.com', 'password'=>'123456']);
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'token.txt', json_decode($response->content())->data->token);
        $this->assertResponseStatus(201);
    }

    public function testRefreshToken()
    {
        $server = $this->transformHeadersToServerVars($this->header);
        $response = $this->call('PUT', "/api/authorizations/current", [], [], [], $server);
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'token.txt', json_decode($response->content())->data->token);
        $this->assertResponseOk();
    }
}
