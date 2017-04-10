<?php


namespace App\Http\DataAccess;


use Illuminate\Support\Facades\App;

class UsersDao
{
    /**
     * UsersDao constructor.
     */
    public function __construct()
    {
        $this->model = App::make('UsersModel');
    }

    /**
     * @param array $condition
     * @param array $select_field
     * @param array $relevance
     * @return mixed
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

        // 标签ID
        if (isset($condition['tag_id'])) {
            $builder->whereHas('tags', function ($query) use ($condition) {
                return $query->where('id', $condition['tag_id']);
            });
        }

        $builder->orderBy('subscribe_time', 'DESC');

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
     * 根据openid获取详情
     * @param null $openid
     */
    public function show($openid = null)
    {
        return $this->model->where('openid', $openid)->first();
    }

    /**
     * @param array $store_data
     */
    public function store(array $store_data = [])
    {
        return $this->model->create($store_data);
    }


    /**
     * 更新
     * @param null $openid
     * @param array $update_data
     */
    public function update($openid = null, array $update_data = [])
    {
        $allow = [
            'subscribe', 'nickname', 'sex', 'city', 'province', 'headimgurl', 'unionid', 'remark'
        ];

        $allow_update_data = [];

        foreach ($update_data as $key => $value) {
            if (in_array($key, $allow)) {
                $allow_update_data[$key] = $value;
            }
        }

        return $this->model->where('openid', $openid)->update($allow_update_data);
    }
}