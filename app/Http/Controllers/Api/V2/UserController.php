<?php

/**
 * 用户控制器
 */
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\BaseController;
use App\Transformer\UserTransformer;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::paginate();

        return $this->response->paginator($users, new UserTransformer);
    }

    public function store()
    {
        $request = \Request::all();
        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->save();

        return $this->response->created(route('users.show', $user->id));
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound();
        };

        return $this->response->item($user, new UserTransformer);
        return $this->response->array($user->toArray());
    }

    public function update($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound();
        };

        $request = \Request::all();
        $updateColumns = ['name', 'email'];

        foreach ($updateColumns as $column) {
            if (array_key_exists($column, $request)) {
                $user->$column = $request[$column];
            }
        }

        $user->save();

        /**
         * 看rfc http://tools.ietf.org/html/rfc7231#section-4.3.4
         * 如果创建了新资源应该返回201
         * 如果更新了资源，可以返回200 或者 204
         * return $this->response->array($user->toArray()); 这样返回也行
         */
        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound();
        };

        $user->delete();
        /**
         * 看rfc http://tools.ietf.org/html/rfc7231#section-4.3.4
         * If a DELETE method is successfully applied, the origin server SHOULD
         * send
         *
         * a 202 (Accepted) status code if the action will likely succeed
         * but has not yet been enacted,
         *
         * a 204 (No Content) status code if the
         * action has been enacted and no further information is to be supplied,
         *
         * or a 200 (OK) status code if the action has been enacted and the
         * response message includes a representation describing the status.
         */
        return $this->response->noContent();
    }
}
