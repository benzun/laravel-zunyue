<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * 表单提交错误重定向上一页
     * @param array $with_input
     * @param string $with_errors
     * @return $this
     */
    public function formSubmitError(array $with_input = [], $with_errors = '')
    {
        return redirect()->back()
            ->withInput($with_input)
            ->withErrors([
                'failed' => $with_errors,
            ]);
    }


    /**
     * 格式化成Json数据
     * Author weixinhua
     * @param $data
     * @return array
     */
    public function jsonFormat($data)
    {
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }

            if (!is_array($data)) {
                $data = (array)$data;
            }
        }

        return [
            'code' => 0,
            'msg'  => '成功',
            'data' => $data,
        ];
    }
}
