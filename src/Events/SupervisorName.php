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

class SupervisorName
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $server;
    public $new_name;

    /**
     * Create a new event instance.
     * 修改了服务配置名称以后自动修改supervisor下的配置文件名
     *
     * @return void
     */
    public function __construct(Server $server, string $new_name)
    {
        //
        $this->server = $server;
        $this->new_name = $new_name;
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
