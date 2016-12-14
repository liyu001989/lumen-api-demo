<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充好多用户
        $users = factory(User::class)->times(10)->make();
        // password 是hidden的，toArray的时候会过滤掉，所以需要取一下属性
        // 不然的话直接 User::insert($users->toArray()) 即可
        $datas = array_map(function ($value) {
            return $value->getAttributes();
        }, $users->all());

        User::insert($datas);
    }
}
