<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as Basic;

class TestCase extends Basic
{
    protected $header;
    protected $user;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\Models\User')->create();
        $token = \Auth::fromUser($this->user);
        $this->header = ['Authorization' => 'bearer '.$token];
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->user->forceDelete();
    }
}
