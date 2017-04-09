<?php

namespace App\Exceptions;

use Exception;

class ErrorHmtlOrJsonException extends Exception
{
    private $error_code = null;

    private $is_return_json = 0;

    private $data = [];

    private $error_code_list = [
        // 基本
        10000 => '参数错误',
        10001 => '没有获取到数据',
        10002 => '添加失败',
        10003 => '更新失败',
        10004 => '删除失败',

        20001 => '公众号不存在',
        20002 => '没有权限获取微信用户资料',

        // 标签
        30001 => '标签名称已存在'
    ];

    /**
     * 初始化
     * JsonException constructor.
     * @param string $error_code
     * @param array $data
     */
    public function __construct($error_code, $is_return_json = 0)
    {
        $this->error_code = $error_code;
        $this->is_return_json = $is_return_json;
    }

    /**
     * 获取错误消息
     * @return array
     */
    public function getErrorMessage()
    {
        $this->error_code = isset($this->error_code_list[$this->error_code]) ? $this->error_code : 10000;

        return [
            'error_code' => $this->error_code,
            'data'       => $this->data,
            'error_msg'  => $this->error_code_list[$this->error_code]
        ];
    }

    /**
     * 是否返回Json数据
     */
    public function isReturnjson()
    {
        return $this->is_return_json;
    }

}