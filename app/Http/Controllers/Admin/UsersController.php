<?php

namespace App\Http\Controllers\Admin;

use App\Http\Business\TagsBusiness;
use App\Http\Business\UsersBusiness;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function getIndex(Request $request, UsersBusiness $users_business, TagsBusiness $tags_business)
    {
        $condition = $request->all();

        $list = $users_business->index(array_merge($condition, [
            'admin_users_id' => session('wechat_account.admin_users_id'),
            'account_id'     => session('wechat_account.account_id'),
        ]));

        // 获取用户标签组列表
        $tags_list = $tags_business->index([
            'all' => true,
            'admin_users_id' => session('wechat_account.admin_users_id'),
            'account_id'     => session('wechat_account.account_id'),
        ]);

        return view('admin.users.index', compact('list', 'condition','tags_list'));
    }
}
