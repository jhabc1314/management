<?php

namespace JackDou\Management\Models;

use Illuminate\Database\Eloquent\Model;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Management\ManagementService;
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

    /**
     * 服务治理 根据算法进行服务发现，服务降低和服务熔断
     *
     * 算法模式: 连续ping服务的每个节点10次，pong几次则权重调整为多少。
     * 如果一次都不通则下线，如果只剩一个在线节点则不下线
     *
     */
    public function governance()
    {
        $nodes = json_decode($this->server_node);
        $node_cnt = count($nodes);
        if ($node_cnt <= 0) {
            //TODO 发送alert级别通知消息
            return;
        }
        $collect = collect($nodes);
        $offline = 0;
        $collect->transform(function ($node) use ($node_cnt, &$offline) {
            $success = 0;
            $cnt = 10;
            for ($i = 0; $i < $cnt; $i++) {
                $pong = Service::getInstance($this->server_name, $node->ip)
                    ->ping()
                    ->getResult();
                $pong == 'pong' and $success++;
            }
            //一次不通
            if ($success == 0) {
                //服务节点大于1个且当前总节点-已下线节点大于1，下线
                if ($node_cnt > 1 && ($node_cnt - $offline) > 1) {
                    $node->status = 'offline';
                    $offline++;
                }
                //存在服务不通的节点 TODO 发送 alert 级别通知
            } else {
                //至少通了一次，那就上线，保持一定权重比例
                $node->status = 'online';
                $node->weight = $success * 10;
            }
            return $node;
        });
        $this->server_node = json_encode($collect->all());
        $this->save();
        //TODO 修改了以后下发到客户端 事件方式

        $clients = Client::where('server_id', $this->id)
            ->where('client_status', 1)
            ->get();
        if ($this->server_name == SwooleService::NODE_MANAGER) {
            //下发节点管理服务的配置时先下发到当前机器，防止找不到其他的机器节点ip
            ManagementService::pushServerNode($this->server_name, $this->server_node);
        }
        foreach ($clients as $client) {
            //请求对应客户机器的node_manager 下发配置内容
            Service::getInstance(SwooleService::NODE_MANAGER, $client->client_ip)
                ->call('ManagementService::pushServerNode', $this->server_name, $this->server_node)
                ->getResult();
        }
    }
}
