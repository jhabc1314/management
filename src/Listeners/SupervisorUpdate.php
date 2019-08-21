<?php

namespace JackDou\Management\Listeners;

use Illuminate\Support\Facades\Log;
use JackDou\Management\Events\SupervisorName;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use JackDou\Management\Models\Supervisor;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Services\SwooleService;

class SupervisorUpdate
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
     * @param  SupervisorName  $event
     * @return void
     */
    public function handle(SupervisorName $event)
    {
        //
        $server = $event->server;

        $supervisor = Supervisor::where('server_id', $server->id)->first();
        $nodes = json_decode($server->server_node);
        if (!empty($nodes) && $supervisor) {
            foreach ($nodes as $node) {
                //重新修改supervisor配置项目名
                Service::getInstance(SwooleService::NODE_MANAGER, $node->ip)
                    ->call('ManagementService::pushSupervisorConf', $event->new_name, $supervisor, $server->server_name)
                    ->getResult();
            }
            Log::info('update');
        }
    }
}
