<?php

namespace JackDou\Management\Models;

use Illuminate\Database\Eloquent\Model;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Services\SwooleService;

class Server extends Model
{
    //
    protected $table = 'management_server';

    /**
     * 查询节点的运行状态
     *
     * @param $nodes array
     *
     */
    public function nodeStatus(&$nodes):void
    {
        foreach ($nodes as &$node) {
            $run_status = Service::getInstance(SwooleService::NODE_MANAGER, $node->ip)
                ->call('ManagementService::nodeStatus', $this->server_name)
                ->getResult(2);
            $node->run_status = !empty($run_status['data']) ? $run_status['data'] : 'query fail, please try to refresh';
        }
    }
}
