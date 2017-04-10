<?php

namespace App\Jobs;

use App\Http\Controllers\Helper;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class SyncWechatUsers extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $account_info;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($identity = null)
    {
        $account_business   = app('App\Http\Business\AccountBusiness');
        $this->account_info = $account_business->show($identity);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account_info = $this->account_info;
        if (empty($account_info)) {
            return;
        }

        try {
            // 获取微信服务
            $wechat_service = Helper::wechatApp([
                'app_id' => $account_info->app_id,
                'secret' => $account_info->secret
            ]);

            // ========= 用户标签 =========
            // 获取所有的标签列表
            $tags = $wechat_service->user_tag->lists();

            $tag_model = App::make('TagsModel');

            $tags_array = $tags->tags;

            // 创建内置标签
            $built_in_array = ['淘宝/天猫', '京东'];
            foreach ($built_in_array as $built_in) {
                try {
                    $wechat_return = $wechat_service->user_tag->create($built_in);
                    $wechat_return = $wechat_return->tag;

                    $wechat_return['is_system'] = 'yes';
                    $wechat_return['count']     = 0;
                    array_push($tags_array, $wechat_return);
                } catch (\Exception $e) {

                }
            }

            foreach ($tags_array as $tag) {
                $is_system = $tag['name'] == '星标组' ? 'yes' : 'no';
                $tag_model->create([
                    'tag_id'         => $tag['id'],
                    'name'           => $tag['name'],
                    'count'          => $tag['count'],
                    'is_system'      => $is_system,
                    'admin_users_id' => $account_info->admin_users_id,
                    'account_id'     => $account_info->id
                ]);
            }

            // ========= 微信用户 =========
            // 用户服务
            $user_service = $wechat_service->user;

            $users_model = App::make('UsersModel');
            $next_openid = null;
            while ($next_openid !== false) {
                // 获取用户openid列表
                $user_data = $user_service->lists($next_openid);
                // 判断该公众号是否有关注用户
                if ($user_data->count == 0) {
                    break;
                }

                $user_openids_data = $user_data->data;
                // 分割成5等分,去获取
                $chunk_openid = array_chunk($user_openids_data['openid'], 10);

                foreach ($chunk_openid as $openids) {
                    // 根据openids 批量获取微信用户信息
                    $users_info = $user_service->batchGet($openids);

                    foreach ($users_info->user_info_list as $wechat_user_info) {
                        $unionid = isset($wechat_user_info['unionid']) ? $wechat_user_info['unionid'] : '';
                        $model   = $users_model->create([
                            'admin_users_id' => $account_info->admin_users_id,
                            'account_id'     => $account_info->id,
                            'openid'         => $wechat_user_info['openid'],
                            'subscribe'      => $wechat_user_info['subscribe'],
                            'nickname'       => $wechat_user_info['nickname'],
                            'sex'            => $wechat_user_info['sex'],
                            'city'           => $wechat_user_info['city'],
                            'province'       => $wechat_user_info['province'],
                            'headimgurl'     => $wechat_user_info['headimgurl'],
                            'subscribe_time' => $wechat_user_info['subscribe_time'],
                            'remark'         => $wechat_user_info['remark'],
                            'unionid'        => $unionid
                        ]);

                        if (!empty($wechat_user_info['tagid_list'])) {
                            $tagid_list = App::make('TagsModel')->select('id')->whereIn('tag_id', $wechat_user_info['tagid_list'])->get();
                            $model->tags()->attach($tagid_list);
                        }
                    }
                }

                $next_openid = empty($user_data->next_openid) || $user_data->count < 1000 ? false : $user_data->next_openid;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }

    }
}
