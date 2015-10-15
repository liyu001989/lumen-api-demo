<?php
/**
 * @access    public
 * @author    liyu
 * @desc      ClientUser 模型
 */

namespace App\Models;

class User extends BaseModel
{
    // 查询用户的时候，不暴露密码
    protected $hidden = ['password'];
}
