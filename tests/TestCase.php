<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as Basic;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TestCase extends Basic
{
    //回滚数据库数据 如果增删改操作测试不想对原数据产生影响可以引入这个
    use DatabaseTransactions;
    protected $header;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $user = factory('App\Models\User')->create();
        $token = \Auth::fromUser($user);
        $this->header = ['Authorization' => 'bearer '.$token];
    }

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

}
