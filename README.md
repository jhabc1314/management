# management
微服务网站服务管理平台 可视化管理服务

## 说明
management 是基于 ` jackdou\laravel-swoole`  和 `adminLTE3` 的 `laravel` 可视化管理后台工具，有服务添加，服务节点下发等功能

## 安装
- composer require jackdou\management
- run php artisan vendor:publish
    - 选择 management_assets 回车
    - 选择 JackDou\Management\ManagementServiceProvider 回车
## 使用
- 默认的权限系统使用的是项目里的 `auth:web`， 可以在 `config/management.php` 内修改
- 打开 youdomain.url/management 访问

## 使用教程
- [传送门](http://www.jackdou.top)
