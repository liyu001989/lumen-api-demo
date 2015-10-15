<?php

/**
 * 用户控制器
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;

class FooController extends BaseController
{
    public function index()
    {
        return $this->response->array(['1,2,3,4']);
    }

    public function store()
    {
        return $this->response->created();
    }

    public function show($id)
    {
        return $this->response->array('show foo id:'.$id);
    }

    public function update($id)
    {
        return $this->response->array('updated');
    }

    public function destroy($id)
    {
        return $this->response->array('deleted');
    }
}
