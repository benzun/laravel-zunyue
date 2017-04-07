<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Business\AccountBusiness;
use App\Http\Business\WechatBusiness;
use App\Exceptions\JsonException;

class WechatController extends Controller
{
    /**
     * @param Request $request
     * @param AccountBusiness $account_business
     * @param WechatBusiness $wechat_business
     */
    public function service(Request $request, AccountBusiness $account_business, WechatBusiness $wechat_business)
    {
        $request_data = $request->only([
            'identity', 'signature', 'timestamp', 'nonce', 'echostr'
        ]);

        // 判断身份标识
        if (empty($request_data['identity']) || strlen($request_data['identity']) != 32) throw new JsonException(1000);
        // 获取公众号信息
        $account_info = $account_business->show($request_data['identity']);
        if (empty($account_info)) throw new JsonException(20001);

        // 实例化微信服务端
        $wechat = Helper::wechatApp([
            'app_id'  => $account_info->app_id,
            'secret'  => $account_info->secret,
            'token'   => $account_info->token,
            'aes_key' => $account_info->aes_key
        ]);

        $wechat_service = $wechat->serve();

        $wechat_service->setMessageHandler(function ($message) use ($account_info, $wechat_business, $wechat) {
            return $wechat_business->messageHandler($message, $wechat, $account_info);
        });

        $response = $wechat_service->serve();

        // 微信公众号配置接入请求,并更新微信公众号接入状态
        if (!empty($request_data['echostr']) && $response->getContent() == $request_data['echostr']) {
            // 更新接入状态
            $account_business->update($request_data['identity'], ['activate' => 'yes']);
        }

        return $response;
    }
}
