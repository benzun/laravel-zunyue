<?php

namespace App\Http\Controllers\Admin;

use App\Http\Business\AccountBusiness;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;

class IndexController extends Controller
{
    public function index(AccountBusiness $account_business)
    {
        $list = $account_business->index([
            'all'            => true,
            'admin_users_id' => Helper::getAdminLoginInfo()
        ]);

        return view('admin.index.index', compact('list'));
    }
}
