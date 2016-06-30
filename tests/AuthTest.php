<?php

class AuthTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testAuth()
    {
        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('attempt')->once()->andReturn(false);
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);

        // 测试没有传参数
        $this->json('POST', 'api/auth/login')
            ->seeJsonEquals([
                'email' => ['The Email field is required.'],
                'password' => ['The password field is required.'],
            ])
            ->assertResponseStatus(400);


        
        $this->json('POST', 'api/auth/login', ['email'=>'foobar@bar.com', 'password' => 123456])
            ->seeJsonEquals([
                'status_code' => 403,
                'message' =>  'email or password is incorrect',
            ])
            ->assertResponseStatus(403);
    }
}
