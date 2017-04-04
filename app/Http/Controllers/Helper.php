<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class Helper
{
    /**
     * 检测公众号 AppID,AppSecret输入是否正确
     * @param null $app_id
     * @param null $secret
     */
    public static function checkWechatAccount($app_id = null, $secret = null)
    {
        $wechat = self::wechatApp([
            'app_id' => $app_id,
            'secret' => $secret
        ]);

        try {
            $wechat->access_token->getToken(true);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取公众号类型
     * @param null $app_id
     * @param null $secret
     */
    public static function getWechatType($app_id = null, $secret = null)
    {
        $wechat = self::wechatApp([
            'app_id' => $app_id,
            'secret' => $secret
        ]);

        // 是否有获取用户信息权限
        try {
            $wechat->user->lists();
            return 'subscribe';
        } catch (\Exception $e) {
            return 'auth_service';
        }
    }

    /**
     * 实例化微信SDK
     * @param array $config
     */
    public static function wechatApp(array $config = [])
    {
        $wechat_config = array_merge(config('wechat'), $config);
        return new Application($wechat_config);
    }

    /**
     * 获取后台登陆用户信息
     * @param string $field
     */
    public static function getAdminLoginInfo($field = 'id')
    {
        $login_admin_user = Auth::user();
        return isset($login_admin_user[$field]) ? $login_admin_user[$field] : $login_admin_user['id'];
    }

    /**
     * 生成微信公众号Token令牌 OR 身份表示
     */
    public static function createTokenOrIdentity()
    {
        return md5(uniqid(md5(microtime(true)), true));
    }

    /**
     * 生成微信公众号EncodingAESKey(消息加解密密钥)
     */
    public static function createEncodingAESKey($length = 43)
    {
        $string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        shuffle($string);
        return implode('', array_slice($string, 0, $length));
    }
}