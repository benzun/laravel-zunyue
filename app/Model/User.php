<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_users_id',
        'account_id',
        'openid',
        'subscribe',
        'nickname',
        'sex',
        'city',
        'province',
        'headimgurl',
        'subscribe_time',
        'remark'
    ];

    /**
     * 多对多微信用户标签
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(__NAMESPACE__ . '\Tag', 'user_tag', 'users_id', 'tags_id');
    }
}
