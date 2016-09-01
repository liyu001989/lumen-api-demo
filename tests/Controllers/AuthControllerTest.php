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
        // 只验证是否有这个key就行，不用管具体报错
        $this->json('POST', 'api/authorization')
            ->seeJsonStructure([
                'email',
                'password',
            ])
            ->assertResponseStatus(400);

        // 用户名密码错误
        $attemptResult = [];
        $attempValue = false;
        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('attempt')->andReturnUsing(function ($credentials) use (&$attemptResult, &$attempValue) {
            $attemptResult = $credentials;

            return $attempValue ? 'login-token' : false;
        });
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);

        // 请求的参数
        $params = [
            'email' => 'foo@bar.com',
            'password' => '123456',
            'foo' => 'bar',
        ];

        $this->json('POST', 'api/authorization', $params)
            ->seeJsonEquals([
                'status_code' => 403,
                'message' =>  'email or password is incorrect',
            ])
            ->assertResponseStatus(403);

        // attempt 预期的结果
        $expected = [
            'email' => 'foo@bar.com',
            'password' => '123456',
        ];
        $this->assertEquals($expected, $attemptResult);

        // 正确登录
        $attempValue = true;
        $this->json('POST', 'api/authorization', $params)
            ->seeJsonEquals([
                'token' => 'login-token',
            ])
            ->assertResponseStatus(200);
    }

    public function testRefreshToken()
    {
        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('refresh')->once()->andReturn('refresh-token');
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);

        $this->json('POST', 'api/auth/token/new')
            ->seeJsonEquals([
                'token' => 'refresh-token',
            ])
            ->assertResponseStatus(200);
    }

    public function testRegister()
    {
        //测试没有邮箱和密码
        $this->json('POST', 'api/users')
            ->seeJsonEquals([
                'email' => ['The Email field is required.'],
                'password'  => ['The password field is required.'],
            ])
            ->assertResponseStatus(400);

        // 测试邮箱格式不正确
        $params = ['email' => 'foobar', 'password' => 123456];
        $this->json('POST', 'api/users', $params)
            ->seeJsonEquals([
                'email' => ['The Email must be a valid email address.'],
            ])
            ->assertResponseStatus(400);

        // 测试用户已经存在
        $params = ['email' => 'foo@bar.com'];
        $expectedRules = [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];

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

        $createResult = [];
        //mock user repository
        $userMock = Mockery::mock('ApiDemo\Repositories\Contracts\UserRepositoryContract');
        $userMock->shouldReceive('create')->andReturnUsing(function ($attributes) use (&$createResult) {
            return $createResult = $attributes;
        });
        $this->app->instance('ApiDemo\Repositories\Contracts\UserRepositoryContract', $userMock);

        //$attributes = [];
        // mock auth
        $authMock = Mockery::mock('Illuminate\Auth\AuthManager');
        $authMock->shouldReceive('fromUser')->once()->andReturn('register-token');
        $this->app->instance('Illuminate\Auth\AuthManager', $authMock);

        $params = ['email' => 'foo@bar.com', 'password' => 123456];

        // 使用 $this->json 调用 request->get() 取不到值，有空研究一下
        $this->post('api/users', $params)
            ->seeJsonEquals(['email not pass'])
            ->assertResponseStatus(400);

        $this->post('api/users', $params)
            ->seeJsonEquals([
                'token'  => 'register-token',
            ])
            ->assertResponseStatus(200);

        // 验证参数正确
        $this->assertEquals('foo@bar.com', $createResult['email']);
        $this->assertTrue(password_verify(123456, $createResult['password']));
    }
}
