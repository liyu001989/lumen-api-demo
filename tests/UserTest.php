<?php

namespace Tests;

class UserTest extends TestCase
{
    public function testRegister()
    {
        $this->post("/api/users", ['email'=>'1234@gmail.com', 'name'=>'test','password'=>'123456'])->assertResponseStatus(201);
    }

    public function testUserShow()
    {
        $this->get('/api/user', $this->header)->assertResponseOk();
    }
}
