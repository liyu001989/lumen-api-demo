<?php

namespace App\Http\Controllers\Api\V1;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    // 接口帮助调用
    use Helpers;

    // 请求
    protected $request;

    // 分页数
    protected $perPage;

    // 返回错误的请求
    protected function errorBadRequest($message = '')
    {
        return $this->response->array($message)->setStatusCode(400);
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->perPage = $request->get('per_page');
    }
}
