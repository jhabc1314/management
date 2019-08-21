<?php

namespace JackDou\Management\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use JackDou\Management\Events\Notify;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Services\SwooleService;
use JackDou\Management\Models\Notify as NotifyModel;

class NoticeSend
{
    public $notice;

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
     * @param  Notify  $event
     * @return void
     */
    public function handle(Notify $event)
    {
        //
        $this->notice = $event->notice;
        $notice_user = config('management.notice_user');
        if (!empty($notice_user)) {
            if (is_array($notice_user)) {
                foreach ($notice_user as $user) {
                    $this->send($user);
                }
            } else {
                $this->send($notice_user);
            }
        }

    }

    private function param(string $type)
    {
        return !empty($this->notice[$type]) ? $this->notice[$type] : '';
    }

    private function send($user)
    {
        $model = new NotifyModel();
        $model->notice_title = $this->param('notice_title');
        $model->notice_user = $user;
        $model->notice_level = $this->param('notice_level');
        $model->notice_text = $this->param('notice_text');
        $model->notice_url = $this->param('notice_url');
        $model->save();
    }
}
