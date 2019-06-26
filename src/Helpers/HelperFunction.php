<?php
/**
 * Created by PhpStorm.
 * User: jiangheng
 * Date: 19-6-26
 * Time: 下午6:19
 */

if (!function_exists("m_asset")) {
    /**
     * 生成management对应的资源路径
     *
     * @param string $path
     * @param $secure
     *
     * @return string
     */
    function m_asset(string $path, $secure = null) {
        return asset("vendor/management/" . $path, $secure);
    }
}