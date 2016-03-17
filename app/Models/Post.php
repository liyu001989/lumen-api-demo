<?php
namespace App\Models;

class User extends BaseModel
{
    // 可填充的字段
    protected $fillable = ['name'];

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
