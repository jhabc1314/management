<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    //
    public function home($timezone = "PRC")
    {

        return view("management::server");
        //echo Carbon::now($timezone)->toDateTimeString();
    }
}
