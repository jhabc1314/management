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
        //获取当前登录用户信息
        return view("management::home");
    }
}
