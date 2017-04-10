<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Controllers\Helper;

class StoreAccountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'wechat_id'   => 'required',
            'original_id' => 'required|min:15',
            'app_id'      => 'sometimes|required|min:18|unique:accounts,app_id,null,id,admin_users_id,' . Helper::getAdminLoginInfo(),
            'secret'      => 'required|max:32',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'        => '名称不能为空！',
            'wechat_id.required'   => '微信号不能为空！',
            'original_id.required' => '原始ID不能为空！',
            'app_id.required'      => 'AppId(应用ID)不能为空！',
            'app_id.unique'        => 'AppId(应用ID)已存在！',
            'secret.required'      => 'AppSecret(应用密钥)不能为空！',
        ];
    }
}
