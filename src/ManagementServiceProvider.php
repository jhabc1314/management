<?php

namespace JackDou\Management;

use Illuminate\Support\Facades\View;
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

        //合并用户自定义配置和默认配置
        $this->mergeConfigFrom(
            __DIR__ . '/../configs/management.php',
            'management'
        );
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

        //Publish config files
        $this->publishes([
            __DIR__ . '/../configs/management.php' => config_path('management.php'),
        ]);


        //注册视图合成器
        View::composer('management::home', 'JackDou\Management\Http\ViewComposers\HomeComposer');
        View::composer([
            'management::servers.index',
            'management::servers.create',
            'management::servers.edit',
            'management::servers.client',
            'management::servers.supervisor',
        ], 'JackDou\Management\Http\ViewComposers\ServerComposer');
        View::composer([
            'management::crontab.index',
            'management::crontab.create',
            'management::crontab.edit',
        ], 'JackDou\Management\Http\ViewComposers\CrontabComposer');

        //发布配置，公共asset
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/management'),
        ], 'management_assets');
    }
}
