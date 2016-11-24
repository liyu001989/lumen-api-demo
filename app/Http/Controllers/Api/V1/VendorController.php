<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends BaseController
{
    public function login(Request $request)
    {
        $vendor = Vendor::first();
        $token = \Auth::guard('vendor')->fromUser($vendor);

        return $this->response->array(compact('token'));
    }

    public function show()
    {
        return $this->user();
    }
}
