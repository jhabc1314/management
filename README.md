# management
微服务网站服务管理平台 可视化管理服务

## 说明
management 是基于 ` jackdou/laravel-swoole`  和 `adminLTE3` 的 `laravel` 可视化管理后台工具，有服务添加，服务节点下发等功能

## 安装
- composer require jackdou/management
- run php artisan vendor:publish
    - 选择 management_assets 回车
    - 选择 JackDou\Management\ManagementServiceProvider 回车
    - 选择 JackDou\Swoole\SwooleServiceProvider 回车
## 使用
- 使用前请查看详细配置教程 [查看教程](http://www.jackdou.top)
- 默认的权限系统使用的是项目里的 `auth:web`， 可以在 `config/management.php` 内修改
- 打开 youdomain.url/management 访问
## 新特性
- v0.2.0 新增 `supervisor` 进程管理，后台一键启动，重启，停止
- v0.2.2 新增 `crontab` 调度任务服务 修复了多机器下发出错的问题，请使用最新版本
