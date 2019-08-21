<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JackDou\Management\Models\Notify;
use JackDou\Management\Notifications\SwooleServerNotify;

class ManagementController extends Controller
{
    public $guard;

    public function __construct()
    {
        $this->guard = config('management.guard') ?: 'web';
        $this->middleware('auth:' . $this->guard);
    }

    public function home()
    {
        //发送通知消息
        event(new \JackDou\Management\Events\Notify([
            'notice_title' => '测试消息',
            'notice_text' => '你说是不是傻逼',
            'notice_level' => Notify::ERROR,
            'notice_url' => route('servers.index')
        ]));

        //获取当前登录用户信息
        return view("management::home");
    }
}
