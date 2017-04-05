<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class syncWechatUsers extends Job implements ShouldQueue
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

    }
}
