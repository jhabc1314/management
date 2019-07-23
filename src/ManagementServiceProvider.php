<?php

namespace JackDou\Management;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use JackDou\Management\Models\Server;

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
        $this->app->booted([$this, 'customSchedule']);

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
            'management::crontab.log',
        ], 'JackDou\Management\Http\ViewComposers\CrontabComposer');

        //发布配置，公共asset
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/management'),
        ], 'management_assets');
    }


    public function customSchedule()
    {
        $schedule = $this->app->make(Schedule::class);
        $schedule->call(function() {
            //获取所有开启了服务治理的服务
            $servers = Server::where('server_status', 1)
                ->where('auto_governance', 1)
                ->get();

            $servers->each(function ($server) {
                /** @var $server Server */
                $server->governance();
            });
        })->everyMinute();
    }
}
