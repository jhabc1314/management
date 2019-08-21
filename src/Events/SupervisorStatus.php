<?php

namespace JackDou\Management\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use JackDou\Management\Models\Server;

class SupervisorStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $server;

    /**
     * Create a new event instance.
     * 修改服务状态，上线or下线时自动推送服务配置文件到客户端
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        //
        $this->server = $server;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
