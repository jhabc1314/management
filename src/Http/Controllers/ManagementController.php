<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;

class ManagementController extends Controller
{
    public function __construct()
    {
        $guard = config('management.guard') ?: 'web';
        $this->middleware('auth:' . $guard);
    }
   //
    public function home()
    {
        //获取当前登录用户信息
        return view("management::home");
    }
}
