<?php
/**
 * Created by PhpStorm.
 * User: jiangheng
 * Date: 19-6-26
 * Time: 下午2:45
 */

return [
    /*
     * 默认使用的权限看守器
     *
     * 一般需要修改成后台用户使用的看守器
     */
    'guard' => 'web',

    /*
     * supervisor 进程管理配置
     */
    'supervisor' => [
        /*
        * supervisor 配置文件存放路径 以斜杠结尾
        * 根据机器上实际配置文件路径更改
        */
        'conf_path' => '/etc/supervisor/conf.d/',

        /*
        * supervisor 配置文件的名字
        */
        'conf_suffix' => '.conf',

        /*
         * 日志相关
         */
        'stdout_logfile_maxbytes' => '500MB',
        'stdout_logfile_backups' => 10,
        'stderr_logfile_maxbytes' => '500MB',
        'stderr_logfile_backups' => 10

    ],

    /*
     * 超级管理员，配置了以后，node_manger等关键服务只有管理员可见
     * 可以有多个人，以数组形式。
     * eg. '张三' or ['张三', '李四']
     */
    'super_user' => null,

    /*
     * 关键服务列表
     */
    'kernel_servers' => ['node_manager'],

];