<?php

namespace App\Http\Business;

use App\Http\DataAccess\AccountsDao;
use App\Http\Controllers\Helper;
use App\Exceptions\ErrorHmtlOrJsonException;

class AccountBusiness
{
    /**
     * AccountBusiness constructor.
     * @param AccountsDao $dao
     */
    public function __construct(AccountsDao $dao)
    {
        $this->dao = $dao;
    }
    
    /**
     * 获取公众号列表
     * @param array $condition
     * @param array $select_field
     */
    public function index(array $condition = [], array $select_field = ['*'])
    {
        return $this->dao->index($condition, $select_field);
    }


    /**
     * 添加公众号
     * @param array $store_data
     */
    public function store(array $store_data = [])
    {
        $store_data['admin_users_id'] = Helper::getAdminLoginInfo();
        $store_data['token']          = Helper::createTokenOrIdentity();
        $store_data['aes_key']        = Helper::createEncodingAESKey();
        $store_data['identity']       = Helper::createTokenOrIdentity();
        $store_data['type']           = Helper::getWechatType($store_data['app_id'], $store_data['secret']);
        return $this->dao->store($store_data);
    }

    /**
     * 获取公众号详情
     * @param string $identity
     */
    public function show($identity = null)
    {
        if (empty($identity)) throw new ErrorHmtlOrJsonException(10000);

        $result = $this->dao->show($identity);

        if (empty($result)) throw new ErrorHmtlOrJsonException(20001);

        return $result;
    }

    /**
     * @param null $identity
     * @param array $update_data
     */
    public function update($identity = null, $update_data = [])
    {
        if (empty($identity) || empty($update_data)) throw new ErrorHmtlOrJsonException(10000);
        
        return $this->dao->update($identity, $update_data);
    }
}