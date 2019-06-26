<?php

namespace JackDou\Management;

use Illuminate\Support\ServiceProvider;

class ManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //加载帮助函数
        require __DIR__ . "/Helpers/HelperFunction.php";

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //加载管理台路由
        $this->loadRoutesFrom(__DIR__ . "/../routes/management.php");

        //加载migration
        $this->loadMigrationsFrom(__DIR__ . "/../migrations");

        //加载views
        $this->loadViewsFrom(__DIR__ . "/../resources/views", "management");

        //发布配置，公共asset
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/management'),
        ], 'management_assets');
    }
}
