<?php

namespace App\Http\DataAccess;


use Illuminate\Support\Facades\App;

class TagsDao
{
    /**
     * TagsDao constructor.
     */
    public function __construct()
    {
        $this->model = App::make('TagsModel');
    }

    /**
     * @param array $condition
     * @param array $select_field
     * @param array $relevance
     */
    public function index(array $condition = [], array $select_field = ['*'], array $relevance = [])
    {
        $builder = $this->model->select($select_field);

        // 后台登录用户
        if (isset($condition['admin_user_id'])) {
            $builder->where('admin_users_id', $condition['admin_user_id']);
        }

        // 所属公众号
        if (isset($condition['account_id'])) {
            $builder->where('account_id', $condition['account_id']);
        }

        $builder->orderBy('id', 'DESC');

        if (!empty($relevance)) {
            // 关联用户标签
            $builder->with($relevance);
        }

        if (isset($condition['all'])) {
            return $builder->get();
        }

        $page_size = isset($condition['page_size']) && is_numeric($condition['page_size']) ? abs($condition['page_size']) : 20;
        return $builder->paginate($page_size);
    }

    /**
     * @param int $tag_id
     * @param array $additional_condition
     */
    public function show($tag_id = 0, array $condition = [], array $select_field = ['*'])
    {
        $builder = $this->model->select($select_field);

        // 后台登录用户
        if (isset($condition['admin_user_id'])) {
            $builder->where('admin_users_id', $condition['admin_user_id']);
        }

        // 所属公众号
        if (isset($condition['account_id'])) {
            $builder->where('account_id', $condition['account_id']);
        }

        return $builder->where('id', $tag_id)->first();
    }

    /**
     * @param array $store_data
     */
    public function store(array $store_data = [])
    {
        return $this->model->create($store_data);
    }

    /**
     * @param array $condition
     * @param array $update_data
     */
    public function update($tag_id = 0, $account_id = 0, $name = '')
    {
        $info = $this->model->where('id', $tag_id)->where('account_id', $account_id)->first();

        $info->name = $name;

        $result = $info->save();

        if (empty($result)) return false;

        return $info;
    }

    /**
     * @param int $tag_id
     * @param int $account_id
     */
    public function delete($tag_id = 0, $account_id = 0)
    {
        return $this->model->where('id', $tag_id)->where('account_id', $account_id)->delete();
    }
}