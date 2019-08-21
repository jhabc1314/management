<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JackDou\Management\Models\Notify;

class NotifyController extends Controller
{
    public $guard;

    public function __construct()
    {
        $this->guard = config('management.guard') ?: 'web';
        $this->middleware('auth:' . $this->guard);
    }

    public function notify()
    {
        $user = Auth::guard($this->guard)->user()->name;
        $notices = Notify::where('notice_user', $user)
            ->where('notice_status', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view("management::notify.index", compact('notices'));
    }

    public function read($id)
    {
        $notify = Notify::findOrFail($id);
        if ($notify->notice_user != Auth::guard($this->guard)->user()->name) {
            abort(404, '不能已读非自己的消息哦');
        }
        $notify->notice_status = 1;
        $notify->save();
        return redirect($notify->notice_url);
    }
}
