<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Business\UsersBusiness;
use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Business\AccountBusiness;
use App\Exceptions\ErrorHmtlOrJsonException;

class WechatController extends Controller
{
    /**
     * @var UsersBusiness
     */
    private $users_business;

    /**
     * @var AccountBusiness
     */
    private $account_business;

    /**
     * @var
     */
    private $account_info;

    /**
     * @var
     */
    private $message;

    /**
     * @var
     */
    private $wechat_app;

    public function __construct(UsersBusiness $users_business, AccountBusiness $account_business)
    {
        // 初始化
        $this->users_business   = $users_business;
        $this->account_business = $account_business;
    }

    /**
     * @param Request $request
     */
    public function service(Request $request)
    {
        $request_data = $request->only([
            'identity', 'signature', 'timestamp', 'nonce', 'echostr'
        ]);

        // 判断身份标识
        if (empty($request_data['identity']) || strlen($request_data['identity']) != 32) throw new ErrorHmtlOrJsonException(10000);
        // 获取公众号信息
        $account_info       = $this->account_business->show($request_data['identity']);
        $this->account_info = $account_info;

        try {
            // 实例化微信服务端
            $wechat_app       = Helper::wechatApp([
                'app_id'  => $account_info->app_id,
                'secret'  => $account_info->secret,
                'token'   => $account_info->token,
                'aes_key' => $account_info->aes_key
            ]);
            $this->wechat_app = $wechat_app;
        } catch (\Exception $e) {
            return false;
        }

        $wechat_service = $wechat_app->server;

        $wechat_service->setMessageHandler(function ($message) {

            $this->message = $message;

            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case 'subscribe' :
                            return self::eventSubscribe();
                            break;
                        case 'unsubscribe':
                            self::eventUnsubscribe();
                            break;
                        case 'qualification_verify_success':
                            self::eventQualificationVerifySuccess();
                            break;
                    }
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                default:
                    return '收到其它消息';
                    break;
            }
        });

        $response = $wechat_service->serve();

        // 微信公众号配置接入请求,并更新微信公众号接入状态
        if (!empty($request_data['echostr']) && $response->getContent() == $request_data['echostr']) {
            // 更新接入状态
            $this->account_business->update($request_data['identity'], ['activate' => 'yes']);
        }

        return $response;
    }

    /**
     * 关注事件
     */
    private function eventSubscribe()
    {
        $wechat_user_info = [];

        $openid = $this->message->FromUserName;
        $type   = $this->account_info->type;


        $user_info = $this->users_business->show($openid);


        if ($type == 'auth_service') {
            $wechat_user_info = $this->wechat_app->user->get($openid)->toArray();
        }

        // 添加微信用户
        if (empty($user_info) && $type == 'auth_service') {
            $wechat_user_info['admin_users_id'] = $this->account_info->admin_users_id;
            $wechat_user_info['account_id']     = $this->account_info->id;
            $this->users_business->store($wechat_user_info);
        } elseif ($type == 'auth_service') { //更新微信用户信息
            $this->users_business->update($openid, $wechat_user_info);
        }

        return '欢迎关注[' . $this->account_info->name . ']';
    }

    /**
     * 取消关注事件
     */
    private function eventUnsubscribe()
    {
        $openid = $this->message->FromUserName;
        $this->users_business->update($openid, [
            'subscribe' => 0
        ]);
    }

    /**
     * 资质认证事件
     */
    private function eventQualificationVerifySuccess()
    {
        $this->account_business->update($this->account_info->identity, ['type' => 'auth_service']);
    }


}
