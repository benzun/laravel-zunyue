<?php

namespace App\Http\Controllers\Admin;

use App\Http\Business\AccountBusiness;
use App\Http\Controllers\Helper;
use App\Jobs\SyncQRcode;
use App\Jobs\SyncWechatUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Exceptions\ErrorHmtlOrJsonException;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->middleware('has-wechat-account', ['only' => ['getIndex']]);
    }

    /**
     * 获取公众号数据信息
     * Author weixinhua
     */
    public function getIndex()
    {
        return view('admin.account.index');
    }

    /**
     * 添加公众号
     */
    public function getStore()
    {
        return view('admin.account.store');
    }

    /**
     * 添加公众号
     * @param Request $request
     * @param AccountBusiness $account_business
     */
    public function postStore(StoreAccountRequest $request, AccountBusiness $account_business)
    {
        $store_data = $request->only([
            'name', 'wechat_id', 'original_id', 'app_id', 'secret'
        ]);

        // 检测微信公众号 AppId AppSecret 输入是否正确
        $result = Helper::checkWechatAccount($store_data['app_id'], $store_data['secret']);
        if ($result === false) {
            return $this->formSubmitError($store_data, 'AppId(应用ID) 或者 AppSecret(应用密钥) 填写错误！');
        }

        // 添加微信公众号
        $account = $account_business->store($store_data);
        if (empty($account)) {
            return $this->formSubmitError($store_data, '添加微信公众号失败！');
        }

        // 上传公众号二维码到七牛OSS
        $this->dispatch(new SyncQRcode($account->identity));

        // 判断是否认证服务号，进行同步微信用户信息
        if ($account->type == 'auth_service') {
            $this->dispatch(new SyncWechatUsers($account->identity));
        }

        $redirect_url = action('Admin\AccountController@getGuide') . '?identity=' . $account->identity;
        return redirect($redirect_url);
    }

    /**
     * 更新公众号信息页面
     * @param Request $request
     * @param AccountBusiness $account_business
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity'));

        return view('admin.account.update', compact('info'));
    }

    /**
     * 进入公众号平台
     * @param Request $request
     * @param AccountBusiness $account_business
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getChange(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity'));

        Session::forget('wechat_account');
        Session::put('wechat_account', $info);

        return redirect(action('Admin\AccountController@getIndex'));
    }


    /**
     * 微信公众号接入引导页
     * @param Request $request
     */
    public function getGuide(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity'));
        
        return view('admin.account.guide', compact('info'));
    }

    /**
     * Ajax检测是否接入成功
     * @param Request $request
     * @param AccountBusiness $account_business
     */
    public function getCheckActivate(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity'));
        $data = !empty($info) && $info->activate == 'yes' ? 'yes' : 'no';
        return $this->jsonFormat($data);
    }

}
