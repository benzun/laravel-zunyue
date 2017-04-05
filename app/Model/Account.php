<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_users_id', 'identity', 'name', 'wechat_id', 'original_id', 'type', 'app_id', 'secret', 'token', 'aes_key'
    ];
}
