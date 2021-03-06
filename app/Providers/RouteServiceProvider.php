<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapAdminRoutes($router);

        $this->mapWechatRoutes($router);
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    protected function mapAdminRoutes(Router $router)
    {
        $router->group([
            'namespace'  => $this->namespace . '\Admin',
            'middleware' => 'web',
            'prefix'     => 'admin'
        ], function ($router) {
            require app_path('Routes/admin.php');
        });
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    protected function mapWechatRoutes(Router $router)
    {
        $router->group([
            'namespace'  => $this->namespace . '\Wechat',
            'middleware' => 'web',
            'prefix'     => 'wechat'
        ], function ($router) {
            require app_path('Routes/wechat.php');
        });
    }
}
