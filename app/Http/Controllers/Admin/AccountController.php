<?php

namespace App\Http\Controllers\Admin;

use App\Http\Business\AccountBusiness;
use App\Http\Controllers\Helper;
use App\Jobs\syncQRcode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Exceptions\ErrorHtml;

class AccountController extends Controller
{
    public function getIndex()
    {

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
        $this->dispatch(new syncQRcode($account->identity));

        $redirect_url = action('Admin\AccountController@getGuide') . '?identity=' . $account->identity;
        return redirect($redirect_url);
    }

    /**
     * 更新公众号信息页面
     * @param Request $request
     * @param AccountBusiness $account_business
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws ErrorHtml
     */
    public function getUpdate(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity', ''));
        if (empty($info)) throw new ErrorHtml('没有获取到数据');
        
        return view('admin.account.update', compact('info'));
    }


    /**
     * 接入引导
     * @param Request $request
     */
    public function getGuide(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity', ''));
        if (empty($info)) throw new ErrorHtml('没有获取到数据');
        return view('admin.account.guide', compact('info'));
    }

    /**
     * @param Request $request
     * @param AccountBusiness $account_business
     */
    public function getCheckActivate(Request $request, AccountBusiness $account_business)
    {
        $info = $account_business->show($request->get('identity', ''));
        $data = !empty($info) && $info->activate == 'yes' ? 'yes' : 'no';
        return $this->jsonFormat($data);
    }

}
