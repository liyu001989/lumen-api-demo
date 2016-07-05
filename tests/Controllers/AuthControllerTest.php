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

    public function testRefreshToken()
    {
        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('refresh')->once()->andReturn(123123);
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);


        $this->json('POST', 'api/auth/token/refresh')
            ->seeJsonEquals([
                'token' => 123123,
            ])
            ->assertResponseStatus(200);
    }

    public function testRegister()
    {
        //测试没有邮箱和密码
        $this->json('POST', 'api/auth/register')
            ->seeJsonEquals([
                'email' => ['The Email field is required.'],
                'password'  => ['The password field is required.'],
            ])
            ->assertResponseStatus(400);

        // 测试邮箱格式不正确
        $this->json('POST', 'api/auth/register', ['email'=>'foobar', 'password' => 123456])
            ->seeJsonEquals([
                'email' => ['The Email must be a valid email address.']
            ])
            ->assertResponseStatus(400);

        // 测试用户已经存在
        $attributes = [];
        // mock auth
        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('fromUser')->once()->andReturn('register-token');
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);

        //mock user repository
        $userMock = Mockery::mock('ApiDemo\Repositories\Contracts\UserRepositoryInterface');
        $userMock->shouldReceive('create')->once();
        $this->app->instance('ApiDemo\Repositories\Contracts\UserRepositoryInterface', $userMock);

        // validate,验证数据库unique，所以需要mock
        // 两次，第一次返回错误，第二次正确
        \Validator::shouldReceive('make')
            ->twice()
            ->andReturnSelf()
            ->shouldReceive('fails')
            ->twice()
            ->andReturnValues([true, false])
            ->shouldReceive('messages')
            ->once()
            ->andReturn(['email not pass']);

        $this->json('POST', 'api/auth/register', ['email'=>'foobar@bar.com', 'password' => 123456])
            ->seeJsonEquals(['email not pass'])
            ->assertResponseStatus(400);

        $this->json('POST', 'api/auth/register', ['email'=>'foobar@bar.com', 'password' => 123456])
            ->seeJsonEquals([
                'token'  => 'register-token',
            ])
            ->assertResponseStatus(200);
    }
}
