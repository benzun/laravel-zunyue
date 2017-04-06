<?php

namespace App\Http\Middleware;

use Closure;

class HasWechatAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('wechat_account')) {
            return redirect('/admin');
        }

        return $next($request);
    }
}
