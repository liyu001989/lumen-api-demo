<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use SoftDeletes;

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function recentComments($limit = 15)
    {
        /**
         * 对于分页和列表，dingo 默认会预加载include, 除非关闭
         * 当然预加载是有用的，我不希望关闭它，然后手动处理
         * 这样就会有个问题，当一个列表 include, 另一个列表
         * 比如 帖子列表  posts?include=comments , 帖子列表项获取最新的几条评论
         * 如果不增加limit 那么就会查询出来每个帖子的所有评论，数据多了不太好
         * 所以在关系中增加limit
         */
        return $this->comments()->limit($limit);

    }
}
