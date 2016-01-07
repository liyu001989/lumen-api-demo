<?php
/**
 * 接口基础控制器
 */
namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    // 接口帮助调用
    use Helpers;

        // 请求
    protected $request;

    // 返回错误的请求
    protected function errorBadRequest($message='')
    {
        return $this->response->array($message)->setStatusCode(400);
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
