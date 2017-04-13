<?php

namespace App\Exceptions;

use Exception;

class ErrorHmtlOrJsonException extends Exception
{
    /**
     * @var null|string
     */
    private $error_code = null;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $error_code_list = [
        // 基本
        10000 => '参数错误',
        10001 => '没有获取到数据',
        10002 => '添加失败',
        10003 => '更新失败',
        10004 => '删除失败',

        20001 => '公众号不存在',
        20002 => '没有权限获取微信用户资料',
        20003 => 'AppId(应用ID) 或者 AppSecret(应用密钥) 填写错误！',

        // 标签
        30001 => '标签名称已存在'
    ];

    /**
     * 初始化
     * JsonException constructor.
     * @param string $error_code
     * @param array $data
     */
    public function __construct($error_code)
    {
        $this->error_code = $error_code;
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
}