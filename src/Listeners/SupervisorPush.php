<?php

namespace JackDou\Management\Listeners;

use Illuminate\Support\Facades\Log;
use JackDou\Management\Events\SupervisorStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use JackDou\Management\Models\Client;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Management\ManagementService;
use JackDou\Swoole\Services\SwooleService;

class SupervisorPush
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SupervisorStatus  $event
     * @return void
     */
    public function handle(SupervisorStatus $event)
    {
        //
        $server = $event->server;
        $clients = Client::where('server_id', $server->id)
            ->where('client_status', 1)
            ->get();
        if ($server->server_name == SwooleService::NODE_MANAGER) {
            //下发节点管理服务的配置时先下发到当前机器，防止找不到其他的机器节点ip
            ManagementService::pushServerNode($server->server_name, $server->server_node);
        }
        foreach ($clients as $client) {
            //请求对应客户机器的node_manager 下发配置内容
            Service::getInstance(SwooleService::NODE_MANAGER, $client->client_ip)
                ->call('ManagementService::pushServerNode', $server->server_name, $server->server_node)
                ->getResult();
        }
        Log::info('push');
    }
}
