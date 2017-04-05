<?php

namespace App\Http\DataAccess;

use Illuminate\Support\Facades\App;

class AccountsDao
{
    /**
     * AccountDao constructor.
     */
    public function __construct()
    {
        $this->model = App::make('AccountsModel');
    }

    /**
     * 获取公众号列表
     * @param array $condition
     * @param array $select_field
     * @return mixed
     */
    public function index(array $condition = [], array $select_field = ['*'])
    {
        $builder = $this->model->select($select_field);

        // 后台用户
        if (isset($condition['admin_users_id'])) {
            $builder->where('admin_users_id', $condition['admin_users_id']);
        }

        $builder->orderBy('id', 'DESC');

        if (isset($condition['all'])) {
            return $builder->get();
        }

        $page_size = isset($condition['page_size']) && is_numeric($condition['page_size']) ? abs($condition['page_size']) : 20;
        return $builder->paginate($page_size);
    }

    /**
     * 获取公众号详情
     * @param null $identity
     * @return mixed
     */
    public function show($identity = null)
    {
        return $this->model->where('identity', $identity)->first();
    }


    /**
     * 添加公众号
     * @param array $store_data
     */
    public function store(array $store_data = [])
    {
        return $this->model->create($store_data);
    }

    /**
     * 更新公众号
     * @param null $identity
     * @param array $update_data
     */
    public function update($identity = null, array $update_data = [])
    {

    }
}