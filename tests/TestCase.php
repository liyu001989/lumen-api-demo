<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as Basic;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TestCase extends Basic
{

    use DatabaseTransactions;//回滚数据库数据 如果增删改操作测试不想对原数据产生影响可以引入这个
    protected $header;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        //如果token过期则运行一次AuthorizationTest即可 平常测试可相应运行需要测试的单元
        $this->header = ['Authorization'=>'bearer '.@file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'token.txt')];
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
