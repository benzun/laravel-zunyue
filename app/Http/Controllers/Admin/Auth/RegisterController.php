<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminUser;

class RegisterController extends Controller
{
    use RegistersUsers, ThrottlesLogins;

    protected $redirectTo = 'admin';
    protected $registerView = 'admin.auth.register';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'mobile'   => 'bail|required|regex:/^1[0-9]{10}$/|unique:admin_users,mobile',
            'nickname' => 'bail|required',
            'password' => 'required|min:6|confirmed',
        ], [
            'mobile.required'    => '手机号码不能为空！',
            'mobile.regex'       => '手机号码格式不正确！',
            'mobile.unique'      => '手机号码已存在！',
            'nickname.required'  => '昵称不能为空！',
            'password.required'  => '密码不能为空！',
            'password.min'       => '密码不能低于6位数！',
            'password.confirmed' => '两次密码不一致！',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return AdminUser
     */
    protected function create(array $data)
    {
        return AdminUser::create([
            'mobile'   => $data['mobile'],
            'nickname' => $data['nickname'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
