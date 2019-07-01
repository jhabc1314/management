<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function home($timezone = "PRC")
    {
        return view("management::server");
    }
}
