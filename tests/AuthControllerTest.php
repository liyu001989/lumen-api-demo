<?php

class AuthControllerTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLogin()
    {
        // 测试没有传参数
        $this->json('POST', 'api/auth/login')
            ->seeJsonEquals([
                'email' => ['The Email field is required.'],
                'password' => ['The password field is required.'],
            ])
            ->assertResponseStatus(400);

        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('attempt')->twice()->andReturnValues([false, 123456]);
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);

        // 用户名密码错误
        $this->json('POST', 'api/auth/login', ['email'=>'foobar@bar.com', 'password' => 123456])
            ->seeJsonEquals([
                'status_code' => 403,
                'message' =>  'email or password is incorrect',
            ])
            ->assertResponseStatus(403);

        // 正确
        $this->json('POST', 'api/auth/login', ['email'=>'foobar@bar.com', 'password' => 123456])
            ->seeJsonEquals([
                'token' => 123456,
            ])
            ->assertResponseStatus(200);
    }

    public function testRegister()
    {
        $this->json('POST', 'api/auth/register')
            ->seeJsonEquals([
                'email' => ['The Email field is required.'],
                'password'  => ['The password field is required.'],
            ])
            ->assertResponseStatus(400);
    }
}
