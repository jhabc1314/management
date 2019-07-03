<?php
/**
 * Created by PhpStorm.
 * User: jackdou
 * Date: 19-7-1
 * Time: 下午4:15
 */

namespace JackDou\Management\Services;

class ManagementService
{
    /**
     * 下发服务节点配置信息到本节点机器
     *
     * @param $server_name
     * @param $server_node
     *
     * @return array
     */
    public static function pushServerNode($server_name, $server_node)
    {
        $node_path = config('swoole.node_conf_path') . $server_name . '.conf';
        file_put_contents($node_path, $server_node);
        return self::success();
    }


    public static function success($data = [])
    {
        return ['code' => 0, 'data' => $data];
    }

    public static function fail($msg, $code = -1)
    {
        return ['code' => $code, 'msg' => $msg];
    }

}