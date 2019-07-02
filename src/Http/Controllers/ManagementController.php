<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;

class ManagementController extends Controller
{
   //
    public function home()
    {
        //获取当前登录用户信息
        return view("management::home");
    }
}
