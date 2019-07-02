<?php
/**
 * Created by PhpStorm.
 * User: jiangheng
 * Date: 19-7-1
 * Time: 下午6:14
 */

namespace JackDou\Management\Http\ViewComposers;

class ViewComposer
{
    /**
     * 激活的侧边栏
     *
     * @var array
     */
    protected $active = [
        'home_active' => '',
        'server_active' => '',
    ];

    const ACTIVE = 'active';
}