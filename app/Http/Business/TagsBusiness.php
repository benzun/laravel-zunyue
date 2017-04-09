<?php

namespace App\Http\Business;


use App\Exceptions\ErrorHmtlOrJsonException;
use App\Http\Controllers\Helper;
use App\Http\DataAccess\TagsDao;
use Illuminate\Support\Facades\DB;

class TagsBusiness
{
    /**
     * TagsBusiness constructor.
     * @param TagsDao $dao
     */
    public function __construct(TagsDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $condition
     * @param array $select_field
     * @param array $relevance
     * @return mixed
     */
    public function index(array $condition = [], array $select_field = ['*'], array $relevance = [])
    {
        return $this->dao->index($condition, $select_field, $relevance);
    }


    /**
     * @param int $tag_id
     * @param array $condition
     * @return mixed
     * @throws ErrorHmtlOrJsonException
     */
    public function show($tag_id = 0, array $condition = [])
    {
        if (empty($tag_id) || empty($condition)) {
            throw new ErrorHmtlOrJsonException(10000);
        }

        return $this->dao->show($tag_id, $condition);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ErrorHmtlOrJsonException
     */
    public function store($name = '')
    {
        if (empty($name)) {
            throw new ErrorHmtlOrJsonException(10000);
        }

        // 开启事务
        DB::beginTransaction();

        try {
            // 同步微信标签
            $tag_service = Helper::wechatApp([
                'app_id' => session('wechat_account.app_id'),
                'secret' => session('wechat_account.secret'),
            ])->user_tag;

            $wechat_return = $tag_service->create($name);
            $wechat_return = $wechat_return->tag;

        } catch (\Exception $e) {
            DB::rollback();
            throw new ErrorHmtlOrJsonException(10002);
        }

        $result = $this->dao->store([
            'name'           => $name,
            'tag_id'         => $wechat_return['id'],
            'admin_users_id' => Helper::getAdminLoginInfo(),
            'account_id'     => session('wechat_account.id', 0),
        ]);

        if (empty($result)) {
            $tag_service->delete($wechat_return['id']);
            DB::rollback();
            throw new ErrorHmtlOrJsonException(10002);
        }

        DB::commit();

        return $result;
    }


    /**
     * @param array $condition
     * @param array $update_data
     */
    public function update($tag_id = 0, $account_id = 0, $name = '')
    {
        if (empty($tag_id) || empty($account_id) || empty($name)) {
            throw new ErrorHmtlOrJsonException(1000);
        }

        // 开启事务
        DB::beginTransaction();

        $info = $this->dao->update($tag_id, $account_id, $name);

        if (empty($info)) {
            DB::rollback();
            throw new ErrorHmtlOrJsonException(10003);
        }

        try {
            // 同步微信标签
            $tag_service = Helper::wechatApp([
                'app_id' => session('wechat_account.app_id'),
                'secret' => session('wechat_account.secret'),
            ])->user_tag;

            $tag_service->update($info->tag_id, $name);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new ErrorHmtlOrJsonException(10003);
        }

        return $info;
    }

    /**
     * @param int $tag_id
     * @param array $condition
     */
    public function delete($tag_id = 0, $account_id = 0, $wechat_tag_id = 0)
    {
        if (empty($tag_id) || empty($account_id) || empty($wechat_tag_id)) {
            throw new ErrorHmtlOrJsonException(10000);
        }

        // 开启事务
        DB::beginTransaction();

        $result = $this->dao->delete($tag_id, $account_id);

        if (empty($result)) {
            DB::rollback();
            throw new ErrorHmtlOrJsonException(10004);
        }

        try {
            // 同步微信标签
            $tag_service = Helper::wechatApp([
                'app_id' => session('wechat_account.app_id'),
                'secret' => session('wechat_account.secret'),
            ])->user_tag;

            $tag_service->delete($wechat_tag_id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new ErrorHmtlOrJsonException(10004);
        }

        return $result;
    }
}