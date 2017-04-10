<?php


namespace App\Http\Business;


use App\Exceptions\ErrorHmtlOrJsonException;
use App\Http\DataAccess\UsersDao;

class UsersBusiness
{
    /**
     * UsersBusiness constructor.
     * @param UsersDao $dao
     */
    public function __construct(UsersDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $condition
     */
    public function index(array $condition = [], array $select_field = ['*'], array $relevance = [])
    {
        $relevance['tags'] = function ($query) use ($condition) {
            return $query->select(['id', 'name', 'tag_id']);
        };

        return $this->dao->index($condition, $select_field, $relevance);
    }

    /**
     * 根据openid获取详情
     * @param null $openid
     */
    public function show($openid = null)
    {
        if (empty($openid)) throw new ErrorHmtlOrJsonException(10000);
        return $this->dao->show($openid);
    }

    /**
     * 添加微信用户
     * @param array $store
     */
    public function store(array $store_data = [])
    {
        if (empty($store_data)) throw new ErrorHmtlOrJsonException(10000);

        return $this->dao->store($store_data);
    }


    /**
     * 更新
     * @param null $openid
     * @param array $update_array
     */
    public function update($openid = null, array $update_data = [])
    {
        if (empty($openid) || empty($update_data)) throw new ErrorHmtlOrJsonException(10000);
        
        return $this->dao->update($openid, $update_data);
    }
}