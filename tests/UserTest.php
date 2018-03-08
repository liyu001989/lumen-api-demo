<?php

namespace Tests;

use Faker\Factory;
use App\Models\User;

class UserTest extends TestCase
{
    public function testRegister()
    {
        $this->withoutJobs();
        $faker = Factory::create();
        $email = $faker->unique()->safeEmail;
        $this->post('/api/users', ['email' => $email, 'name' => $faker->name, 'password' => $faker->password])->assertResponseStatus(201);
        User::where('email', '=', $email)->forceDelete();
    }

    public function testUserShow()
    {
        $this->get('/api/user', $this->header)->assertResponseOk();
    }

    public function testUserShowWithoutToken()
    {
        $this->get('/api/user')->assertResponseStatus(401);
    }
}
