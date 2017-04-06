<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SyncQRcode extends Job implements ShouldQueue
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
        $account_business = app('App\Http\Business\AccountBusiness');
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

        $qr_code_url = 'http://open.weixin.qq.com/qr/code/?username=' . $account_info->wechat_id;
        $qr_code_content = file_get_contents($qr_code_url);
        if (empty($qr_code_content)) {
            return;
        }

        // 上传到七牛OSS
        $disk = Storage::disk('qiniu');
        // 上传路径
        $qrcode_name = 'wechat/qrcode/' . $account_info->wechat_id;
        // 判断是否存在
        if ($disk->has($qrcode_name)) {
            $disk->delete($qrcode_name);
        }

        // 上传成功，更新公众号二维码地址
        if ($disk->put($qrcode_name, $qr_code_content)) {
            $account_info->qr_code = $qrcode_name;
            $account_info->headimgurl = $qrcode_name . '?' . 'imageMogr2/gravity/Center/crop/86x86/blur/1x0/quality/100';
            $account_info->save();
        }
    }
}
