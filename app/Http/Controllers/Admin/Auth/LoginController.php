<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    /*
   |--------------------------------------------------------------------------
   | Login Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the registration of new users, as well as the
   | authentication of existing users. By default, this controller uses
   | a simple trait to add these behaviors. Why don't you explore it?
   |
   */
    use AuthenticatesUsers, ThrottlesLogins;

    protected $redirectTo = 'admin';
    protected $loginView = 'admin.auth.login';
    protected $username = 'mobile';
    protected $redirectAfterLogout = 'admin/login';
    protected $maxLoginAttempts = 3;
    protected $lockoutTime = 600;


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'getLogout']);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'bail|required|regex:/^1[0-9]{10}$/',
            'password'             => 'required',
        ], [
            $this->loginUsername() . '.required' => '手机号码不能为空！',
            $this->loginUsername() . '.regex'    => '手机号码格式不正确！',
            'password.required'                  => '密码不能为空！'
        ]);
    }


    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'failed_login' => '账号或密码不正确！',
            ]);
    }


    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function getThrottleKey(Request $request)
    {
        return $request->ip();
    }


    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->secondsRemainingOnLockout($request);

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                'failed_login' => '登录尝试太多次。请' . $seconds . '秒再登录',
            ]);
    }
}
