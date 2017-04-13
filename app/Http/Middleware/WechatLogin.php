<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorHmtlOrJsonException;
use App\Http\Business\AccountBusiness;
use App\Http\Business\UsersBusiness;
use App\Http\Controllers\Helper;
use Closure;

class WechatLogin
{
    /**
     * @var
     */
    private $account_business;

    /**
     * @var
     */
    private $users_business;

    /**
     * WechatLogin constructor.
     */
    public function __construct(AccountBusiness $account_business, UsersBusiness $users_business)
    {
        $this->account_business = $account_business;
        $this->users_business = $users_business;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $identity = $request->get('identity');

        if (empty($identity) || strlen($identity) != 32) {
            // 错误处理
            throw new ErrorHmtlOrJsonException(10000);
        }

        // 检测是否已经微信登录
        if (!session('oauth_user_' . $identity)) {
            $account_info = $this->account_business->show($identity);

            try {
                $wechat_app = Helper::wechatApp([
                    'app_id' => $account_info->app_id,
                    'secret' => $account_info->secret
                ]);
            } catch (\Exception $e) {
                throw new ErrorHmtlOrJsonException(20003);
            }

            if ($request->has('state') && $request->has('code')) {
                session(['oauth_user_' . $identity => $wechat_app->oauth->user()]);
                return redirect()->to($this->getTargetUrl($request));
            }

            return $wechat_app->oauth->scopes(['snsapi_userinfo'])->redirect($request->fullUrl());
        }

        $wechat_user_info = session('oauth_user_' . $identity);
        dd($wechat_user_info);

        return $next($request);
    }


    /**
     * Build the target business url.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getTargetUrl($request)
    {
        $queries = array_except($request->query(), ['code', 'state']);

        return $request->url() . (empty($queries) ? '' : '?' . http_build_query($queries));
    }
}
