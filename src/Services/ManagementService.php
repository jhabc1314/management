<?php
/**
 * Created by PhpStorm.
 * User: jackdou
 * Date: 19-7-1
 * Time: 下午4:15
 */

namespace JackDou\Management\Services;

use Illuminate\Support\Facades\Log;
use JackDou\Management\Models\Supervisor;

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

    /**
     * 下发服务的supervisor配置，如果更改了服务名称就删除原有配置文件
     *
     * @param $server_name
     * @param $supervisor Supervisor
     * @param string $old_server_name
     *
     * @return array
     */
    public static function pushSupervisorConf($server_name, $supervisor, $old_server_name = '')
    {
        $supervisor_conf = config('management.supervisor');
        $conf = $supervisor->makeConf($server_name);
        if (!empty($old_server_name)) {
            $old_conf_path = $supervisor_conf['conf_path'] . $old_server_name . $supervisor_conf['conf_suffix'];
            file_exists($old_conf_path) and @unlink($old_conf_path);
        }
        $conf_path = $supervisor_conf['conf_path'] . $server_name . $supervisor_conf['conf_suffix'];
        file_put_contents($conf_path, implode(PHP_EOL, $conf));
        //下发以后更新
        $cmd = "supervisorctl update {$server_name}";
        return self::success(exec($cmd));
    }

    /**
     * 查询节点服务运行状态
     *
     * @param $server_name
     *
     * @return array
     */
    public static function nodeStatus($server_name)
    {
        $cmd = "supervisorctl status {$server_name}";
        return self::success(exec($cmd));
    }

    public static function restart($server_name)
    {
        $cmd = "supervisorctl restart {$server_name}";
        return self::success(exec($cmd));
    }

    public static function start($server_name)
    {
        $cmd = "supervisorctl start {$server_name}";
        return self::success(exec($cmd));
    }

    public static function stop($server_name)
    {
        $cmd = "supervisorctl stop {$server_name}";
        return self::success(exec($cmd));
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