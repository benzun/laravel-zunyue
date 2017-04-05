<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AccountsModel','App\Model\Account');
        $this->app->bind('UsersModel','App\Model\User');
        $this->app->bind('TagsModel','App\Model\Tag');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'AccountsModel',
            'UsersModel',
            'TagsModel',
        ];
    }
}
